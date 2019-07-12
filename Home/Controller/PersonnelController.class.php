<?php
//聲明命名空間：空間名要與所在目錄路徑一致
namespace Home\Controller;
use \Frame\Libs\BaseController;
use \Home\Model\PersonnelModel;

//定義首頁控制器類，並繼承基礎控制器類
final class PersonnelController extends BaseController
{
    public function index()
    {
        //創建模型類對象
        $modelObj = PersonnelModel::getInstance();
        //獲取多行數據
        $arrs     = $modelObj->fetchAll();
        //視圖文件
        include VIEW_PATH . "index.html";
    }
}