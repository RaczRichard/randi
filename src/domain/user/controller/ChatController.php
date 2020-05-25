<?php


namespace Randi\domain\user\controller;


use Randi\domain\base\controller\BaseController;
use Randi\domain\user\entity\UserMessage;
use Randi\domain\user\service\ChatService;
use Randi\modules\RequestHandler;

class ChatController extends BaseController
{
    /**
     * @var ChatService $chatService
     */
    private $chatService;

    public function __construct()
    {
        parent::__construct();
        $this->chatService = new ChatService();
    }

    public function getRoomIdAction($pId2)
    {
        if ($this->hasRole(["admin", "user"])) {
            if (!isset($pId2)) {
                $this->returnJson("Nem okés!");
            }
            $this->returnJson($this->chatService->getRoomId($pId2));
        }
    }

    public function getRoomsAction()
    {
        if ($this->hasRole(["admin", "user"])) {
            $this->returnJson($this->chatService->getRooms());
        }
    }

    public function getMessagesAction($roomId)
    {
        if ($this->hasRole(["admin", "user"])) {
            $this->returnJson($this->chatService->getMassages($roomId));
        }
    }

    public function sendMessageAction()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $userMessage = new UserMessage();
        $userMessage->roomId = RequestHandler::postParam("roomId");
        $userMessage->msg = RequestHandler::postParam("message");
        if ($this->hasRole(["admin", "user"])) {
            $this->returnJson($this->chatService->sendMessage($userMessage));
        }
    }
}