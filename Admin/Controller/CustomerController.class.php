<?php
//聲明命名空間
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\CustomerModel;

//定義用戶控制器類，並繼承基礎控制器類
class CustomerController extends BaseController
{
    //顯示用戶列表
    public function index()
    {
        //權限驗證
        $this->denyAccess();
        //創建模型類對象
        $modelObj = CustomerModel::getInstance();
        //獲取多行數據
        $customers = $modelObj->fetchAll();
        include VIEW_PATH . "index.html";
    }
    
    //顯示添加用戶的視圖文件
    public function add()
    {
        //權限驗證
        $this->denyAccess();
        include VIEW_PATH . "add.html";
    }
    
    //新增用戶
    public function insert()
    {
        
        //權限驗證
        $this->denyAccess();
        //獲取表單數據
        $data['客戶寶號'] = $_POST['客戶寶號'];
        $data['客戶代號']     = $_POST['客戶代號'];
        $data['縣市']      = $_POST['縣市'];
        $data['地址']   = $_POST['地址'];
        $data['郵遞區號']     = $_POST['郵遞區號'];
        $data['聯絡人']     = $_POST['聯絡人'];
        $data['職稱']      = $_POST['職稱'];
        $data['電話']   = $_POST['電話'];
        $data['行業別']     = $_POST['行業別'];
        $data['統一編號']     = $_POST['統一編號'];
        
        //判斷用戶是否已經存在：
        if (CustomerModel::getInstance()->rowCount("客戶寶號='{$data['客戶寶號']}'")) {
            $this->jump("客戶名{$data['客戶寶號']}已經被註冊了！", "?c=Customer");
        }
        

        
        //判斷記錄是否插入成功
        if (CustomerModel::getInstance()->insert($data)) {
            $this->jump("新增成功！", "?c=Customer");
        } else {
            $this->jump("新增失敗！", "?c=Customer");
        }
    }
    
    //顯示修改的表單
    public function edit()
    {
        //權限驗證
        $this->denyAccess();
        
        //獲取地址欄傳遞的id
        $id                   = $_GET['id'];
        //獲取指定ID的用戶資源
        $customer= CustomerModel::getInstance()->fetchOne("id={$id}");
        include VIEW_PATH . "edit.html";
    }
    
    //更新記錄
    public function update()
    {
        //權限驗證
        $this->denyAccess();
        
        //獲取表單數據
        $id             = $_POST['id'];
        $data['客戶寶號'] = $_POST['客戶寶號'];
        $data['客戶代號']     = $_POST['客戶代號'];
        $data['縣市']      = $_POST['縣市'];
        $data['地址']   = $_POST['地址'];
        $data['郵遞區號']     = $_POST['郵遞區號'];
        $data['聯絡人']     = $_POST['聯絡人'];
        $data['職稱']      = $_POST['職稱'];
        $data['電話']   = $_POST['電話'];
        $data['行業別']     = $_POST['行業別'];
        $data['統一編號']     = $_POST['統一編號'];
        

        //判斷記錄是否更新成功
        if (CustomerModel::getInstance()->update($data, $id)) {
            $this->jump("id={$id}記錄更新成功！", "?c=Customer");
        } else {
            $this->jump("id={$id}記錄更新失敗！", "?c=Customer");
        }
    }
    
}