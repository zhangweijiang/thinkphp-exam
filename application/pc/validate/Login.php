<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2017/12/24
 * Time: 19:26
 */

namespace app\pc\validate;

use think\Validate;

class Login extends Validate
{
    // 校验规则
    protected $rule = [
        'username' => 'require|max:25', // 用户名：必需，最大不能超过25
        'password' => 'require|min:6|max:25', // 密码：必需，最小不小于6，最大不超过25
        'captcha_code' => 'require|captcha', // 验证码：必需，验证码匹配
    ];

    // 校验规则对应的错误信息
    protected $message = [
        'username.require' => '名称必须',
        'username.max' => '名称最多不能超过25个字符',
        'password.require'     => '密码不能为空',
        'password.min'         => '密码为6~16位字符',
        'password.max'         => '密码为6~16位字符',
        'captcha_code.require' => '请输入验证码',
        'captcha_code.captcha' => '验证码错误',
    ];
}