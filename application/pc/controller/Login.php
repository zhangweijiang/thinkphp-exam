<?php
namespace app\pc\controller;
use app\api\controller\UserApi;
use app\pc\validate\Login as LoginValidate;
class Login extends BaseController
{
    /**
     * 考生登录页面
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    public function login(){
        //创建登录验证的实例
        $validate = new LoginValidate;
        $result = $validate->check(input('post.'));
        //对用户登录数据进行校验
        if ($result === false) {//数据错误,输出错误信息
            $response = [
                "status" => false,
                "message" => $validate->getError() //登录验证错误信息
            ];
            return json($response);
        }else{
            $username = input('post.username');
            $password = input('post.password');
            //创建考试api接口的实例
            $UserApi = new UserApi();
            $response = $UserApi->login($username,$password);
            return $response;
        }
    }





}
