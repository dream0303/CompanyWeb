<?php
namespace Home\Model;
use Frame\Libs\BaseModel;

final class PersonnelModel extends BaseModel
{
	//獲取多行數據
	public function fetchAll()
	{
		$year=(!empty($_GET['year']))?$_GET['year']:90;

		$sql="SELECT 
		  `sales2`.`業務姓名`,
		  `customer`.`客戶寶號`,
		  `sales2`.`數量`,
		  `product`.`單價`,
		  (`sales2`.`數量`*`product`.`單價`) AS `總額` 
	  FROM 
		  `customer`,
		  `sales2`,
		  `product` 
	  WHERE 
		  `customer`.`客戶代號`=`sales2`.`客戶代號` && 
		  `sales2`.`產品代號`=`product`.`產品代號` && 
		  (`sales2`.`數量`*`product`.`單價`)>=10000000 &&
		  `sales2`.`交易年`='$year'
	  
	  ORDER BY
		  `總額` DESC,
		  `product`.`單價` DESC,
		  `sales2`.`數量` DESC,
		  CONVERT(`sales2`.`業務姓名` using big5) ASC";

		return $this->pdo->fetchAll($sql);
	}

}