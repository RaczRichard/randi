<?php


namespace Randi\domain\user\controller;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\controller\BaseController;
use Randi\domain\user\service\GameService;
use Randi\modules\RequestHandler;


class GameController extends BaseController
{
    private $gameService;

    public function __construct()
    {
        parent::__construct();
        $this->gameService = new GameService();
        $this->log = new Logger('AuthController.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'] . '/randi.log', Logger::DEBUG));
    }

    public function searchGameAction()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $gender = RequestHandler::postParam('gender' ?: '');
        $tol = RequestHandler::postParam('tol' ?: '');
        $ig = RequestHandler::postParam('ig' ?: '');
        $this->returnJson($this->gameService->searchGame($gender, $tol, $ig));

    }
}