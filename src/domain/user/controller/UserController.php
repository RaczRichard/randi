<?php


namespace Randi\domain\user\controller;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\controller\BaseController;
use Randi\domain\user\service\UserService;

class UserController extends BaseController
{
    private $userService;
    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
        $this->log = new Logger('AuthController.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'].'/randi.log', Logger::DEBUG));
    }

    public function listAction(){
        if($this->hasRole(["admin","user"])){
            $this->returnJson($this->userService->listUsers());
        }
    }
}
