<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/1/9
 * Time: 15:28
 */

namespace app\api\model;


use think\Model;

class Manager extends Model
{
    /*登录验证*/
    public function login($username, $password)
    {
        $where['username'] = $username;
        $where['password'] = sha256($password);

        $manager = Manager::where($where)->find();
        if ($manager) {
            if ($manager["status"] == 1) {
                unset($manager["password"]);
                session('admin', $manager);
            } else {
                return -1;
            }
            return 1;
        } else {
            return 0;
        }

    }


}