<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/1/9
 * Time: 11:25
 */

namespace app\api\controller;


use think\Db;
use app\api\model\User as UserModel;

class UserApi
{
    /**
     * 用户登录
     * @param $username
     * @param $password
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login ($username, $password)
    {

        $userModel = new UserModel();

        $code = $userModel->login($username, $password);

        $response = [
            "status" => true,
            "message" => "登录成功"
        ];


        switch ($code) {
            case 1: // 登录成功
                if(session('url')){
                    $response['url'] = session('url');
                }else{
                    $response['url'] = url('pc/index/index');
                }
                break;
            case 0: // 用户名或密码错误
                $response["status"] = false;
                $response["message"] = "用户名或密码错误！";
                break;
            case -1: // 账号被禁用
                $response["status"] = false;
                $response["message"] = "该用户已被禁用！";
                break;
            default:
                break;
        }

        return json($response);
    }

    /**
     * 获取用户信息（根据ID获取）
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUser($id)
    {
        $user = Db::name('user')->field('password',true)->where('id', $id)->find();

        return $user;
    }

    /**
     * 获取用户信息（根据Email获取）
     * @param $email
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserByEmail($email)
    {
        $user = Db::name('user')->field('password',true)->where('email', $email)->find();

        return $user;
    }

    /**
     * 获取用户列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserList() {
        $list = Db::name('user')->field('password',true)->select();

        return json($list);
    }

    /**
     * 获取用户数量
     * @return int|string
     */
    public function countUser() {
        $count = Db::name('user')->where('status','1')->count();

        return $count;
    }

    /**
     * 添加用户信息
     * @param $data
     * @return \think\response\Json
     */
    public function addUser($data)
    {
        $response = [
            "status" => true,
            "message" => ""
        ];

        if (Db::name('user')->where('username', $data["username"])->count()) {
            $response["status"] = false;
            $response["message"] = "该用户名已存在!";
        } else {
            Db::name('user')->insert($data);
        }

        return json($response);

    }

    /**
     * 切换用户状态
     * @param $data array 用户信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function changeStatus($data)
    {
        $res = Db::name('user')->update($data);

        $response = [
            "status" => true,
            "message" => ""
        ];

        if ($res == 0) {
            $response["status"] = false;
            $response["message"] = "不存在该ID或者状态不变！";
        }

        return json($response);
    }

    /**
     * 更新用户信息
     * @param $data
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateUser($data)
    {
        $result = Db::name('user')->update($data);

        $response = [
            "status" => true,
            "message" => ""
        ];

        return json($response);
    }

    /**
     * 删除用户信息
     * @param $id
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteUser($id)
    {

        $result = Db::name('user')->delete($id);
        $response = [
            "status" => true,
            "message" => ""
        ];

        if ($result == 0) {
            $response["status"] = false;
            $response["message"] = "该记录已被删除！";
        }

        return json($response);
    }

    public function resetPassword($email) {
        $response = [
            "status" => true,
            "message" => ""
        ];

        if (Db::name('email')->where('username', $email)->count()) {
            $response["status"] = false;
            $response["message"] = "该用户名已存在!";
        } else {
            Db::name('user')->insert($email);
        }

        return json($response);
    }

    /**
     * 切换考生状态
     * @param $data array 管理员信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function switchStatus($data)
    {
        $res = Db::name('user')->update($data);

        $response = [
            "status" => true,
            "message" => ""
        ];

        if ($res == 0) {
            $response["status"] = false;
            $response["message"] = "不存在该ID或者状态不变！";
        }

        return json($response);
    }

    /**
     * 获取考生信息列表
     * @param string $where  条件
     * @param string $order  排序
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getList($where='',$order='id desc',$limit=''){
        $list = Db::name('user')->where($where)->order($order)->limit($limit)->select();

        return $list;
    }


}