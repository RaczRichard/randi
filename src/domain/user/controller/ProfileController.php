<?php


namespace Randi\domain\user\controller;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\controller\BaseController;
use Randi\domain\user\entity\Profile;
use Randi\domain\user\service\ProfileService;
use Randi\modules\RequestHandler;


class ProfileController extends BaseController
{
    private $profileService;
    public function __construct()
    {
        parent::__construct();
        $this->profileService = new ProfileService();
        $this->log = new Logger('ProfileController.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'] . '/randi.log', Logger::DEBUG));
    }

    /**
     * http://randi/profile/save
     */
    public function saveAction()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $profile = new Profile();
        $profile->id = RequestHandler::postParam('id') ?: '';
        $profile->username = RequestHandler::postParam('username') ?: '';
        $profile->address = RequestHandler::postParam('address') ?: '';
        $profile->height = RequestHandler::postParam('height') ?: '';
        $profile->physique = RequestHandler::postParam('physique') ?: '';
        $profile->gender = RequestHandler::postParam('gender') ?: '';
        $profile->age = RequestHandler::postParam('age') ?: '';
        $profile->child = RequestHandler::postParam('child') ?: '';
        $profile->job = RequestHandler::postParam('job') ?: '';
        $profile->live = RequestHandler::postParam('live') ?: '';
        $profile->looking = RequestHandler::postParam('looking') ?: '';
        $profile->school = RequestHandler::postParam('school') ?: '';
        $profile->status = RequestHandler::postParam('status') ?: '';
        if ($this->hasRole(["admin", "user"])) {
            $this->returnJson($this->profileService->changeSetting($profile));
        }
    }

    /**
     * http://randi/profile/get
     * @param null|integer $id
     */
    public function getAction($id = null)
    {
        if ($this->hasRole(["admin", "user"])) {
            $this->returnJson($this->profileService->listSetting($id));
        }
    }

}