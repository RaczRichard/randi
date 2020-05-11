<?php


namespace Randi\domain\user\controller;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\controller\BaseController;

class ProfileController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->log = new Logger('ProfileController.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'] . '/randi.log', Logger::DEBUG));
    }

    public function settingAction()
    {
        die("mukszik");
    }
}