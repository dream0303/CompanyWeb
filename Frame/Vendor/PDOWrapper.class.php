<?php
namespace Frame\Vendor;
use \PDO;
use \PDOException;

//封裝PDO最終類(限制不能被繼承或覆寫)
final class PDOWrapper
{
    //資料庫配置訊息
    private $db_type;
    private $db_host;
    private $db_port;
    private $db_user;
    private $db_pass;
    private $db_name;
    private $charset;
    private $pdo = null;
    
    //初始化，讀取config檔案內的資料
    public function __construct()
    {
        $this->db_type = $GLOBALS['config']['db_type'];
        $this->db_host = $GLOBALS['config']['db_host'];
        $this->db_port = $GLOBALS['config']['db_port'];
        $this->db_user = $GLOBALS['config']['db_user'];
        $this->db_pass = $GLOBALS['config']['db_pass'];
        $this->db_name = $GLOBALS['config']['db_name'];
        $this->charset = $GLOBALS['config']['charset'];
        $this->createPDO(); //創建PDO對象
        $this->setErrMode(); //設置報錯模式
    }
    
    //私有的創建PDO對象的方法
    private function createPDO()
    {
        try {
            //構建DSN字符串
            $dsn = "{$this->db_type}:host={$this->db_host};port={$this->db_port};";
            $dsn .= "dbname={$this->db_name};charset={$this->charset}";
            
            //創建PDO類的對象
            $this->pdo = new PDO($dsn, $this->db_user, $this->db_pass);
        }
        catch (PDOException $e) {
            echo "<h2>創建PDO異常！</h2>";
            echo "錯誤編號：" . $e->getCode();
            echo "<br>錯誤行數：" . $e->getLine();
            echo "<br>錯誤檔案：" . $e->getFile();
            echo "<br>錯誤訊息：" . $e->getMessage();
            die();
        }
    }
    
    //私有的設置PDO報錯模式
    private function setErrMode()
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    //執行SQL語句：insert、update、delete、set等
    public function exec($sql)
    {
        try {
            return $this->pdo->exec($sql);
        }
        catch (PDOException $e) {
            $this->showErrMsg($e);
        }
    }
    
    //獲取單行數據：SELECT * FROM student
    public function fetchOne($sql)
    {
        try {
            //執行SQL語句，並返回結果集對象PDOStatement
            $PDOStatement = $this->pdo->query($sql);
            //返回一條記錄
            return $PDOStatement->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            $this->showErrMsg($e);
        }
    }
    
    //獲取多行數據：SELECT * FROM
    public function fetchAll($sql)
    {
        try {
            //執行SQL語句，並返回結果PDOStatement
            $PDOStatement = $this->pdo->query($sql);
            //返回多條記錄
            return $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            $this->showErrMsg($e);
        }
    }
    
    //獲取總筆數
    public function rowCount($sql)
    {
        try {
            //執行SQL語句，並返回結果集對象PDOStatement
            $PDOStatement = $this->pdo->query($sql);
            //返回記錄數
            return $PDOStatement->rowCount();
        }
        catch (PDOException $e) {
            $this->showErrMsg($e);
        }
    }
    
    private function showErrMsg($e)
    {
        echo "<h2>執行SQL語句失敗！</h2>";
        echo "錯誤編號：" . $e->getCode();
        echo "<br>錯誤行數：" . $e->getLine();
        echo "<br>錯誤檔案：" . $e->getFile();
        echo "<br>錯誤訊息：" . $e->getMessage();
        die();
    }
}