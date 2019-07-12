<?php
namespace Frame\Libs;
abstract class BaseController
{
	//用戶登陸判斷
	protected function denyAccess()
	{
		//判斷用戶是否登陸
		if(empty($_SESSION['username']))
		{
			$this->jump("請先登入！","?c=User&a=login");
		}		
	}

	//提示頁面，然後跳轉
	protected function jump($message,$url='?',$time=3)
	{
		include ROOT_PATH.DS."Frame".DS."View".DS."page500.html";
		header("refresh:{$time};url={$url}");
		die();
	}
}