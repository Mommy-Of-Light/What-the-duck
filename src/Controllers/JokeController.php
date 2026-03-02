<?php

declare(strict_types=1);

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Services\UserService;
use Models\Settings;

class JokeController extends BaseController
{
    public function showJokes(Request $request, Response $response, array $args): Response
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

        $param['amount'] = $settings->joke_amount;

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

        return $this->view->render($response, 'joke/jokes.php', [
            'title' => 'WhatTheDuck | Jokes',
            'jokes' => $jokes,
        ]);
    }

    public function clearJokes(Request $request, Response $response, array $args): Response
    {
        if (!UserService::isConnected()) {
            return UserService::unAuthorized($response, $request, $this->view);
        }

        return $this->view->render($response, 'joke/jokes.php', [
            'title' => 'WhatTheDuck | Jokes',
            'jokes' => [],
        ]);
    }
}