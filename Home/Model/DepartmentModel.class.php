<?php
namespace Home\Model;
use Frame\Libs\BaseModel;

final class DepartmentModel extends BaseModel
{
	//獲取多行數據
	public function fetchAll()
	{
		$year=(!empty($_GET['year']))?$_GET['year']:90;

		$empSQL="SELECT 
		`dept`.`部門名稱`,
		  `employee`.`姓名`
	  FROM 
		  `dept`,
		  `employee` 
	  WHERE 
		`dept`.`部門代號`=`employee`.`部門代號` && 
		`dept`.id BETWEEN 6 AND 9
	  ORDER BY
		CONVERT(`dept`.`部門名稱` using big5) ASC,
		CONVERT(`employee`.`姓名` using big5) ASC";
	  
	  
	  
	  $saleSQL="SELECT 
			`sales2`.`業務姓名`,
		  `customer`.`客戶寶號`,
		  `customer`.`聯絡人`,
		  sum(`sales2`.`數量`*`product`.`單價`) AS `總額` 
	  FROM 
		  `customer`,
		  `sales2`,
		  `product`
	  WHERE 
		  `customer`.`客戶代號`=`sales2`.`客戶代號` && 
		  `sales2`.`產品代號`=`product`.`產品代號` && 
		  `sales2`.`交易年`='$year'
	  GROUP BY
		  `customer`.`客戶代號`";
		  $emps = $this->pdo->fetchAll($empSQL);
		  $sale = $this->pdo->fetchAll($saleSQL);
		  $name=[];
		  
		  foreach($sale as $s){
			$name[$s['業務姓名']][]=$s;
		  }
		  foreach($emps as $k => $e){
			if(!empty($name[$e['姓名']])){
			   $emps[$k]['客戶']=$name[$e['姓名']];
			}else{
			  unset($emps[$k]);
			}
		  
		  }
		  $dept=[];
		  foreach($emps as $k => $e){
			$dept[$e['部門名稱']][]=$e;
		  }


		return $dept;
	}

}