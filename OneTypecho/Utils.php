
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

class date_tool
{

    /**
     * @desc 得到某天凌晨零点的时间戳
     * @param string $str
     * @return int
     */
    public static function getSomeZeroTimeStamp($str = 'today')
    {

        switch ($str) {
            case 'today':   // 今天凌晨零点的时间戳
                return strtotime(date("Y-m-d"), time());
                break;
            case 'yesterday':   // 昨天 即 今天凌晨零点的时间戳 减去 一天的秒数
                return strtotime(date("Y-m-d"), time()) - 3600 * 24;
                break;
            case 'tomorrow':    // 明天 即 今天凌晨零点的时间戳 加上 一天的秒数
                return strtotime(date("Y-m-d"), time()) + 3600 * 24;
                break;
            case 'month_first': // 这个月第一天凌晨零点的时间戳
                return strtotime(date("Y-m"), time());
                break;
            case 'year_first':  // 这一年第一天凌晨零点的时间戳
                return strtotime(date("Y-01"), time());
                break;
            default:   // 默认为今天凌晨零点的时间戳
                return strtotime(date("Y-m-d"), time());
                break;
        }

    }

    /**
     * @desc 友好时间显示
     * @param int 时间戳
     * @param string $lang $lang 语言, cn 中文, en 英文
     * @return bool|string
     */
    public static function get_friend_date($time, $lang = 'cn')
    {
        if (!$time) {
            return '';
        }
        $f_date = '';
        $d = time() - intval($time);
        $ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
        $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
        $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
        $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
        $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
        $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
        $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
        if ($lang == 'cn') {
            if ($d <= 10) {
                $f_date = '刚刚';
            } else {
                switch ($d) {
                    case $d < $td:
                        $f_date = '后天' . date('H:i', $time);
                        break;
                    case $d < 0:
                        $f_date = '明天' . date('H:i', $time);
                        break;
                    case $d < 60:
                        $f_date = $d . '秒前';
                        break;
                    case $d < 3600:
                        $f_date = floor($d / 60) . '分钟前';
                        break;
                    case $d < $dd:
                        $f_date = floor($d / 3600) . '小时前';
                        break;
                    case $d < $yd:
                        $f_date = '昨天' . date('H:i', $time);
                        break;
                    case $d < $byd:
                        $f_date = '前天' . date('H:i', $time);
                        break;
                    case $d < $md:
                        $f_date = date('m月d日 H:i', $time);
                        break;
                    case $d < $ld:
                        $f_date = date('m月d日', $time);
                        break;
                    default:
                        $f_date = date('Y年m月d日', $time);
                        break;
                }
            }
        } else {
            if ($d <= 10) {
                $f_date = 'just';
            } else {
                switch ($d) {
                    case $d < $td:
                        $f_date = 'the day after tomorrow' . date('H:i', $time);
                        break;
                    case $d < 0:
                        $f_date = 'tomorrow' . date('H:i', $time);
                        break;
                    case $d < 60:
                        $f_date = $d . 'seconds ago';
                        break;
                    case $d < 3600:
                        $f_date = floor($d / 60) . 'minutes ago';
                        break;
                    case $d < $dd:
                        $f_date = floor($d / 3600) . 'hour ago';
                        break;
                    case $d < $yd:
                        $f_date = 'yesterday' . date('H:i', $time);
                        break;
                    case $d < $byd:
                        $f_date = 'the day before yesterday' . date('H:i', $time);
                        break;
                    case $d < $md:
                        $f_date = date('m-d H:i', $time);
                        break;
                    case $d < $ld:
                        $f_date = date('m-d', $time);
                        break;
                    default:
                        $f_date = date('Y-m-d', $time);
                        break;
                }
            }
        }
        return $f_date;

    }

    /**
     * @desc 获取当前时间的前7天
     * @return array
     */
    public static function getLast7Days()
    {

        $begin = strtotime(date('Y-m-d', strtotime('-6 days')));  // ? 7天前
        $today_time = strtotime(date('Y-m-d'));  // ? 7天前
        $now_time = time();
        $weeks_arr = array();
        $weeks_arr['date'] = array();
        $weeks_arr['weeks'] = array();
        $weeks_arr['day'] = array();
        $weeks_array = array("日", "一", "二", "三", "四", "五", "六"); // 先定义一个数组
        $day_second = 3600 * 24;
        for ($i = $begin; $i < $now_time; $i = $i + $day_second) {
            if ($i != $today_time) {
                array_push($weeks_arr['date'], $i);
            } else {
                array_push($weeks_arr['date'], $now_time);
            }
            array_push($weeks_arr['weeks'], '星期' . $weeks_array[date('w', $i)]);
            array_push($weeks_arr['day'], date('Y-m-d', $i));
        }
        return $weeks_arr;

    }

    /**
     * @desc 获取星期几的信息
     * @param $timestamp 时间戳
     * @param string $lang 语言, cn 中文, en 英文
     * @return mixed
     */
    public static function get_week_day($timestamp, $lang = 'cn')
    {

        if ($lang == 'cn') {
            $week_array = array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
            return $week_array[date("w", $timestamp)];
        } else {
            return date("l"); // date("l") 可以获取英文的星期比如Sunday
        }

    }


    /**
     * @desc 获取月份
     * @param $timestamp 时间戳
     * @param string $lang cn 中文, en 英语
     * @return string
     */
    public static function get_month($timestamp, $lang = 'cn')
    {

        if ($lang == 'cn') {
            $month_arr = array(
                '1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'
            );
        } else {
            $month_arr = array(
                'Jan.', 'Feb.', 'Mar.', 'Apr.', 'May.', 'Jun.', 'Jul.', 'Aug.', 'Sept.', 'Oct.', 'Nov.', 'Dec.'
            );
        }
        $month = date('n', $timestamp);
        return $month_arr[$month - 1];

    }
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
            case $t < 31536000 && $y == 0: //一年内 不跨年
                $m = date('m', $ctime) - date('m', $time) -1;
                if($m == 0) {
                    $text = floor($t / 86400) . '天前';
                } else {
                    $text = $m . '个月前';
                }
                break;
            case $t < 31536000 && $y > 0://一年内 跨年
                $text = (12 - date('m', $time) + date('m', $ctime)) . '个月前';
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
        return filter_var($Email, FILTER_VALIDATE_EMAIL);
    }
}