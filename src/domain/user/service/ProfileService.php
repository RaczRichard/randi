<?php

namespace Randi\domain\user\service;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\service\BaseService;
use Randi\domain\user\entity\ProfileResponse;
use Randi\domain\user\entity\Token;
use Randi\domain\user\entity\User;
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
     * @return ProfileResponse[]
     */
    public function listSetting(): array //PROFIL LISTÁZÁSA
    {
        $user = new User();
        $userId = $user->id;
        $stmt = $this->db->prepare("select * from profile WHERE id=:1");
//        $stmt->execute();
        $ProfileData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $profiles = [];

        $mapper = new Mapper();
        foreach ($ProfileData as $profile) {
            $profiles[] = $mapper->classFromArray($profile, new ProfileResponse());
        }
        dd($user);
        return $profiles;
    }

}