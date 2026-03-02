<?php

declare(strict_types=1);

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Models\Settings;
use Services\UserService;

class SettingsController extends BaseController
{
    public function getSettings(Request $request, Response $response, array $args): Response
    {
        if (!UserService::isConnected()) {
            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }

        $user = $_SESSION['user'];
        $settings = Settings::findByUserId($user->getIdUser());

        if (!$settings) {
            $settings = new Settings();
            $settings->idUser = $user->getIdUser();
        }

        $categories = [
            'category_programming' => 'Programming',
            'category_misc' => 'Misc',
            'category_dark' => 'Dark',
            'category_pun' => 'Pun',
            'category_spooky' => 'Spooky',
            'category_christmas' => 'Christmas',
        ];

        $blacklists = [
            'blacklist_nsfw' => 'NSFW',
            'blacklist_religious' => 'Religious',
            'blacklist_political' => 'Political',
            'blacklist_racist' => 'Racist',
            'blacklist_sexist' => 'Sexist',
            'blacklist_explicit' => 'Explicit',
        ];

        return $this->view->render($response, 'settings/show.php', [
            'title' => 'WhatTheDuck | Settings',
            'settings' => $settings,
            'categories' => $categories,
            'blacklists' => $blacklists
        ]);
    }

    public function updateSettings(Request $request, Response $response, array $args): Response
    {
        if (!UserService::isConnected()) {
            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }

        try {
            $user = $_SESSION['user'];
            $userId = $user->getIdUser();
            $data = (array) $request->getParsedBody();

            $settings = Settings::findByUserId($userId);

            if (!$settings) {
                $settings = new Settings();
                $settings->idUser = $userId;
            }

            $settings->category_any = isset($data['category_mode']) && $data['category_mode'] === 'any';
            $settings->category_programming = isset($data['category_programming']);
            $settings->category_misc = isset($data['category_misc']);
            $settings->category_dark = isset($data['category_dark']);
            $settings->category_pun = isset($data['category_pun']);
            $settings->category_spooky = isset($data['category_spooky']);
            $settings->category_christmas = isset($data['category_christmas']);

            $settings->language_code = $data['language_code'] ?? 'en';

            $settings->blacklist_nsfw       = isset($data['blacklist_nsfw']);
            $settings->blacklist_religious  = isset($data['blacklist_religious']);
            $settings->blacklist_political  = isset($data['blacklist_political']);
            $settings->blacklist_racist     = isset($data['blacklist_racist']);
            $settings->blacklist_sexist     = isset($data['blacklist_sexist']);
            $settings->blacklist_explicit   = isset($data['blacklist_explicit']);

            $safeModeChecked = isset($data['safe_mode']);

            if ($safeModeChecked) {
                $settings->blacklist_nsfw      = true;
                $settings->blacklist_religious = true;
                $settings->blacklist_political = true;
                $settings->blacklist_racist    = true;
                $settings->blacklist_sexist    = true;
                $settings->blacklist_explicit  = true;

                $settings->safe_mode = true;
            } else {
                $allBlacklisted =
                    $settings->blacklist_nsfw &&
                    $settings->blacklist_religious &&
                    $settings->blacklist_political &&
                    $settings->blacklist_racist &&
                    $settings->blacklist_sexist &&
                    $settings->blacklist_explicit;

                $settings->safe_mode = $allBlacklisted;
            }

            $settings->allow_single = isset($data['allow_single']);
            $settings->allow_two_part = isset($data['allow_two_part']);

            $settings->joke_amount = max(1, (int) ($data['joke_amount'] ?? 10));

            if ($settings->idSettings) {
                $settings->update();
            } else {
                $settings->insert();
            }

            $_SESSION['success'] = 'Settings updated successfully.';

            return $response
                ->withHeader('Location', '/settings')
                ->withStatus(302);
        } catch (\Exception $e) {
            $_SESSION['error'] = 'An error occurred while updating settings.' . $e->getMessage();
            return $response
                ->withHeader('Location', '/settings')
                ->withStatus(302);
        }
    }

    public function resetSettings(Request $request, Response $response, array $args): Response
    {
        if (!UserService::isConnected()) {
            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }

        try {
            $user = $_SESSION['user'];
            $settings = Settings::findByUserId($user->getIdUser());

            if ($settings) {
                $settings->category_any = true;
                $settings->category_programming = false;
                $settings->category_misc = false;
                $settings->category_dark = false;
                $settings->category_pun = false;
                $settings->category_spooky = false;
                $settings->category_christmas = false;

                $settings->language_code = 'en';

                $settings->blacklist_nsfw = false;
                $settings->blacklist_religious = false;
                $settings->blacklist_political = false;
                $settings->blacklist_racist = false;
                $settings->blacklist_sexist = false;
                $settings->blacklist_explicit = false;

                $settings->safe_mode = true;

                $settings->allow_single = true;
                $settings->allow_two_part = true;

                $settings->joke_amount = 10;

                $settings->update();
            }

            $_SESSION['success'] = 'Settings reset successfully.';

            return $response
                ->withHeader('Location', '/settings')
                ->withStatus(302);
        } catch (\Exception $e) {
            $_SESSION['error'] = 'An error occurred while resetting settings.';
            return $response
                ->withHeader('Location', '/settings')
                ->withStatus(302);
        }
    }
}
