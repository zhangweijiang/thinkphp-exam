<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/3/8
 * Time: 8:30
 */

namespace app\index\controller;

use think\Controller;

class BaseController extends Controller
{

    /**
     * 后台控制器初始化
     */
    public function _initialize()
    {
        if ($this->isLogin() === false) {//未登录
            $this->redirect('login/index');
        }
    }

    /**
     * 检查用户是否登录
     * @return bool|mixed
     */
    public static function isLogin()
    {
        $admin = session('admin');
        if (empty($admin)) {
            return false;
        } else {
            return true;
        }

    }

    /**
     * 用户登出
     */
    public function logout()
    {
        session('admin', NULL);

        $this->redirect('login/index');
    }
}