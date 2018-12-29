<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/1/30
 * Time: 9:23
 */

namespace app\api\model;


use think\Model;

class User extends Model
{
    /**
     * 登录验证
     * @param $username
     * @param $password
     * @return int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login($username, $password)
    {
        $where['username'] = $username;
        $where['password'] = sha256($password);

        $user = $this->where($where)->find();
        if ($user) {
            if ($user["status"] == 1) {
                unset($user["password"]);
                session('user', $user);
            } else {
                return -1;
            }
            return 1;
        } else {
            return 0;
        }

    }
}