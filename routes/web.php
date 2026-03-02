<?php

use Controllers\HomeController;
use Controllers\LoginController;
use Controllers\SettingsController;
use Controllers\JokeController;

$app->get('/', [HomeController::class, 'index']);

$app->get('/profile', [HomeController::class, 'profile']);
$app->post('/profile/update-pfp', [HomeController::class, 'updateProfilePicture']);
$app->post('/profile/delete', [HomeController::class, 'deleteAccount']);

$app->get('/login', [LoginController::class, 'showLogin']);
$app->post('/login', [LoginController::class, 'login']);

$app->get('/register', [LoginController::class, 'showRegister']);
$app->post('/register', [LoginController::class, 'register']);

$app->get('/logout', [LoginController::class, 'logout']);

$app->get('/settings', [SettingsController::class, 'getSettings']);
$app->post('/settings/update', [SettingsController::class, 'updateSettings']);
$app->post('/settings/reset', [SettingsController::class, 'resetSettings']);

$app->get('/jokes', [JokeController::class, 'showJokes']);
$app->post('/jokes/new', [JokeController::class, 'showJokes']);
$app->post('/jokes/clear', [JokeController::class, 'clearJokes']);

// Secret route
$app->get('/secret', [HomeController::class, 'secret']);
