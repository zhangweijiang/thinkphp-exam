<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/1/9
 * Time: 10:31
 */

namespace app\pc\controller;


use think\Controller;

class BaseController extends Controller
{

    /**
     * 后台控制器初始化
     */
    public function _initialize()
    {

    }

    /**
     * 检查用户是否登录
     * @return bool|mixed
     */
    public function isLogin()
    {
        $user = session('user');
        if (empty($user)) {
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
        session('user', NULL);

        $this->redirect('login/index');
    }
}