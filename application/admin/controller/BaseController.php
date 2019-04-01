<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2017/12/24
 * Time: 10:31
 */

namespace app\admin\controller;


use app\api\controller\ManagerApi;
use think\Controller;

class BaseController extends Controller
{

    /**
     * 后台控制器初始化
     */
    public function _initialize()
    {
        // 判断是否登录
        if ($this->isLogin() === false) { //未登录，则跳转至登陆页
            // 跳转，参数为`控制器/方法`
            $this->redirect('login/index');
        }
    }

    /**
     * 检查用户是否登录
     * @return bool|mixed
     */
    public static function isLogin()
    {
        // 获取后台用户登录session
        $admin = session('admin');
        // 若判断session值是否为空
        if (empty($admin)) {
            // 为空，无用户登录，返回false
            return false;
        } else {
            // 不为空，有用户登录，返回true
            return true;
        }

    }

    /**
     * 修改密码
     * @return \think\response\Json
     */
    public function editPassword() {
        //获取前台post的表单数据
        $password = input('post.password');
        $admin = session('admin');
        //通过sha2进行加密
        $data["password"] = $password;
        $data["id"] = $admin["id"];
        //创建考试api接口的实例
        $ManagerApi = new ManagerApi();
        //更新管理员的密码
        $response = $ManagerApi->updatePassword($data);

        return $response;
    }

    /**
     * 用户登出
     */
    public function logout()
    {
        // 清除用户登录的session
        session('admin', NULL);
        // 清除后跳转页面至登陆页
        $this->redirect('login/index');
    }
}