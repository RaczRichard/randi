<?php

namespace Randi\domain\user\service;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\service\BaseService;
use Randi\domain\user\entity\ProfileRequest;
use Randi\domain\user\entity\Profile;
use Randi\domain\user\entity\Token;
use Randi\domain\user\entity\User;
use Randi\modules\JwtHandler;
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
     * @return Profile
     */
    public function listSetting(): Profile //PROFIL LISTÁZÁSA
    {

        /** @var User $user */
        $user = $this->getUser();
        $profileId = $user->profileId;
        $this->log->debug("listSettings" . $profileId . " " . json_encode($user));
        $stmt = $this->db->prepare("select * from profile WHERE id=:profileId");
        $stmt->execute(array(
            "profileId" => $profileId
        ));
        $profileData = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->log->debug("profileData " . json_encode($profileData));
        $mapper = new Mapper();
        /** @var Profile $profile */
        $profile = $mapper->classFromArray($profileData, new Profile());
        $this->log->debug("profile " . json_encode($profile));
        return $profile;
    }

    /**
     * @param Profile $profile
     * @return Profile
     */
    public function changeSetting(Profile $profile): Profile //PROFILE UPDATE
    {
        $stmt = $this->db->prepare("update profile set username =:username,address=:address,height=:height,weight=:weight,
                                              age=:age,child=:child,job=:job,live=:live,looking=:looking,school=:school where id=:id");
        $stmt->execute(array(
            "id" => $profile->id,
            "username" => $profile->username,
            "address" => $profile->address,
            "height" => $profile->height,
            "weight" => $profile->weight,
            "age" => $profile->age,
            "child" => $profile->child,
            "job" => $profile->job,
            "live" => $profile->live,
            "looking" => $profile->looking,
            "school" => $profile->school
        ));
        $this->log->debug("changeSettings Profile adatok ");

    }
}