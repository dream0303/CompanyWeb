<?php
namespace Frame\Vendor;

//驗證碼最終類
final class Captcha
{
    //私有的成員屬性
    private $codelen; //驗證碼長度
    private $code; //驗證碼字符串
    private $width; //圖片的寬度
    private $height; //圖片的高度
    private $fontsize; //字號大小
    private $fontfile; //字體文件
    private $img; //圖像資源
    
    //公共的構造方法
    public function __construct($codelen = 4, $width =86, $height = 30, $fontsize = 18)
    {
        $this->codelen  = $codelen;
        $this->width    = $width;
        $this->height   = $height;
        $this->fontsize = $fontsize;
        //字體文件必須是絕對路徑
        $this->fontfile = ROOT_PATH . "Public" . DS . "Admin" . DS . "Images" . DS . "msyh.ttf";
        
        $this->createCode(); //生成隨機的驗證碼字符串
        $this->createImg(); //創建畫布
        $this->createBg(); //畫布背景
        $this->createFont(); //寫入文本
        $this->outPut(); //輸出圖像
    }
    
    //私有的生成隨機字符串的方法
    private function createCode()
    {
        $arr1 = array_merge(range('a', 'z'), range(0, 9), range('A', 'Z'));
        shuffle($arr1); //打亂數組
        $arr2 = array_rand($arr1, 4); //隨機取4個下標
        $str  = "";
        foreach ($arr2 as $index) {
            $str .= $arr1[$index];
        }
        $this->code = $str;
    }
    
    //私有的創建畫布的方法
    private function createImg()
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);
    }
    
    //私有的繪製畫布背景方法
    private function createBg()
    {
        //分配顏色
        $color1 = imagecolorallocate($this->img, mt_rand(200, 250), mt_rand(200, 250), mt_rand(200, 255));
        //繪製帶背景的矩形
        imagefilledrectangle($this->img, 0, 0, $this->width, $this->height, $color1);
        //繪製像數點
        for ($i = 1; $i <= 200; $i++) {
            $color3 = imagecolorallocate($this->img, mt_rand(0, 250), mt_rand(0, 250), mt_rand(50, 255));
            imagesetpixel($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), $color3);
        }
        //繪製線段
        for ($i = 1; $i < 10; $i++) {
            $color4 = imagecolorallocate($this->img, mt_rand(0, 250), mt_rand(0, 250), mt_rand(50, 255));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color4);
        }
    }
    
    //私有的寫入文本到圖像上
    private function createFont()
    {
        $color2 = imagecolorallocate($this->img, mt_rand(0, 250), mt_rand(0, 250), mt_rand(50, 255));
        imagettftext($this->img, $this->fontsize, 0, 10, 20, $color2, $this->fontfile, $this->code);
    }
    
    //私有的輸出圖像
    private function outPut()
    {
        header("content-type:image/png");
        imagepng($this->img);
        imagedestroy($this->img);
    }
    
    //公共的獲取驗證碼字符串的方法
    public function getCode()
    {
        return strtolower($this->code);
    }
}