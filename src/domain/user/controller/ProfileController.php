<?php


namespace Randi\domain\user\controller;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\controller\BaseController;
use Randi\domain\user\service\ProfileService;


class ProfileController extends BaseController
{
    private $ProfileService;
    public function __construct()
    {
        parent::__construct();
        $this->ProfileService = new ProfileService();
        $this->log = new Logger('ProfileController.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'] . '/randi.log', Logger::DEBUG));
    }

    /**
     * http://randi/profile/save
     */
    public function saveAction()
    {

    }

    /**
     * http://randi/profile/get
     */
    public function getAction()
    {
        if ($this->hasRole(["admin", "user"])) {
            $this->returnJson($this->ProfileService->listSetting());
        }
    }
}