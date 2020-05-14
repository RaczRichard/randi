<?php


namespace Randi\domain\user\controller;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\controller\BaseController;
use Randi\domain\user\entity\LoginRequest;
use Randi\domain\user\entity\ProfileRequest;
use Randi\domain\user\entity\RegisterRequest;
use Randi\domain\user\service\validator\Validator;
use Randi\modules\RequestHandler;

class AuthController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->log = new Logger('AuthController.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'].'/randi.log', Logger::DEBUG));
    }

    public function loginAction()
    {
        $_POST = json_decode(file_get_contents('php://input'), true); // postnál elengedhetettlen
        $email = RequestHandler::postParam('email') ?: '';
        $password = RequestHandler::postParam('password') ?: '';

        $request = new LoginRequest();
        $request->setEmail($email);
        $request->setPassword($password);

        if(Validator::validateLogin($request)){
            $response = $this->authService->login($request);
            if($response){
                $this->returnJson($response);
            }else{
                http_response_code(401);
                $error["message"] = "login failed!";
                $this->returnJson($error);
            }

        }else{
            echo "login szar!";
        }
    }

    public function registerAction()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $email = RequestHandler::postParam('email') ?: '';
        $password = RequestHandler::postParam('password') ?: '';

        $request = new RegisterRequest();

        $request->setEmail($email);
        $request->setPassword($password);

        if(Validator::validateRegister($request)){
            $this->authService->registerUser($request);
        }else{
            echo "register szar!";
        }
    }
}
