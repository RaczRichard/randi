<?php

$routing = [
    "auth" => \Randi\domain\user\controller\AuthController::class,
    "user" => \Randi\domain\user\controller\UserController::class,
    "profile" => \Randi\domain\user\controller\ProfileController::class,
    "search" => \Randi\domain\user\controller\SearchController::class,
    "chat" => \Randi\domain\user\controller\ChatController::class,
    "verification" => \Randi\domain\user\controller\ChatController::class,
];
