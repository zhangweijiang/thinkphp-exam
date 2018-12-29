<?php
/**
 * 数字签名认证
 * @param string $data 被认证的数据
 * @return string 签名
 */
function dataAuthSign($data)
{
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名

    return $sign;
}

/**
 * 随机生成密码函数
 * @param int $length 密码长度
 * @return string
 */
function generate_password($length = 8)
{
    // 密码字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组 $chars 的任意元素
        // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        $password .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $password;
}

/***
 * json对象转数组
 * @param $array
 * @return array
 */
function object_array($array)
{
    if (is_object($array)) {
        $array = (array)$array;
    }
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}

/**
 * //将秒数转换为时间（年、天、小时、分、秒）
 * @param $time int 秒数
 * @return bool|string
 */
function Sec2Time($time)
{   $time = $time*60;
    if (is_numeric($time)) {
        $value = array(
            "years" => 0, "days" => 0, "hours" => 0,
            "minutes" => 0, "seconds" => 0,
        );
        if ($time >= 31556926) {
            $value["years"] = floor($time / 31556926);
            $time = ($time % 31556926);
        }
        if ($time >= 86400) {
            $value["days"] = floor($time / 86400);
            $time = ($time % 86400);
        }
        if ($time >= 3600) {
            $value["hours"] = floor($time / 3600);
            $time = ($time % 3600);
        }
        if ($time >= 60) {
            $value["minutes"] = floor($time / 60);
            $time = ($time % 60);
        }
        $value["seconds"] = floor($time);

        //$t=$value["years"] ."年". $value["days"] ."天"." ". $value["hours"] ."小时". $value["minutes"] ."分".$value["seconds"]."秒";
        if ($value['hours'] > 0) {
            if ($value['minutes'] == '0') {
                $t = $value["hours"] . "小时";
            } else {
                $t = $value["hours"] . "小时" . $value["minutes"] . "分";
            }
        } else {
            $t = $value["minutes"] . "分";
        }
        Return $t;

    } else {
        return (bool)FALSE;
    }
}

/**
 * 删除文件
 * @param $file string 待删除的文件路径
 * @return bool
 */
function deleteFile($file)
{
    if (is_file($file)) {
        return @unlink($file);
    } else {
        return false;
    }
}