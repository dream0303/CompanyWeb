<?php
//聲明命名空間
namespace Frame;

//定義最終的框架初始類
final class Frame
{
    //公共的靜態的框架初始化方法
    public static function run()
    {
        self::initConfig(); //初始化配置數據
        self::initRoute(); //初始化路由參數
        self::initConst(); //初始化常量定義
        self::initAutoLoad(); //初始化類的自動加載
        self::initDispatch(); //初始化請求分發
    }
    //私有的靜態的初始化配置信息
    private static function initConfig()
    {
        //開啟SESSION會話
        session_start();
        
        $GLOBALS['config'] = require_once(APP_PATH . "Conf" . DS . "Config.php");
    }
    
    //私有的靜態的初始化路由參數
    private static function initRoute()
    {
        //獲取路由參數
        $p = $GLOBALS['config']['default_platform']; //平台參數
        $c = isset($_GET['c']) ? $_GET['c'] : $GLOBALS['config']['default_controller']; //控制器名
        $a = isset($_GET['a']) ? $_GET['a'] : $GLOBALS['config']['default_action']; //動作名
        define("PLAT", $p);
        define("CONTROLLER", $c);
        define("ACTION", $a);
    }
    //私有的靜態的初始化目錄常量
    private static function initConst()
    {
        define("VIEW_PATH", APP_PATH . "View" . DS . CONTROLLER . DS); //View目錄
    }
    
    //私有的靜態的初始化類的自動加載
    private static function initAutoLoad()
    {
        //自動require_once類別
        spl_autoload_register(function($className)
        {
            //傳遞過來類名參數：Home\Controller\StudentController
            //類文件的真實路徑：./Home/Controller/StudentController.class.php
            //將傳遞的類名轉成真實類文件路徑
            $filename = ROOT_PATH . str_replace("\\", DS, $className) . ".class.php";
            //如果文件存在，執行require_once
            if (file_exists($filename))
                require_once($filename);
        });
    }
    
    //私有的靜態的初始化請求分發：創建哪個控制器類的對象？調用控制器對象的哪個方法？
    private static function initDispatch()
    {
        //獲得類別名稱：Home\Controller\StudentController
        $controllerClassName = PLAT . "\\" . "Controller" . "\\" . CONTROLLER . "Controller";
        $controllerObj = new $controllerClassName();
        
        //根據?a(ACTION)調用方法
        $action_name = ACTION;
        $controllerObj->$action_name();
    }
}