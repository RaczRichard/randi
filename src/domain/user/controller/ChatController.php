<?php
/**
 * Created by PhpStorm.
 * User: Én
 * Date: 2020. 05. 17.
 * Time: 17:07
 */

namespace Randi\domain\user\controller;


use Randi\domain\base\controller\BaseController;
use Randi\domain\user\service\ChatService;

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

        if (!isset($pId2)) {
            $this->returnJson("A kurva anyád!");
        }

        $this->returnJson($this->chatService->getRoomId($pId2));
    }
}