<?php


namespace Randi\domain\base\service;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\config\Database;
use Randi\domain\user\entity\Token;
use Randi\domain\user\entity\User;
use Randi\modules\JwtHandler;

class BaseService
{
    protected $jwtHandler;
    protected $db;
    protected $log;
    protected $userDatas;


    public function __construct()
    {
        $this->db = new Database();
        $this->log = new Logger('BaseService.php');
        $this->jwtHandler = new JwtHandler();
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'] . '/randi.log', Logger::DEBUG));
    }

    protected function getToken(): ?Token
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $jwt = $headers['Authorization'];
            if (isset($jwt) && strlen($jwt) > 6) {
                $jwt = substr($jwt, 6);
                return $this->jwtHandler->parseJwt($jwt);
            }
        }
        return null;
    }


    protected function getUser(): ?User
    {
        $token = $this->getToken();
        $userId = $token->id;
        $anyad = "kurva anyád";
//        if (isset($token)) {
        $stmt = $this->db->prepare("select * from user where id=:id");
            $stmt->execute([
                "id" => $userId
            ]);
        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $userId;
//        }
//        return $userData2;
    }
}
