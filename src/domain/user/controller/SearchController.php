<?php
/**
 * Created by PhpStorm.
 * User: Én
 * Date: 2020. 05. 17.
 * Time: 15:56
 */

namespace Randi\domain\user\controller;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Randi\domain\base\controller\BaseController;
use Randi\domain\user\service\SearchService;
use Randi\modules\RequestHandler;

class SearchController extends BaseController
{

    /**
     * @var SearchService $searchService
     */
    private $searchService;

    public function __construct()
    {
        parent::__construct();
        $this->log = new Logger('SearchController.php');
        $this->log->pushHandler(new StreamHandler($GLOBALS['rootDir'] . '/randi.log', Logger::DEBUG));
        $this->searchService = new SearchService();
    }

    public function getAction()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $name = RequestHandler::postParam("name");
        /**
         * csináld meg rendesen több paraméterrel!
         */
        if ($this->hasRole(["admin", "user"])) {
            $this->returnJson($this->searchService->search($name));
        }
    }
}