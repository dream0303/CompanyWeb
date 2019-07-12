<?php
namespace Home\Model;
use Frame\Libs\BaseModel;

final class SeasonModel extends BaseModel
{
	//獲取多行數據
	public function fetchAll()
	{
		$year=(!empty($_GET['year']))?$_GET['year']:90;
		$sql="SELECT
		`product`.`產品名稱`,
		FLOOR((`sales2`.`交易月`-1)/3)+1 AS '季',
		sum(`sales2`.`數量`) AS '數量',
		SUM(`sales2`.`數量`*`product`.`單價`) AS '銷售額'
	  FROM
		`sales2`,
		`product`
	  WHERE 
		`sales2`.`產品代號`=`product`.`產品代號` && 
		`sales2`.`交易年`='$year'
	  GROUP BY
		`sales2`.`產品代號`,
		FLOOR((`sales2`.`交易月`-1)/3)+1
	  ORDER BY
		CONVERT(`product`.`產品名稱` using big5) ASC , 
		FLOOR((`sales2`.`交易月`-1)/3)+1
		";
		$sales = $this->pdo->fetchAll($sql);

		$pro=[];
		$sumAll=0;
		foreach($sales as $key => $s){
			if(!empty($pro[$s['產品名稱']]["銷售額"])){
				$pro[$s['產品名稱']]["銷售額"]+=$s['銷售額'];
			}else{
				$pro[$s['產品名稱']]["銷售額"]=$s['銷售額'];
			}
			$pro[$s['產品名稱']][$s['季']]=$s['數量'];
			$sumAll+=$s['銷售額'];
		}
	
		foreach($pro as $key => $p){
			$sumQ=0;
			$p['銷售百分比']=round($p['銷售額']/$sumAll,4)*100 . "%";
			for($i=1;$i<=4;$i++){
				if(!empty($p[$i])){
					$sumQ+=$p[$i];
				}else{
					$p[$i]=0;
				}
			}
			$p['平均數量']= round($sumQ/4,2);
			$pro[$key]=$p;
		}
		return $pro;
	}
	
}