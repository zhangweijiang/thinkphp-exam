<?php
namespace app\pc\controller;
use app\api\controller\UserApi;
use app\common\PHPMailer\PHPMailer;
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

    /**
     * 重置密码
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function resetPassword() {

        // 获取邮箱
        $email = trim(input('post.email'));

        $userApi = new UserApi();

        // 根据邮箱查找用户
        $user = $userApi->getUserByEmail($email);

        $response = [
            "status" => true,
            "message" => ""
        ];

        if($user == null) {
            $response = [
                "status" => false,
                "message" => "该邮箱地址不属于任何用户!"
            ];

            return $response;
        } else {
            // 生成重置密码
            $code = generate_password(8);

            // 邮件主题
            $subject = "在线考试网站【忘记密码】邮件";

            // 邮件内容
            $body = "您好，我们已对您在在线考试网站上对应邮箱帐号的密码进行重置，重置的密码为：" . $code . "。请及时登录修改密码！";

            // 判断邮件发送成功失败
            if ($this->send_email($email, $subject, $body)) {
                // 发送成功则将密码进行加密并更新到数据库中
                $user["password"] = sha256($code);

                $res = $userApi->updateUser($user);

                if (!$res) {
                    $response = [
                        "status" => true,
                        "message" => ""
                    ];
                    $this->success("重置密码失败！");
                }
            } else {
                $response = [
                    "status" => false,
                    "message" => "邮件发送失败！"
                ];
            }

            return $response;
        }
    }

    /**
     * 发送email
     * @param $toemail array|string 要发送到的email地址
     * @param $subject string  email标题
     * @param $body string  email主体内容
     * @return bool
     * @throws \app\common\PHPMailer\phpmailerException
     */
    function send_email($toemail, $subject, $body)
    {

        $mail = new PHPMailer();

        $mail->SMTPDebug = 0;

        $mail->isSMTP();
        //加密方式 "ssl" or "tls"
        $mail->SMTPSecure = config('email_config.secure');
        //smtp需要鉴权
        $mail->SMTPAuth = true;
        $mail->Host = config('email_config.host');
        $mail->Port = config('email_config.port');
        $mail->Username = config('email_config.username');
        $mail->Password = config('email_config.psw');

        $mail->From = config('email_config.From');
        $mail->FromName = config('email_config.FromName');
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);

        if (is_array($toemail)) {
            foreach ($toemail as $to_email) {
                $mail->AddAddress($to_email);
            }
        } else {
            $mail->AddAddress($toemail);
        }
        //添加该邮件的主题
        $mail->Subject = $subject;
        //添加邮件正文
        $mail->Body = $body;
        //为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
        //$mail->addAttachment('./d.jpg','mm.jpg');
        //同样该方法可以多次调用 上传多个附件
        //$mail->addAttachment('./Jlib-1.1.0.js','Jlib.js');
        //dump($mail);exit;

        $status = $mail->send();

        if ($status) {
            return true;
        } else {
            return false;
        }
    }





}
