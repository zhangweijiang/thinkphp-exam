<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2017/12/26
 * Time: 10:40
 */

namespace app\admin\controller;

use app\api\controller\UserApi;

class User extends BaseController
{
    /**
     * 考生信息列表页面
     * @return mixed
     */
    public function lists()
    {
        // 返回模板视图
        return $this->fetch();
    }

    /**
     * 考生信息列表
     * @return \think\response\Json
     */
    public function userList()
    {
        // 创建考生API接口的实例
        $UserApi = new UserApi();

        // 获取所有考生的信息
        $list = $UserApi->getList();

        // 根据所有考生数据中的sex(性别)标识替换为对应的性别名称
        foreach ($list as &$v) {
            if ($v['sex'] == 1) {
                $v['sex'] = '男';
            } else if ($v['sex'] == 2) {
                $v['sex'] = '女';
            }
        }

        // 返回数据
        return json($list);
    }


    /**
     * 切换考生状态
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function switchStatus()
    {
        // 获取考生API接口的实例
        $UserApi = new UserApi();

        // 获取前台传过来的数据
        $data["id"] = trim(input('post.id'));
        $data["status"] = trim(input('post.status'));

        // 更新数据库中对应考生信息的状态
        $response = $UserApi->switchStatus($data);

        // 返回更新数据的结果
        return $response;
    }

    /**
     * 新增考生信息界面
     * @return mixed
     */
    public function add()
    {
        // 返回模板视图
        return $this->fetch();
    }

    /**
     * 新增考生信息
     * @return \think\response\Json
     */
    public function addUser()
    {
        // 创建考生API接口的实例
        $UserApi = new UserApi();

        // 获取前台传过来的数据并整合
        $user["username"] = trim(input('post.username'));
        $user["truename"] = trim(input('post.truename'));
        $user["password"] = sha256(trim(input('post.password')));
        $user["birth"] = trim(input('post.birth'));
        $user["sex"] = trim(input('post.sex'));
        $user["email"] = trim(input('post.email'));
        $user["phone"] = trim(input('post.phone'));
        $user["status"] = trim(input('post.status'));

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('avatar');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            if ($info) {
                // 成功上传后 获取上传信息;
                // 输出 20171224/42a79759f284b767dfcb2a0197904287.jpg
                $user['avatar'] = "/upload/".$info->getSaveName();
            } else {
                // 上传失败获取错误信息
                $this->error($info->getError());
            }
        }

        // 向数据库中新增考生信息
        $response = $UserApi->addUser($user);

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
        $UserApi= new UserApi();

        // 获取前台传过来的专业ID
        $id = trim(input('post.id'));

        // 根据专业ID获取对应的专业信息
        $user = $UserApi->getUser($id);

        // 定义模板变量major
        $this->assign('user', $user);

        // 返回对应控制器的模板视图add.html
        return $this->fetch('add');
    }

    /**
     * 更新考生信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editUser()
    {

        // 创建考生API接口的实例
        $UserApi = new UserApi();

        // 获取前台传过来的数据并整合
        $user["id"] = trim(input('post.id'));
        $user["username"] = trim(input('post.username'));
        $user["truename"] = trim(input('post.truename'));
        $user["password"] = trim(input('post.password'));
        $user["birth"] = trim(input('post.birth'));
        $user["sex"] = trim(input('post.sex'));
        $user["email"] = trim(input('post.email'));
        $user["phone"] = trim(input('post.phone'));
        $user["status"] = trim(input('post.status'));

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('avatar');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            if ($info) {
                // 成功上传后 获取上传信息;
                // 输出 20171224/42a79759f284b767dfcb2a0197904287.jpg
                $user['avatar'] = "/upload/".$info->getSaveName();
            } else {
                // 上传失败获取错误信息
                $this->error($info->getError());
            }
        }

        // 向数据库中新增考生信息
        $response = $UserApi->updateUser($user);

        // 返回更新数据的结果
        return $response;

    }

    /**
     * 删除考生信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function delete()
    {
        // 创建考生API接口的实例
        $UserApi = new UserApi();

        // 获取要删除的考生ID
        $id = trim(input('post.id'));

        // 获取对应考生ID的考生信息
        $user = $UserApi->getUser($id);

        //删除图片
        deleteFile(ROOT_PATH . 'public' . DS . 'upload' . DS . $user['avatar']);

        // 删除数据库中对应考生ID的考生信息
        $response = $UserApi->deleteUser($id);

        // 返回删除数据的结果
        return $response;
    }


}