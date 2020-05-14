<?php


namespace Randi\domain\base\service;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\config\Database;
use Randi\domain\user\entity\Token;
use Randi\domain\user\entity\User;

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

    protected function getUser(): User
    {
        $token = $this->getToken();
        echo "<script>console.log('token " . $token . "' );</script>";
        if (isset($token)) {
            $stmt = $this->db->prepare("select * from profile where id=:tokenId");
            $stmt->execute([
                "tokenId" => $token->id
            ]);

            $userData = $stmt->fetch(\PDO::FETCH_COLUMN);
            return $userData;
        }
        return null;
    }
}
