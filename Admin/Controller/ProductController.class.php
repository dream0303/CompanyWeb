<?php
//聲明命名空間
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\ProductModel;

//定義用戶控制器類，並繼承基礎控制器類
class ProductController extends BaseController
{
    //顯示用戶列表
    public function index()
    {
        //權限驗證
        $this->denyAccess();
        //創建模型類對象
        $modelObj = ProductModel::getInstance();
        //獲取多行數據
        $products = $modelObj->fetchAll();
        include VIEW_PATH . "index.html";
    }
    
    //顯示添加用戶的視圖文件
    public function add()
    {
        //權限驗證
        $this->denyAccess();
        include VIEW_PATH . "add.html";
    }
    
    //新增產品
    public function insert()
    {
        
        //權限驗證
        $this->denyAccess();
        //獲取表單數據
        $data['產品名稱'] = $_POST['產品名稱'];
        $data['產品代號'] = $_POST['產品代號'];
        $data['單價']     = $_POST['單價'];
        $data['成本']      = $_POST['成本'];

        
        //判斷是否已經存在
        if (ProductModel::getInstance()->rowCount("產品名稱='{$data['產品名稱']}'")) {
            $this->jump("產品名{$data['產品名稱']}已經被新增了！", "?c=Product");
        }
        
        //判斷記錄是否插入成功
        if (ProductModel::getInstance()->insert($data)) {
            $this->jump("新增成功！", "?c=Product");
        } else {
            $this->jump("新增失敗！", "?c=Product");
        }
    }
    
    //顯示修改的表單
    public function edit()
    {
        //權限驗證
        $this->denyAccess();
        
        //獲取地址欄傳遞的id
        $id = $_GET['id'];
        //獲取指定ID的用戶資源
        $product = ProductModel::getInstance()->fetchOne("id={$id}");
        include VIEW_PATH . "edit.html";
    }
    
    //更新記錄
    public function update()
    {
        //權限驗證
        $this->denyAccess();
        //獲取表單數據
        $id = $_POST['id'];
        $data['產品名稱'] = $_POST['產品名稱'];
        $data['產品代號'] = $_POST['產品代號'];
        $data['單價'] = $_POST['單價'];
        $data['成本'] = $_POST['成本'];
        
        //判斷記錄是否更新成功
        if (ProductModel::getInstance()->update($data, $id)) {
            $this->jump("id={$id}記錄更新成功！", "?c=Product");
        } else {
            $this->jump("id={$id}記錄更新失敗！", "?c=Product");
        }
    }
    
    //刪除記錄
    public function delete()
    {
        //權限驗證
        $this->denyAccess();
        
        //獲取地址欄傳遞的id
        $id = $_GET['id'];
        //判斷是否刪除成功
        if (ProductModel::getInstance()->delete($id)) {
            $this->jump("id={$id}的記錄刪除成功", "?c=Product");
        } else {
            $this->jump("id={$id}的記錄刪除失敗", "?c=Product");
        }
    }
}