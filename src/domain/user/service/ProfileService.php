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
    /**
     * @param null|integer $id
     * @return Profile
     */
    public function listSetting($id): Profile //PROFIL LISTÁZÁSA
    {

        /** @var User $user */
        $user = $this->getUser();
        $profileId = isset($id) ? $id : $user->profileId;
        $this->log->debug("listSettings" . $profileId . " " . json_encode($user));
        $stmt = $this->db->prepare("select * from profile WHERE id=:profileId");
        $stmt->execute(array(
            "profileId" => $profileId
        ));
        $profileData = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->log->debug(json_encode($profileData));
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
        $this->log->debug("Change setting id: " . $profile->id . " username: " . $profile->username . " address: " . $profile->address . " height: " . $profile->height . " physique: " . $profile->physique . " age: " . $profile->age);
        $this->log->debug("Change setting child: " . $profile->child . " job: " . $profile->job . " live: " . $profile->live . " looking: " . $profile->looking . " school: " . $profile->school . " gender: " . $profile->gender);
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
                                              gender=:gender,
                                              picturePath=:picturePath
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
            "gender" => $profile->gender,
            "picturePath" => $profile->picturePath
        ));
        return $profile;

    }

    /**
     * @param string $base64_string
     * @param string $output_file
     * @return string $path
     */
    public function base64_to_jpeg($base64_string, $output_file)
    {
        $data = explode(',', $base64_string);

        $ifp = fopen("images/" . $output_file, 'wb');
        fwrite($ifp, base64_decode($data[1]));
        fclose($ifp);

        return $output_file;
    }
}