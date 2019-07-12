<?php
namespace Home\Controller;
use \Frame\Libs\BaseController;

final class IndexController extends BaseController
{
//首頁
    public function index()
    {
        include VIEW_PATH."index.html";
    }

}