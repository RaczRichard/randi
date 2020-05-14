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
     * @return ProfileResponse[]
     */
    public function listSetting(): array //PROFIL LISTÁZÁSA
    {
        $stmt = $this->db->prepare("select * from profile");
        $stmt->execute();
        $ProfileData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $profile = [];

        $mapper = new Mapper();
        foreach ($ProfileData as $profile) {
            $profile[] = $mapper->classFromArray($profile, new ProfileResponse());
        }

        return $profile;
    }

}