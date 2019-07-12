<?php
//聲明命名空間
namespace Admin\Model;
use \Frame\Libs\BaseModel;

//定義用戶模型類，並繼承基礎模型類
class UserModel extends BaseModel
{
	//受保護的數據表名稱
	protected $table = "user";
}