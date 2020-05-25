<?php

namespace Randi\domain\user\service;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\service\BaseService;
use Randi\domain\user\entity\ChatMsg;
use Randi\domain\user\entity\Room;
use Randi\domain\user\entity\RoomResponse;
use Randi\domain\user\entity\UserMessage;
use Randi\modules\Mapper;

class ChatService extends BaseService
{

    private $jsonMapper;
    private $token;

    public function __construct()
    {
        parent::__construct();
        $this->jsonMapper = new Mapper();
        $this->token = $this->getToken();
        $this->log = new Logger('ChatService.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'] . '/randi.log', Logger::DEBUG));
    }

    public function getRoomId($pId2): int
    {
        $pId1 = $this->getUser()->profileId;

        $stmt = $this->db->prepare("select id from room where (pId1=:pId1 and pId2 =:pId2) or (pId2=:pId1 and pId1=:pId2)");

        $stmt->execute([
            "pId1" => $pId1,
            "pId2" => $pId2
        ]);

        $count = $stmt->rowCount();

        if ($count === 0) {

            $istmt = $this->db->prepare("insert into room (pId1, pId2) VALUES (:pId1,:pId2)");
            $istmt->execute([
                "pId1" => $pId1,
                "pId2" => $pId2
            ]);

            return $this->db->lastInsertId();
        }

        $roomData = $stmt->fetch(\PDO::FETCH_ASSOC);

        /**
         * @var Room $room
         */
        $room = $this->jsonMapper->classFromArray($roomData, new Room());
        return $room->id;

    }

    /**
     * @return RoomResponse[]
     */
    public function getRooms(): array
    {

        $profileId = $this->getUser()->profileId;
        $stmt = $this->db->prepare("select * from room where pId1=:profileId or pId2=:profileId");
        $stmt->execute([
            "profileId" => $profileId
        ]);

        $roomDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        /**
         * @var RoomResponse[] $roomResponses
         */
        $roomResponses = [];

        foreach ($roomDatas as $roomData) {
            $roomResponse = new RoomResponse();
            $roomResponse->roomId = $roomData['id'];
            $id = $roomData['pId1'] !== $profileId ? $roomData['pId1'] : $roomData['pId2'];
            $subStmt = $this->db->prepare("select username from profile where id=:id");
            $subStmt->execute([
                "id" => $id
            ]);
            $profileData = $subStmt->fetch(\PDO::FETCH_ASSOC);
            $roomResponse->username = $profileData['username'];
            $roomResponses[] = $roomResponse;
        }
        return $roomResponses;
    }

    /**
     * @param int $roomId
     * @return ChatMsg[]
     */
    public function getMassages($roomId): array
    {
        $stmt = $this->db->prepare("select * from chatmsg where roomId=:roomId order by time");
        $stmt->execute([
            "roomId" => $roomId
        ]);

        $messageDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        /**
         * @var ChatMsg[] $messages
         */
        $messages = [];
        foreach ($messageDatas as $messageData) {
            $messages[] = $this->jsonMapper->classFromArray($messageData, new ChatMsg());
        }
        return $messages;
    }

    /**
     * @param UserMessage $userMessage
     */
    public function sendMessage($userMessage)
    {

        $pId = $this->getUser()->profileId;

        $chatMessage = new ChatMsg();
        $chatMessage->msg = $userMessage->msg;
        $chatMessage->roomId = $userMessage->roomId;
        $chatMessage->pId = $pId;

        $stmt = $this->db->prepare("insert into chatmsg (pId,msg,roomId) values (:pId,:msg,:roomId)");
        $stmt->execute([
            "pId" => $chatMessage->pId,
            "roomId" => $chatMessage->roomId,
            "msg" => $chatMessage->msg
        ]);
    }
}