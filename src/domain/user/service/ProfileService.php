<?php

namespace Randi\domain\user\service;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\service\BaseService;
use Randi\domain\user\entity\Profile;
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
    public function changeSetting($profile): Profile //PROFILE UPDATE
    {
        $this->log->debug("change setting " . json_encode($profile));
        $stmt = $this->db->prepare("update profile set 
                                              username=:username,
                                              address=:address,
                                              height=:height,
                                              physique=:physique,
                                              age=:age,
                                              child=:child,
                                              job=:job,
                                              live=:live,
                                              looking=:looking,
                                              school=:school,
                                              gender=:gender 
                                              where id=:id");
        $stmt->execute(array(
            "id" => $profile->id,
            "username" => $profile->username,
            "address" => $profile->address,
            "height" => $profile->height,
            "physique" => $profile->physique,
            "age" => $profile->age,
            "child" => $profile->child,
            "job" => $profile->job,
            "live" => $profile->live,
            "looking" => $profile->looking,
            "school" => $profile->school,
            "gender" => $profile->gender
        ));
        return $profile;

    }
}