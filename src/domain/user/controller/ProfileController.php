<?php


namespace Randi\domain\user\controller;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\controller\BaseController;
use Randi\domain\user\entity\Profile;
use Randi\domain\user\service\ProfileService;
use Randi\modules\Mapper;
use Randi\modules\RequestHandler;


class ProfileController extends BaseController
{
    private $mapper;
    private $profileService;
    public function __construct()
    {
        parent::__construct();
        $this->profileService = new ProfileService();
        $this->log = new Logger('ProfileController.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'] . '/randi.log', Logger::DEBUG));
        $this->mapper = new Mapper();
    }

    /**
     * http://randi/profile/save
     */
    public function saveAction()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $profile = $this->mapper->classFromArray(RequestHandler::postParam('settings'), new Profile());
        $base64String = RequestHandler::postParam('base64') ?: '';
        $fileName = RequestHandler::postParam('fileName') ?: '';
        if (strlen($base64String) > 0 && strlen($fileName) > 0) {
            $fileResponse = $this->profileService->base64_to_jpeg($base64String, $fileName);
            if ($fileResponse !== 'error') {
                $profile->picturePath = $fileResponse;
            }
        }

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