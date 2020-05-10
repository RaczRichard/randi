<?php


namespace Randi\domain\base\service;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\config\Database;

class BaseService
{
    protected $db;
    protected $log;

    public function __construct()
    {
        $this->db = new Database();
        $this->log = new Logger('BaseService.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'].'/randi.log', Logger::DEBUG));
    }
}
