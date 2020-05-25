<?php


namespace Randi\domain\user\service;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PDO;
use Randi\domain\base\service\BaseService;
use Randi\domain\user\entity\Likee;
use Randi\domain\user\entity\Profile;
use Randi\modules\JwtHandler;
use Randi\modules\Mapper;


class GameService extends BaseService
{
    protected $jwtHandler;
    private $jsonMapper;
    private $token;

    public function __construct()
    {
        parent::__construct();
        $this->jwtHandler = new JwtHandler();
        $this->jsonMapper = new Mapper();
        $this->token = $this->getToken();
        $this->log = new Logger('AuthService.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'] . '/randi.log', Logger::DEBUG));
    }


    /**
     * @param $gender
     * @param $tol
     * @param $ig
     * @return Profile[] $profiles
     */
    public function searchGame($gender, $tol, $ig)
    {

        $sql = sprintf('select * from profile where status=1 %s %s',
            !empty($gender) ? 'AND gender=:gender' : null,
            !empty($tol) && !empty($ig) ? 'AND age between :tol and :ig' : null
        );

        $stmt = $this->db->prepare($sql);

        if (!empty($gender)) {
            $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        }

        if (!empty($tol) && !empty($ig)) {
            $stmt->bindParam(':tol', $tol, PDO::PARAM_STR);
            $stmt->bindParam(':ig', $ig, PDO::PARAM_STR);
        }

        $stmt->execute();
        $profilesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /** @var Profile[] $profiles */
        $profiles = [];

        foreach ($profilesData as $profileData) {
            $profiles[] = $this->jsonMapper->classFromArray($profileData, new Profile());
        }

        return $this->onlyUnselected($profiles);

    }

    /**
     * @param Profile[] $profiles
     * @return Profile[]
     */
    private function onlyUnselected($profiles)
    {
        $profileId = $this->getUser()->id;
//        $this->log->debug('token értéke: ',json_encode($profileId));
        $stmt = $this->db->prepare("select * from likee where liker=:liker");
        $stmt->execute([
            "liker" => $profileId,
        ]);
        $likeDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        /** @var Likee[] $likes */
        $likes = [];
        foreach ($likeDatas as $likeData) {
            $likes[] = $this->jsonMapper->classFromArray($likeData, new Likee());
        }

        foreach ($likes as $like) {
            foreach ($profiles as $key => $profile) {
                if ($like->liked === $profile->id) {
                    unset($profiles[$key]);
                }
            }
        }
        return $profiles;
    }

}