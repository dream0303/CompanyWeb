<?php
//聲明命名空間
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\PersonnelModel;

//定義用戶控制器類，並繼承基礎控制器類
class PersonnelController extends BaseController
{
    //顯示用戶列表
    public function index()
    {
        //權限驗證
        $this->denyAccess();
        //創建模型類對象
        $modelObj = PersonnelModel::getInstance();
        //獲取多行數據
        $personnels = $modelObj->fetchAll();
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
        $data['姓名'] = $_POST['姓名'];
        $data['現任職稱']     = $_POST['現任職稱'];
        $data['部門代號']      = $_POST['部門代號'];
        $data['縣市']   = $_POST['縣市'];
        $data['地址']     = $_POST['地址'];
        $data['電話']     = $_POST['電話'];
        $data['郵遞區號']      = $_POST['郵遞區號'];
        $data['目前月薪資']   = $_POST['目前月薪資'];
        $data['年假天數']     = $_POST['年假天數'];
        
        //判斷用戶是否已經存在
        if (PersonnelModel::getInstance()->rowCount("姓名='{$data['姓名']}'")) {
            $this->jump("姓名{$data['姓名']}已經被註冊了！", "?c=Personnel");
        }
        

        //判斷記錄是否插入成功
        if (PersonnelModel::getInstance()->insert($data)) {
            $this->jump("註冊成功！", "?c=Personnel");
        } else {
            $this->jump("註冊失敗！", "?c=Personnel");
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
        $personnel = PersonnelModel::getInstance()->fetchOne("id={$id}");
        include VIEW_PATH . "edit.html";
    }
    
    //更新記錄
    public function update()
    {
        //權限驗證
        $this->denyAccess();
        
        //獲取表單數據
        $id             = $_POST['id'];
        $data['姓名'] = $_POST['姓名'];
        $data['現任職稱']     = $_POST['現任職稱'];
        $data['部門代號']      = $_POST['部門代號'];
        $data['縣市']   = $_POST['縣市'];
        $data['地址']     = $_POST['地址'];
        $data['電話']     = $_POST['電話'];
        $data['郵遞區號']      = $_POST['郵遞區號'];
        $data['目前月薪資']   = $_POST['目前月薪資'];
        $data['年假天數']     = $_POST['年假天數'];
        
        //判斷記錄是否更新成功
        if (PersonnelModel::getInstance()->update($data, $id)) {
            $this->jump("id={$id}記錄更新成功！", "?c=Personnel");
        } else {
            $this->jump("id={$id}記錄更新失敗！", "?c=Personnel");
        }
    }
    
}