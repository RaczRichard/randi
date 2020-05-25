<?php


namespace Randi\domain\user\service;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\service\BaseService;
use Randi\domain\user\entity\Profile;
use Randi\modules\Mapper;

class SearchService extends BaseService
{

    /**
     * @var Mapper $jsonMapper
     */
    private $jsonMapper;

    public function __construct()
    {
        parent::__construct();
        $this->jsonMapper = new Mapper();
        $this->log = new Logger('SearchService.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'] . '/randi.log', Logger::DEBUG));
    }

    /**
     * @param string $name
     * @return Profile[]
     */
    public function search($name): array
    {

        $stmt = $this->db->prepare("SELECT * FROM profile WHERE username like :username");
        $stmt->execute([
            "username" => "%" . $name . "%"
        ]);

        $this->log->debug("Search query string : " . $stmt->queryString);

        $profilesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        /**
         * @var Profile[] $profiles
         */
        $profiles = [];

        foreach ($profilesData as $profileData) {
            $profiles[] = $this->jsonMapper->classFromArray($profileData, new Profile());
        }

        return $profiles;
    }
}