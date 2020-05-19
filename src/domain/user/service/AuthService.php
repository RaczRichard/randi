<?php


namespace Randi\domain\user\service;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\service\BaseService;
use Randi\domain\user\entity\LoginRequest;
use Randi\domain\user\entity\LoginResponse;
use Randi\domain\user\entity\RegisterRequest;
use Randi\domain\user\entity\Token;
use Randi\domain\user\entity\User;
use Randi\modules\JwtHandler;
use Randi\modules\Mapper;


class AuthService extends BaseService
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


    public function getRole()
    {
        if (isset($this->token)) {
            $stmt = $this->db->prepare("select role.code from user inner join role on user.roleId = role.id where user.id=:id");
            $stmt->execute([
                "id" => $this->token->id
            ]);
            $role = $stmt->fetch(\PDO::FETCH_COLUMN);
            return $role;
        }
        return null;
    }


    public function registerUser(RegisterRequest $request)
    {

        $this->log->debug(json_encode($request));
        //Profile fillelése
        $stmt = $this->db->prepare("insert into profile (status) values (:status)");
        $stmt->execute([
            'status' => 1
        ]);

        $lastInsertId = $this->db->lastInsertId();
        //user feltöltése
        $stmt = $this->db->prepare("insert into user 
                                              (email, password, profileId, status) 
                                              values 
                                              (:email, :password, :profileId,:status)");
        $success = $stmt->execute([
            "email" => $request->getEmail(),
            "password" => $request->getPassword(),
            "profileId" => $lastInsertId,
            "status" => 1
        ]);
        //verifications feltöltése
        $uuid = mt_rand(0, 0xffff);
        $stmt = $this->db->prepare("insert into verification (userId, uuid) values (:userId, :uuid)");
        $stmt->execute([
            "userId" => $lastInsertId,
            "uuid" => $uuid,
        ]);

        if (!$success) {
            $this->log->error("couldn't register user");
        }
        return $uuid;
    }

    /**
     * @param LoginRequest $request
     * @return LoginResponse
     */
    public function login(LoginRequest $request): ?LoginResponse
    {
        $stmt = $this->db->prepare("select * from user where email=:email and password=:password");

        $stmt->execute([
            "email" => $request->getEmail(),
            "password" => $request->getPassword(),
        ]);

        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (isset($userData) && $userData) {
            $this->log->debug("userData debug" . json_encode($userData));
        } else {
            $this->log->error("invalid username and password!");
        }

        if (!$userData) {
            return null;
        }

        $mapper = new Mapper();
        /** @var User $user */
        $user = $mapper->classFromArray($userData, new User());
        $token = new Token();
        $token->email = $user->email;
        $token->id = $user->id;
        $token->exp = time() + (3600);
        $jwt = $this->jwtHandler->generateJwt($token);
        $response = new LoginResponse();
        $response->id = $user->id;
        $response->email = $user->email;
        $response->token = $jwt;
        return $response;
    }


    public function verification()
    {

    }
}
