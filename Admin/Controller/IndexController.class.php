<?php
namespace Admin\Controller;
use \Frame\Libs\BaseController;

final class IndexController extends BaseController
{
//首頁
public function index()
{
$this->denyAccess();
include VIEW_PATH."index.html";
}

}