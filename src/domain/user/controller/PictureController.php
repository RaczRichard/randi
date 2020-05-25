<?php
/**
 * Created by PhpStorm.
 * User: Én
 * Date: 2020. 05. 24.
 * Time: 15:58
 */

namespace Randi\domain\user\controller;


use Randi\domain\base\controller\BaseController;

class PictureController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAction($name)
    {

        $filePath = "images/" . $name;
        $contents = file_get_contents($filePath);
        $mime = mime_content_type($filePath);
        $base64 = base64_encode($contents);
        $this->returnJson(('data:' . $mime . ';base64,' . $base64));
    }
}