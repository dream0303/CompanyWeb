<?php
//常量定義
define("DS",DIRECTORY_SEPARATOR); //動態目錄分割符
define("ROOT_PATH",getcwd().DS); //當前目錄(網站根目錄)
define("APP_PATH",ROOT_PATH."Home".DS); //Home目錄

//包含框架初始類
require_once(ROOT_PATH."Frame".DS."Frame.class.php");
//調用框架初始化方法
Frame\Frame::run();