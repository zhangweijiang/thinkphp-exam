<?php
namespace app\pc\controller;
use app\api\controller\UserApi;
class Register extends BaseController
{
    /**
     * 考生注册页面
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    public function save(){
        //获取前台post的表单数据
        $data = input('post.');
        //密码通过sha2加密
        $data['password'] = sha256($data['password']);
        //获取当前时间
        $data['create_time'] = date('Y-m-d H:i:s');
        //创建考生api接口的实例
        $UserApi = new UserApi();
        //考生表数据的插入
        $result = $UserApi->addUser($data);
        if($result){
            $response = [
                "status" => true,
                "message" => "注册成功"
            ];
        }else{
            $response = [
                "status" => false,
                "message" => "注册失败"
            ];
        }
        return json($response);
    }



}
