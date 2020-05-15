<?php

namespace Randi\domain\user\service;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\service\BaseService;
use Randi\domain\user\entity\ProfileResponse;
use Randi\modules\Mapper;


class ProfileService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
        $this->log = new Logger('ProfileService.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'] . '/randi.log', Logger::DEBUG));
    }


    /**
     * @return ProfileResponse
     */
    public function listSetting(): ProfileResponse //PROFIL LISTÁZÁSA
    {
        $getUser = $this->getUser();
        $getId = $getUser->id;
        dd(json_decode($getId));
        $token = $this->getToken();
        $userId = $token->id;
        $stmt = $this->db->prepare("select * from profile WHERE id=:id");
        $stmt->execute(array(
            "id" => $userId
        ));
        $ProfileData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $profiles = [];


        $mapper = new Mapper();
        foreach ($ProfileData as $profile) {
            $profiles = $mapper->classFromArray($profile, new ProfileResponse());
        }
        return $profiles;


    }

}