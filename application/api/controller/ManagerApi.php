<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/1/9
 * Time: 15:29
 */

namespace app\api\controller;

use app\api\model\Manager as ManagerModel;
use think\Db;

class ManagerApi
{
    /***
     * 登录接口
     * @param $username string 用户名
     * @param $password string 密码
     * @return \think\response\Json
     */
    public function login($username, $password)
    {

        $managerModel = new ManagerModel();

        $code = $managerModel->login($username, $password);

        $response = [
            "status" => true,
            "message" => ""
        ];

        switch ($code) {
            case 1: // 登录成功
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
     * 获取管理员列表
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getManagerList()
    {

        $list = Db::name('Manager')->field('password',true)->select();

        return json($list);
    }

    /**
     * 添加管理员
     * @param $data array 管理员信息
     * @return \think\response\Json
     */
    public function addManager($data)
    {
        $response = [
            "status" => true,
            "message" => ""
        ];

        if (Db::name('manager')->where('username', $data["username"])->count()) {
            $response["status"] = false;
            $response["message"] = "该用户名已存在";
        } else {

            $data["password"] = sha256($data["password"]);

            Db::name('manager')->insert($data);
        }

        return json($response);

    }

    /**
     * 切换管理员状态
     * @param $data array 管理员信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function switchStatus($data)
    {
        $res = Db::name('manager')->update($data);

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
     * 修改管理员密码
     * @param $data array 管理员信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updatePassword($data)
    {
        $data["password"] = sha256($data["password"]);
        $res = Db::name('manager')->update($data);

        $response = [
            "status" => true,
            "message" => ""
        ];

        if ($res == 0) {
            $response["status"] = false;
            $response["message"] = "密码修改失败！";
        }

        return json($response);
    }

    /**
     * 删除管理员
     * @param $id string 管理员ID
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteManager($id)
    {

        $res = Db::name('manager')->delete($id);
        $response = [
            "status" => true,
            "message" => ""
        ];

        if ($res == 0) {
            $response["status"] = false;
            $response["message"] = "该记录已被删除！";
        }

        return json($response);
    }

}