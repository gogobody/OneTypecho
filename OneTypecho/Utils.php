
<?php
/**
 * Class Utils
 * author:gogobody
 */
function ParsePaopaoBiaoqingCallback($match)
{
    return '<img style="height: 18px; vertical-align: -4px;" src="https://cdn.jsdelivr.net/npm/typecho_joe_theme@4.3.5/assets/owo/paopao/' . str_replace('%', '', urlencode($match[1])) . '_2x.png"/>';
}

function ParseAruBiaoqingCallback($match)
{
    return '<img style="height: 18px; vertical-align: -4px;" src="https://cdn.jsdelivr.net/npm/typecho_joe_theme@4.3.5/assets/owo/aru/' . str_replace('%', '', urlencode($match[1])) . '_2x.png"/>';
}

class Utils
{
    /**
     * 美化时间格式
     */
    public static function time_beautify($time)
    {
        $text = '';
        $time = intval($time);
        $ctime = time();
        $t = $ctime - $time; //时间差
        if ($t < 0) {
            return date('Y-m-d', $time);
        }
        $y = date('Y', $ctime) - date('Y', $time);//是否跨年
        switch ($t) {
            case $t == 0:
                $text = '刚刚';
                break;
            case $t < 60://一分钟内
                $text = $t . '秒前';
                break;
            case $t < 3600://一小时内
                $text = floor($t / 60) . '分钟前';
                break;
            case $t < 86400://一天内
                $text = floor($t / 3600) . '小时前'; // 一天内
                break;
            case $t < 2592000://30天内
                if($time > strtotime(date('Ymd',strtotime("-1 day")))) {
                    $text = '昨天';
                } elseif($time > strtotime(date('Ymd',strtotime("-2 days")))) {
                    $text = '前天';
                } else {
                    $text = floor($t / 86400) . '天前';
                }
                break;
            case $t < 31536000 && $y == 0://一年内 不跨年
                $m = date('m', $ctime) - date('m', $time) -1;
                if($m == 0) {
                    $text = floor($t / 86400) . '天前';
                } else {
                    $text = $m . '个月前';
                }
                break;
            case $t < 31536000 && $y > 0://一年内 跨年
                $text = (11 - date('m', $time) + date('m', $ctime)) . '个月前';
                break;
            default:
                $text = (date('Y', $ctime) - date('Y', $time)) . '年前';
                break;
        }
        return $text;
    }

    /* 解析头像 */
    public static function ParseAvatar($mail)
    {
        $b = 'https://gravatar.helingqi.com/wavatar/';
        $c = strtolower($mail);
        $d = md5($c);
        $f = str_replace('@qq.com', '', $c);
        if (strstr($c, "qq.com") && is_numeric($f) && strlen($f) < 11 && strlen($f) > 4) {
            $g = 'https://thirdqq.qlogo.cn/g?b=qq&nk=' . $f . '&s=100';
        } else {
            $g = $b . $d . '?d=mm';
        }
        return $g;
    }

    /* 解析回复 */
    public static function ParseReply($text)
    {
        $text = preg_replace_callback(
            '/\:\:\(\s*(呵呵|哈哈|吐舌|太开心|笑眼|花心|小乖|乖|捂嘴笑|滑稽|你懂的|不高兴|怒|汗|黑线|泪|真棒|喷|惊哭|阴险|鄙视|酷|啊|狂汗|what|疑问|酸爽|呀咩爹|委屈|惊讶|睡觉|笑尿|挖鼻|吐|犀利|小红脸|懒得理|勉强|爱心|心碎|玫瑰|礼物|彩虹|太阳|星星月亮|钱币|茶杯|蛋糕|大拇指|胜利|haha|OK|沙发|手纸|香蕉|便便|药丸|红领巾|蜡烛|音乐|灯泡|开心|钱|咦|呼|冷|生气|弱|吐血)\s*\)/is',
            'ParsePaopaoBiaoqingCallback',
            $text
        );
        $text = preg_replace_callback(
            '/\:\@\(\s*(高兴|小怒|脸红|内伤|装大款|赞一个|害羞|汗|吐血倒地|深思|不高兴|无语|亲亲|口水|尴尬|中指|想一想|哭泣|便便|献花|皱眉|傻笑|狂汗|吐|喷水|看不见|鼓掌|阴暗|长草|献黄瓜|邪恶|期待|得意|吐舌|喷血|无所谓|观察|暗地观察|肿包|中枪|大囧|呲牙|抠鼻|不说话|咽气|欢呼|锁眉|蜡烛|坐等|击掌|惊喜|喜极而泣|抽烟|不出所料|愤怒|无奈|黑线|投降|看热闹|扇耳光|小眼睛|中刀)\s*\)/is',
            'ParseAruBiaoqingCallback',
            $text
        );
        return $text;
    }
    /**
     * [verifyPhone description] 效验邮箱号合法性
     * @param  [type] $phone [description] 邮箱号
     * @return [type]        [description]
     */
    public static function Verify_Email($Email = null){
        global $dArr;
        $dArr = array(
            '163.com','126.com','sina.com','yahoo.com.cn','yahoo.com','sohu.com','yeah.net','139.com',
            'tom.com','21cn.com','qq.com','foxmail.com','gmail.com','hotmail.com','263.net',
            'vip.qq.com','vip.163.com','vip.sina.com','vip.sina.com.cn','vip.foxmail.com',
        );
        if( empty($Email) ){
            return false;
        }
        list($e,$d) = explode('@', $Email);
        if( !empty($e) && !empty($d) ){
            $d = strtolower($d);
            if( !in_array($d,$dArr) ) {return false;}
            return preg_match('/^[a-z0-9]+([\+_\-\.]?[a-z0-9]+)*/i', $e);
        }
        return false;
    }
}