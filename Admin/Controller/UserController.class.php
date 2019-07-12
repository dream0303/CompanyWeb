<?php
//聲明命名空間
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\UserModel;

//定義用戶控制器類，並繼承基礎控制器類
class UserController extends BaseController
{
    //顯示用戶列表
    public function index()
    {
        //權限驗證
        $this->denyAccess();
        //創建模型類對象
        $modelObj              = UserModel::getInstance();
        //獲取多行數據
        $users                 = $modelObj->fetchAll();

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
        $data['username'] = $_POST['username'];
        $data['password'] = md5($_POST['password']);
        $data['name']     = $_POST['name'];
        $data['tel']      = $_POST['tel'];
        $data['status']   = $_POST['status'];
        $data['role']     = $_POST['role'];
        $data['addate']   = time();
        
        //判斷用戶是否已經存在
        if (UserModel::getInstance()->rowCount("username='{$data['username']}'")) {
            $this->jump("用戶名{$data['username']}已經被註冊了！", "?c=User");
        }
        
        //判斷兩次密碼是否一致
        if ($data['password'] != md5($_POST['confirmpwd'])) {
            $this->jump("兩次輸入的密碼不一致！", "?c=User");
        }
        
        //判斷記錄是否插入成功
        if (UserModel::getInstance()->insert($data)) {
            $this->jump("註冊成功！", "?c=User");
        } else {
            $this->jump("註冊失敗！", "?c=User");
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
        $user                 = UserModel::getInstance()->fetchOne("id={$id}");
        include VIEW_PATH . "edit.html";
    }
    
    //更新記錄
    public function update()
    {
        //權限驗證
        $this->denyAccess();
        
        //獲取表單數據
        $id             = $_POST['id'];
        $data['name']   = $_POST['name'];
        $data['tel']    = $_POST['tel'];
        $data['status'] = $_POST['status'];
        $data['role']   = $_POST['role'];
        
        //判斷密碼是否為空
        if (!empty($_POST['password']) && !empty($_POST['confirmpwd'])) {
            //判斷兩次密碼要一致
            if ($_POST['password'] == $_POST['confirmpwd']) {
                $data['password'] = md5($_POST['password']);
            }
        }
        
        //判斷記錄是否更新成功
        if (UserModel::getInstance()->update($data, $id)) {
            $this->jump("id={$id}記錄更新成功！", "?c=User");
        } else {
            $this->jump("id={$id}記錄更新失敗！", "?c=User");
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
        if (UserModel::getInstance()->delete($id)) {
            $this->jump("id={$id}的記錄刪除成功", "?c=User");
        } else {
            $this->jump("id={$id}的記錄刪除失敗", "?c=User");
        }
    }
    
    //顯示登錄界面
    public function login()
    {

        include VIEW_PATH . "login.html";
    }
    
    //登錄驗證
    public function loginCheck()
    {
        //獲取表單提交值
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $verify   = $_POST['verify'];
        
        //判斷驗證碼與服務器保存的是否一致
        if (strtolower($verify) != $_SESSION['captcha']) {
            $this->jump("驗證碼不一致！", "?c=User&a=login");
        }
        
        //判斷用戶名和密碼，與數據庫是否一致
        $user = UserModel::getInstance()->fetchOne("username='$username' and password='$password'");
        if (!$user) {
            $this->jump("用戶名或密碼不正確！", "?c=User&a=login");
        }
        
        //判斷用戶賬號狀態
        if (empty($user['status'])) {
            $this->jump("賬號被停用，請與管理員聯繫！", "?c=User&a=login");
        }
        
        //更新數據庫關於用戶登錄的數據：last_login_ip、last_login_time、login_times
        $data['last_login_ip']   = $_SERVER['REMOTE_ADDR'];
        $data['last_login_time'] = time();
        $data['login_times']     = $user['login_times'] + 1;
        if (!UserModel::getInstance()->update($data, $user['id'])) {
            $this->jump("用戶資料更新失敗！", "?c=User&a=login");
        }
        
        //將用戶的狀態存入SESSION
        $_SESSION['uid']      = $user['id'];
        $_SESSION['username'] = $username;
        
        //跳轉到後台首頁
        header("location:./admin.php");
    }
    
    //獲取驗證碼方法
    public function captcha()
    {
        //創建驗證碼類的對象
        $captcha             = new \Frame\Vendor\Captcha();
        //將驗證碼字符串，保存到SESSION中
        $_SESSION['captcha'] = $captcha->getCode();
    }
    
    //用戶退出方法
    public function logout()
    {
        //刪除SESSION數據
        unset($_SESSION['username']);
        unset($_SESSION['uid']);
        //刪除SESSION文件
        session_destroy();
        //設置當前SESSIONID對應的COOKIE數據為過期時間
        setcookie(session_name(), false);
        //跳轉到後台登錄頁面
        header("location:admin.php?c=User&a=login");
    }
}