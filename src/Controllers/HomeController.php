<?php

declare(strict_types=1);

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Services\UserService;
use Models\Settings;

class HomeController extends BaseController
{
    /**
     * Show home page
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        $user = UserService::current();
        $settings = Settings::findByUserId($user->getIdUser());
        // var_dump($settings, $user);

        $baseUrl = "https://v2.jokeapi.dev/joke/";

        $param = [];

        if ($settings->category_any) {
            $param['category'][] = "Any";
        }

        if ($settings->category_misc) {
            $param['category'][] = "Miscellaneous";
        }
        if ($settings->category_programming) {
            $param['category'][] = "Programming";
        }
        if ($settings->category_dark) {
            $param['category'][] = "Dark";
        }
        if ($settings->category_pun) {
            $param['category'][] = "Pun";
        }
        if ($settings->category_spooky) {
            $param['category'][] = "Spooky";
        }
        if ($settings->category_christmas) {
            $param['category'][] = "Christmas";
        }

        if ($settings->blacklist_nsfw) {
            $param['blacklistFlags'][] = "nsfw";
        }
        if ($settings->blacklist_religious) {
            $param['blacklistFlags'][] = "religious";
        }
        if ($settings->blacklist_political) {
            $param['blacklistFlags'][] = "political";
        }
        if ($settings->blacklist_racist) {
            $param['blacklistFlags'][] = "racist";
        }
        if ($settings->blacklist_sexist) {
            $param['blacklistFlags'][] = "sexist";
        }
        if ($settings->blacklist_explicit) {
            $param['blacklistFlags'][] = "explicit";
        }

        if ($settings->language_code) {
            $param['lang'] = $settings->language_code;
        }

        if ($settings->safe_mode) {
            $param['safe-mode'] = true;
        }

        if ($settings->allow_single) {
            $param['type'] = 'single';
        }
        if ($settings->allow_two_part) {
            $param['type'] = 'twopart';
        }

        if ($settings->allow_single && $settings->allow_two_part) {
            unset($param['type']);
        }

        $param['amount'] = 1;

        $categories = isset($param['category']) ? implode(',', $param['category']) : 'Any';
        $query = [];

        if (!empty($param['blacklistFlags'])) {
            $query['blacklistFlags'] = implode(',', $param['blacklistFlags']);
        }

        if (isset($param['lang'])) {
            $query['lang'] = $param['lang'];
        }

        if (isset($param['type'])) {
            $query['type'] = $param['type'];
        }

        if (isset($param['amount'])) {
            $query['amount'] = $param['amount'];
        }

        if (!empty($param['safe-mode'])) {
            $query['safe-mode'] = 'true';
        }

        $url = $baseUrl . $categories . '?' . http_build_query($query);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $responseJoke = curl_exec($ch);

        if ($responseJoke === false) {
            die('Curl error: ' . curl_error($ch));
        }


        $decoded = json_decode($responseJoke, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $jokes = $decoded;
        }

        curl_close($ch);


        if (isset($jokes['error']) && $jokes['error'] === true) {
            $jokes = [];
        }

        return $this->view->render($response, 'home/home.php', [
            'title' => 'WhatTheDuck | Home',
            'joke' => $jokes,
            'reveal' => false,
        ]);
    }

    public function profile(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        return $this->view->render($response, 'home/profile.php', [
            'title' => 'WhatTheDuck | Profile',
            'user' => UserService::current(),
        ]);
    }


    public function updateProfilePicture(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        $uploadedFile = $request->getUploadedFiles()['pfp'] ?? null;

        if (!$uploadedFile) {
            $_SESSION['error'] = 'No file uploaded.';
            return $response->withHeader('Location', '/profile')->withStatus(302);
        }

        if ($uploadedFile->getError() === UPLOAD_ERR_INI_SIZE) {
            $_SESSION['error'] = 'Profile picture is too large (max 10MB).';
            return $response->withHeader('Location', '/profile')->withStatus(302);
        }

        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Upload failed.';
            return $response->withHeader('Location', '/profile')->withStatus(302);
        }

        $maxSize = 10 * 1024 * 1024;
        if ($uploadedFile->getSize() > $maxSize) {
            $_SESSION['error'] = 'Profile picture is too large (max 10MB).';
            return $response->withHeader('Location', '/profile')->withStatus(302);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/bmp'];
        if (!in_array($uploadedFile->getClientMediaType(), $allowedTypes)) {
            $_SESSION['error'] = 'Invalid image format.';
            return $response->withHeader('Location', '/profile')->withStatus(302);
        }

        $user = UserService::current();

        $directory = __DIR__ . '/../../public/assets/pfp';

        foreach (glob($directory . '/' . $user->userName . '_pfp.*') as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        $extension = strtolower(pathinfo(
            $uploadedFile->getClientFilename(),
            PATHINFO_EXTENSION
        ));

        $filename = sprintf('%s_pfp.%s', $user->userName, $extension);

        $uploadedFile->moveTo($directory . '/' . $filename);

        $user->profilePicture = $filename;
        $_SESSION['user'] = $user;
        $user->save();

        $_SESSION['success'] = 'Profile picture updated successfully.';

        return $response
            ->withHeader('Location', '/profile')
            ->withStatus(302);
    }

    public function deleteAccount(Request $request, Response $response): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        $user = UserService::current();

        $user->delete();
        UserService::disconnect();

        return $response->withHeader('Location', '/profile')->withStatus(302);
    }

    public function secret(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'errors/418.php', [
            'title' => 'WhatTheDuck | Secret',
            'withMenu' => false,
        ]);
    }
}
