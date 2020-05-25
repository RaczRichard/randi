<?php


namespace Randi\domain\user\controller;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\controller\BaseController;
use Randi\domain\user\entity\LoginRequest;
use Randi\domain\user\entity\RegisterRequest;
use Randi\domain\user\entity\User;
use Randi\domain\user\service\validator\Validator;
use Randi\modules\RequestHandler;

class AuthController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->log = new Logger('AuthController.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'] . '/randi.log', Logger::DEBUG));
    }

    /**
     * http://randi/login/login
     */
    public function loginAction()
    {
        $_POST = json_decode(file_get_contents('php://input'), true); // postnál elengedhetettlen
        $email = RequestHandler::postParam('email') ?: '';
        $password = RequestHandler::postParam('password') ?: '';

        $request = new LoginRequest();
        $request->setEmail($email);
        $request->setPassword($password);

        if (Validator::validateLogin($request)) {
            $response = $this->authService->login($request);
            if ($response) {
                $this->returnJson($response);
            } else {
                http_response_code(401);
                $error["message"] = "login failed!";
                $this->returnJson($error);
            }

        } else {
            echo "login szar!";
        }
    }

    /**
     * http://randi/registration/save
     */
    public function registerAction()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $email = RequestHandler::postParam('email') ?: '';
        $password = RequestHandler::postParam('password') ?: '';
        $passwordAgain = RequestHandler::postParam('passwordAgain') ?: '';

        $request = new RegisterRequest();
        $request->setEmail($email);
        $request->setPassword($password);

        if (Validator::validateRegister($request)) {
            $this->authService->registerUser($request, $passwordAgain);
        } else {
            echo "register szar!";
        }

    }

    /**
     * http://randi/auth/verification
     * @param string $uuid
     */
    public function verificationAction($uuid)
    {
        $this->log->debug("uuid: " . $uuid);
        $this->returnJson($this->authService->verification($uuid));
    }

    /**
     * http://randi/auth/reset
     */
    public function resetAction()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $email = new User();
        $email->email = RequestHandler::postParam('email') ?: '';
        $this->returnJson($this->authService->resetPass($email));
    }

    /**
     * http://randi/auth/passwordChange
     */
    public function passwordChangeAction()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $old = RequestHandler::postParam('old') ?: '';
        $newPass = RequestHandler::postParam('newPass') ?: '';
        $again = RequestHandler::postParam('again') ?: '';

        $this->returnJson($this->authService->changePassword($old, $newPass, $again));
    }

    public function emailChangeAction()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $email = RequestHandler::postParam('email') ?: '';
        $this->returnJson($this->authService->changeEmail($email));
    }
}
