<?php
namespace Frame\Libs;
//定義增、刪、改、查，抽象基礎模型類
abstract class BaseModel
{
	protected $pdo = null;
	//初始
	public function __construct()
	{
		//創建PDOWrapper()
		$this->pdo = new \Frame\Vendor\PDOWrapper();
	}

	//創建不同模型類對象的方法
	public static function getInstance()
	{
		//獲得包含namespace的類名
		$modelClassName = get_called_class();
		return new $modelClassName();
	}

	//獲取一行數據(當沒有傳入變數則為默認值1，一樣會顯示全部)
	public function fetchOne($where="1")
	{
		$sql = "SELECT * FROM {$this->table} WHERE {$where}";
		return $this->pdo->fetchOne($sql);
	}

	//獲取多行數據
	public function fetchAll()
	{
		$sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
		return $this->pdo->fetchAll($sql);
	}

	//刪除紀錄
	public function delete($id)
	{
		$sql = "DELETE FROM {$this->table} WHERE id={$id}";
		return $this->pdo->exec($sql);
	}

	//增加紀錄
	public function insert($data)
	{
		//构建"字段列表"和"值列表"字符串
		$fields = '';
		$values = '';
		foreach($data as $key=>$value)
		{
			$fields .= "$key,";
			$values .= "'$value',";
		}
		//去除結尾的逗號
		$fields = rtrim($fields,',');
		$values = rtrim($values,',');
		//INSERT INTO news(title,content,hits) VALUES('標題','內容','30')
		$sql = "INSERT INTO {$this->table}($fields) VALUES($values)";
		//回傳受影響行數
		return $this->pdo->exec($sql);
	}

	//更新紀錄
	public function update($data,$id)
	{

		$str = "";
		foreach($data as $key=>$value)
		{
			$str .= "{$key}='{$value}',";
		}
		//去除結尾的逗號
		$str = rtrim($str,',');
		//UPDATE news SET title='標題',content='內容' WHERE id=5
		$sql = "UPDATE {$this->table} SET {$str} WHERE id={$id}";
		//回傳受影響行數
		return $this->pdo->exec($sql);
	}

	//獲取紀錄數(當沒有傳入變數則為默認值1，一樣會顯示全部)
	public function rowCount($where="1")
	{
		$sql = "SELECT * FROM {$this->table} WHERE {$where}";
		return $this->pdo->rowCount($sql);
	}
}