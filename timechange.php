<?php
/**
 *  $start_time  开始时间
 *  $end_time  结束时间
 *
 */
function cb_check_duration($start_time,$end_time){
    if(($end_time - $start_time)>31*24*60*60){
        return true;
    }else{
        return false;
    }
}

/**
 * 字符串去HTML/UBB
 * @param str $val
 */
function cb_remove_html($str){
    $str = strip_tags(trim($str));
    return $str;
}

/**
 *  $list  排序结果集
 *  $field  排序字段
 *  $sortby  排序顺序
 */
function cb_list_sort_by($list,$field, $sortby='ASC') {
    if(is_array($list)){
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortby) {
            case 'ASC': // 正向排序
                asort($refer);
                break;
            case 'DESC':// 逆向排序
                arsort($refer);
                break;
        }
        foreach ( $refer as $key=> $val)
            $resultSet[] = &$list[$key];
        return $resultSet;
    }
    return false;
}

/**
 * 检查字符串是否为空
 */
function cb_empty($int_value){
    if( $int_value == ""){
        return true;
    }else{
        return false;
    }
}

/**
 * 统一价格显示格式
 * @param float $price
 * @return float
 */
function cb_price($price){
    $price = round($price,2);
    return number_format($price,2,".","");
}

/**
 * 检查云平台定制版本
 * @return number
 */
function cb_check_custom(){
    if(false !== strpos($_SERVER['SERVER_NAME'], "ijiela")){
        if(is_file(CONFIG_PATH.'config_ijiela.php')){
            C(include CONFIG_PATH.'config_ijiela.php');
        }
        return 1;
    }else{
        return 0;
    }
}

/**
 * 判断网吧云平台版本
 * @param string $yversion
 * @return boolean
 */
function cb_netbar_isnew($yversion){
    if("4.0"==$yversion){
        return true;
    }else{
        return false;
    }
}

/**
 * 分钟数字转中文
 * @param int $minute
 * @return string
 */
function cb_minute2str($minute){
    $minute = intval($minute);
    $hour = floor($minute / 60);
    $minute = $minute % 60;
    $str = $hour."小时".$minute."分钟";
    return $str;
}

/**
 * 计算两个时间戳相差的天数
 * @param int $str1
 * @param int $str2
 */
function cb_cal_day($str1,$str2){
    $str1 += 0;
    $str2 += 0;
    if($str2 > $str1){
        $time1 = $str1;
        $time2 = $str2;
    }else{
        $time1 = $str2;
        $time2 = $str1;
    }
    $days = ceil(($time2 - $time1) / 86400);
    return $days;
}

/**
 * 计算两个日期相差的天数
 * @param date $str1
 * @param date $str2
 */
function cb_cal_days($str1,$str2){
    $time1 = strtotime($str1);
    $time2 = strtotime($str2);
    if($time2 > $time1){
        $p_time1 = $time1;
        $p_time2 = $time2;
    }else{
        $p_time1 = $time2;
        $p_time2 = $time1;
    }
    $days = ceil(($p_time2 - $p_time1) / 86400);
    return $days;
}

/**
 * 检查数据合法性
 * @param string $rule
 * @param string $value
 * @return boolean
 */
function cb_check($rule,$value){
    $zh = '\x{4e00}-\x{9fa5}';
    $other = '~`!@#$%\^&*()_\-+=\[\]{}|\\:;"\',?.\/><\\\\';
    $validate = array(
        'require'  => '/.+/',
        'email'    => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
        'url'      => '/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/',
        'currency' => '/^\d+(\.\d+)?$/',
        'number'   => '/^\d+$/',
        'zip'      => '/^[1-9]\d{5}$/',
        'integer'  => '/^[-\+]?\d+$/',
        'double'   => '/^[-\+]?\d+(\.\d+)?$/',
        'english'  => '/^[A-Za-z]+$/',
        'zh'       => '/^['.$zh.']+$/u',
        'en_num'   => '/^[A-Za-z0-9]+$/',
        'en_num_zh'=> '/^[A-Za-z0-9'.$zh.']+$/u',
        'en_zh'    => '/^[A-Za-z'.$zh.']+$/u',
        'ip'       => '/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/',
        'telphone' => '/^1\d{10}$/',
        'time'     => '/^\d{2}:\d{2}$/',
        'date'     => '/^\d{4}-\d{2}-\d{2}$/',
        'datetime' => '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/',
        'other'    => '/^['.$other.']+$/',
        'en_num_other' => '/^[A-Za-z0-9'.$other.']+$/',
        'en_num_zh_other' => '/^[A-Za-z0-9'.$zh.''.$other.']+$/u',
    );
    if(isset($validate[strtolower($rule)])){
        $rule2 = $validate[strtolower($rule)];
    }
    $result = 1===preg_match($rule2,$value);
    if($result){
        if("time" == $rule){
            return cb_check_time($value);
        }
        return true;
    }else{
        return false;
    }
}

/**
 * 检查时间格式是否合法
 * @param time $time
 * @return boolean
 */
function cb_check_time($time){
    list($hour,$min) = explode(":",$time);
    if($hour >=0 && $hour <= 23 && $min >=0 && $min <= 59){
        return true;
    }else{
        return false;
    }
}

/**
 * 判断身份证是否合法
 * @param string $card
 * @return boolean
 */
function cb_check_card($card){
    import("IdCard",APP_PATH.'/Lib/ORG');
    return IdCard::check($card);
}

/**
 * 字符串去 XSS
 * @param str $val
 */
function cb_remove_xss($val){
    return strip_tags(trim($val));
}

/**
 * 判断字符长度
 * @param string $str
 * @return int
 */
function cb_strlen($str){
    return mb_strlen($str,'utf8');
}

/**
 * UBB代码转化为HTML代码
 * @param string $str
 * @return string
 */
function cb_ubb2html($str){
    $str = trim($str);
    $str = preg_replace("/\[face:([0-9]+):([0-9]+)\]/","<img src=\"".C('FACE_PATH')."\\1/\\2.png\" />",$str);
    return $str;
}

/**
 * 获取时间区间
 * @param string $type
 * @param int $time
 * @return array
 */
function cb_get_time($type,$time=0){
    $start_time = $end_time = 0;
    if("today" == $type){//今日
        if($time > 0){
            $start_time = strtotime(date("Y-m-d",$time));
        }else{
            $start_time = strtotime(date("Y-m-d"));
        }
        $end_time = $start_time + 86400 - 1;
    }else if("week" == $type){//本周
        if($time > 0){
            $day = date("N",$time)-1;
            $start_time = strtotime(date("Y-m-d",$time)) - ($day * 86400);
        }else{
            $day = date("N")-1;
            $start_time = strtotime(date("Y-m-d",strtotime("-".$day." day")));
        }
        $end_time = $start_time + (7 * 86400) - 1;
    }else if("month" == $type){//本月
        if($time > 0){
            $start_time = mktime(0,0,0,date("m",$time),1,date("Y",$time));
            $end_time = $start_time + (86400 * date("t",$time)) - 1;
        }else{
            $start_time = mktime(0,0,0,date("m"),1,date("Y"));
            $end_time = $start_time + (86400 * date("t")) - 1;
        }
    }
    return array($start_time,$end_time);
}

/**
 * 获得URL返回内容
 * @param string $url
 * @return string
 */
function cb_get_http_content($url){
    $ctx = stream_context_create(array('http' => array('timeout' => 3)));//单位:秒
    return file_get_contents($url, 0, $ctx);
}

/************************************************** **************************************************/

function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
    if(function_exists("mb_substr"))
        return mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}

function get_client_ip(){
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = "unknown";
    return($ip);
}


/**
 * 判断奖品是否过期
 * @param int $time
 * @param int $global_receive_valid
 * @return boolean true:过期 false:未过期
 */
function getGoodsIsExpire($time,$global_receive_valid){
    if(0 == $global_receive_valid){
        return false;
    }else{
        $nowtime = time();
        $endtime = $time + ($global_receive_valid * 86400);
        $end_time = strtotime(date("Y-m-d",$endtime))+86400;
        if($nowtime > $end_time){
            return true;
        }else{
            return false;
        }
    }
}


/**
 * 账号/昵称/手机/身份证 智能判断
 * @param string $text
 * @return array
 */
function searchUser($text){
    $text = trim($text);
    if(false !== strpos($text,"@")){
        $type = "username";//账号
    }else if(cb_check('telphone',$text)){
        $type = "telphone";//手机号
    }else if(cb_check_card($text)){
        $type = "card";//身份证号
    }else{
        $type = "nickname";//昵称
    }
    return array($type,$text);
}

/**
 * 生成上传的图片文件名
 * @param int $num
 * @return string
 */
function getUploadImgName($num=0){
    $name = $_SESSION[C('USER_AUTH_LOGIN')]['chain_id']."_".$_SESSION[C('USER_AUTH_LOGIN')]['netbar_id']."_".time();
    if($num > 0){
        $name = $name."_".$num;
    }
    return $name;
}

/**
 * 获得奖品图片
 * @param int $goods_type
 * @param string $goods_image
 */
function getGoodsImage($goods_type,$goods_image){
    $image = C('WEBSITE_PATH')."default/noimage.png";
    if(1 == $goods_type){
        if(!empty($goods_image)){
            $image = C('YUN_IMAGES_DIR')."smallImages/".$goods_image;
        }
    }else if(2 == $goods_type){
        $image = C('WEBSITE_PATH')."default/luck.png";
    }else if(3 == $goods_type){
    }else if(4 == $goods_type){
        $image = C('WEBSITE_PATH')."default/crystal.png";
    }else if(100 == $goods_type){
        if(!empty($goods_image)){
            $image = C('WEBSITE_PATH')."hotgoods/small/".$goods_image;
        }
    }
    return $image;
}

/**
 * 有大小图，有云平台和客户端区别
 * 1、获取留言图片
 */
function getGuestBookImage($img_path,$guestbook_image,$file_dir_name="guestbook",$size="small"){
    $image = C('WEBSITE_PATH')."default/noimage.png";
    if($img_path == 1){//云端
        if(!empty($guestbook_image)){
            $image = C('OSS_YUN_DIR').$file_dir_name."/".$size."/".$guestbook_image;
        }
    }else if($img_path == 2){// 客户端
        if(!empty($guestbook_image)){
            $image = C('OSS_CLIENT_DIR').$file_dir_name."/".$size."/".$guestbook_image;
        }
    }
    return $image;
}

/**
 * 无大小图，无云平台和客户端区别
 * 1、获取投票图片
 */
function getVoteImage($image,$file_dir_name="votelist"){
    if(!empty($image)){
        $image = C('OSS_YUN_DIR').$file_dir_name."/".$image;
    }
    return $image;
}

/**
 * 获得用户头像地址
 * @param string $image
 * @return string
 */
function getAvatar($image){
    if(!empty($image)){
        if(in_array($image,array("face_001.png","face_002.png","face_003.png","face_004.png","face_005.png","face_006.png","face_007.png"))){
            $avatar = C("WEBSITE_PATH")."face/".$image;
        }else{
            $avatar = C("OSS_CLIENT_DIR")."smallImages/".$image;
        }
    }else{
        $avatar = C("WEBSITE_PATH")."default/avatar.png";
    }
    return trim($avatar);
}

/**
 * 获取举报理由
 */
function getReportType($class_id){
    $data = array(
        "1" => '广告',
        "2" => '刷屏',
        "3" => '欺诈',
        "4" => '色情',
        "5" => '反动',
        "6" => '其他',
    );
    if(empty($class_id)){
        return $data;
    }else{
        return $data[$class_id];
    }
}

/**
 * 计算幸运点的市场价
 * @param int $luck
 * @return number
 */
function getLuckPrice($luck){
    $price = round(intval($luck) / 100,2);
    $price = sprintf("%01.2f",$price);
    return $price;
}

/**
 * 计算水晶的市场价
 * @param int $crystal
 * @return number
 */
function getCrystalPrice($crystal){
    $price = round(intval($crystal) / 20,2);
    $price = sprintf("%01.2f",$price);
    return $price;
}

?>