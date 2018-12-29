<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2017/12/25
 * Time: 10:40
 */

namespace app\admin\controller;


use app\api\controller\ManagerApi;

class Manager extends BaseController
{
    /**
     * 管理员列表页
     * @return mixed
     */
    public function lists()
    {
        // 返回模板视图
        return $this->fetch();
    }

    /**
     * 获取管理员列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function managerList() {
        // 创建管理员API接口的实例
        $managerApi = new ManagerApi();

        // 获取所有的管理员数据
        $list = $managerApi->getManagerList();

        // 返回数据
        return $list;
    }

    /**
     * 切换管理员状态
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function switchStatus() {
        // 创建管理员API接口的实例
        $managerApi = new ManagerApi();

        // 获取前台传过来的数据
        $data["id"] = trim(input('post.id'));
        $data["status"] = trim(input('post.status'));

        // 更新数据库中对应ID的管理员状态
        $response = $managerApi->switchStatus($data);

        // 返回更新数据的结果
        return $response;
    }

    /**
     * 添加管理员界面
     * @return mixed
     */
    public function add()
    {
        // 返回模板视图
        return $this->fetch();
    }

    /**
     * 新增管理员数据
     * @return \think\response\Json
     */
    public function addManager() {
        // 创建管理员API接口的实例
        $managerApi = new ManagerApi();

        // 获取前台传过来的数据
        $manager["username"] = trim(input('post.username'));
        $manager["password"] = trim(input('post.password'));
        $manager["status"] = trim(input('post.status'));

        // 向数据库添加新的管理员数据
        $response = $managerApi->addManager($manager);

        // 返回新增数据的结果
        return $response;

    }

    /**
     * 删除管理员信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delete() {
        // 创建管理员API接口的实例
        $managerApi = new ManagerApi();

        // 获取要删除的管理员ID
        $id = trim(input('post.id'));

        // 删除数据库中对应的管理员信息
        $response = $managerApi->deleteManager($id);

        // 返回删除数据的结果
        return $response;
    }
}