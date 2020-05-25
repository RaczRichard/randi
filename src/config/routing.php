<?php

$routing = [
    "auth" => \Randi\domain\user\controller\AuthController::class,
    "user" => \Randi\domain\user\controller\UserController::class,
    "profile" => \Randi\domain\user\controller\ProfileController::class,
    "search" => \Randi\domain\user\controller\SearchController::class,
    "chat" => \Randi\domain\user\controller\ChatController::class,
    "verification" => \Randi\domain\user\controller\AuthController::class,
    "password" => \Randi\domain\user\controller\AuthController::class,
    "game" => \Randi\domain\user\controller\GameController::class,
    "picture" => \Randi\domain\user\controller\PictureController::class,
];
