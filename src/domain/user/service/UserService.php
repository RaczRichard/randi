<?php


namespace Randi\domain\user\service;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\service\BaseService;
use Randi\domain\user\entity\User;
use Randi\modules\Mapper;

class UserService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->log = new Logger('UserService.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'] . '/randi.log', Logger::DEBUG));
    }

    /**
     * @return User[]
     */
    public function listUsers(): array //USEREK LISTÁZÁSA
    {
        $stmt = $this->db->prepare("select * from user");
        $stmt->execute();
        $usersData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $users = [];

        $mapper = new Mapper();
        foreach ($usersData as $user) {
            $users[] = $mapper->classFromArray($user, new User());
        }

        return $users;
    }

}
