<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2017/12/25
 * Time: 10:54
 */

namespace app\admin\controller;


use app\api\controller\ManagerApi;
use think\Controller;

class Login extends Controller
{
    /**
     * 登陆页界面
     * @return mixed
     */
    public function index()
    {
        // 返回模板视图
        return $this->fetch();
    }

    /**
     * 后台管理系统登录
     * @return \think\response\Json
     */
    public function login() {
        // 创建管理员API接口的实例
        $managerApi = new ManagerApi();

        // 获取前台传过来的用户名和密码
        $username = trim(input('post.username'));
        $password = trim(input('post.password'));

        // 将用户名密码传到API接口中进行登录操作
        $response = $managerApi->login($username, $password);

        // 返回登录的结果
        return $response;
    }

    public function resetPassword($data)
    {

        return json(["status" => true, "message" => "重置成功", "data" => ["email" => $data["email"]]]);
    }
}