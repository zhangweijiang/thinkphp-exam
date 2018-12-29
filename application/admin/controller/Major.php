<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2017/12/25
 * Time: 10:40
 */

namespace app\admin\controller;

use app\api\controller\MajorApi;

class Major extends BaseController
{
    /**
     * 专业信息列表页面
     * @return mixed
     */
    public function lists()
    {
        // 返回模板视图
        return $this->fetch();
    }

    /**
     * 专业信息列表
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function majorList() {
        // 创建专业信息API接口的实例
        $MajorApi = new MajorApi();

        // 获取所有专业信息的数据
        $list = $MajorApi->getMajorList();

        // 返回数据
        return json($list);
    }

    /**
     * 添加专业信息页面
     * @return mixed
     */
    public function add()
    {
        // 返回模板视图
        return $this->fetch();
    }

    /**
     * 添加专业信息
     * @return mixed
     */
    public function addMajor()
    {
        // 创建专业API接口的实例
        $MajorApi = new MajorApi();

        // 获取前台传过来的专业名称及排序数据，并整合
        $major["name"] = trim(input('post.name'));
        $major["order"] = trim(input('post.order'));

        // 向数据库新增专业信息
        $response = $MajorApi->addMajor($major);

        // 返回新增数据的结果
        return $response;
    }

    /**
     * 专业信息更新页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit()
    {
        // 创建专业API接口的实例
        $MajorApi = new MajorApi();

        // 获取前台传过来的专业ID
        $id = trim(input('post.id'));

        // 根据专业ID获取对应的专业信息
        $major = $MajorApi->getMajor($id);

        // 定义模板变量major
        $this->assign('major', $major);

        // 返回对应控制器的模板视图add.html
        return $this->fetch('add');
    }

    /**
     * 更新专业信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editMajor()
    {
        // 创建专业API接口的实例
        $MajorApi = new MajorApi();

        // 获取前台传过来的数据，并整合
        $major["id"] = trim(input('post.id'));
        $major["name"] = trim(input('post.name'));
        $major["order"] = trim(input('post.order'));

        // 向数据库更新对应的专业信息
        $response = $MajorApi->updateMajor($major);

        // 返回更新数据的结果
        return $response;
    }

    /**
     * 删除专业信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delete()
    {
        // 创建专业API接口的实例
        $MajorApi = new MajorApi();

        // 获取前台传过来的专业ID
        $id = trim(input('post.id'));

        // 根据专业ID，删除数据库中对应的专业信息
        $response = $MajorApi->deleteMajor($id);

        // 返回删除数据的结果
        return $response;
    }
}