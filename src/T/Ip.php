<?php
namespace T;
/**
 *  IP 地理位置查询类 修改自 ThinkPHP
 *  由于使用UTF8编码 如果使用纯真IP地址库的话 需要对返回结果进行编码转换
 * @category   ORG
 * @package  ORG
 * @subpackage  Net
 * @author    T <zthi@qq.com>
 */
class Ip
{

    /**
     * QQWry.Dat文件指针
     * @var resource
     */
    private $fp;

    /**
     * 第一条IP记录的偏移地址
     *
     * @var int
     */
    private $firstIp;

    /**
     * 最后一条IP记录的偏移地址
     * @var int
     */
    private $lastIp;

    /**
     * IP记录的总条数（不包含版本信息记录）
     * @var int
     */
    private $totalIp;

    /**
     * 中国省市数据
     * @var array
     */
    private $stateCity = ['北京市' => ['北京市'], '天津市' => ['天津市'], '上海市' => ['上海市'], '重庆市' => ['重庆市'], '河北省' => ['石家庄市', '唐山市', '秦皇岛市', '邯郸市', '邢台市', '保定市', '张家口市', '承德市', '沧州市', '廊坊市', '衡水市'], '山西省' => ['太原市', '大同市', '阳泉市', '长治市', '晋城市', '朔州市', '晋中市', '运城市', '忻州市', '临汾市', '吕梁市'], '台湾省' => ['台北市', '高雄市', '基隆市', '台中市', '台南市', '新竹市', '嘉义市', '台北县', '宜兰县', '桃园县', '新竹县', '苗栗县', '台中县', '彰化县', '南投县', '云林县', '嘉义县', '台南县', '高雄县', '屏东县', '澎湖县', '台东县', '花莲县'], '辽宁省' => ['沈阳市', '大连市', '鞍山市', '抚顺市', '本溪市', '丹东市', '锦州市', '营口市', '阜新市', '辽阳市', '盘锦市', '铁岭市', '朝阳市', '葫芦岛市'], '吉林省' => ['长春市', '吉林市', '四平市', '辽源市', '通化市', '白山市', '松原市', '白城市', '延边朝鲜族自治州'], '黑龙江省' => ['哈尔滨市', '齐齐哈尔市', '鹤 岗 市', '双鸭山市', '鸡 西 市', '大 庆 市', '伊 春 市', '牡丹江市', '佳木斯市', '七台河市', '黑 河 市', '绥 化 市', '大兴安岭地区'], '江苏省' => ['南京市', '无锡市', '徐州市', '常州市', '苏州市', '南通市', '连云港市', '淮安市', '盐城市', '扬州市', '镇江市', '泰州市', '宿迁市'], '浙江省' => ['杭州市', '宁波市', '温州市', '嘉兴市', '湖州市', '绍兴市', '金华市', '衢州市', '舟山市', '台州市', '丽水市'], '安徽省' => ['合肥市', '芜湖市', '蚌埠市', '淮南市', '马鞍山市', '淮北市', '铜陵市', '安庆市', '黄山市', '滁州市', '阜阳市', '宿州市', '巢湖市', '六安市', '亳州市', '池州市', '宣城市'], '福建省' => ['福州市', '厦门市', '莆田市', '三明市', '泉州市', '漳州市', '南平市', '龙岩市', '宁德市'], '江西省' => ['南昌市', '景德镇市', '萍乡市', '九江市', '新余市', '鹰潭市', '赣州市', '吉安市', '宜春市', '抚州市', '上饶市'], '山东省' => ['济南市', '青岛市', '淄博市', '枣庄市', '东营市', '烟台市', '潍坊市', '济宁市', '泰安市', '威海市', '日照市', '莱芜市', '临沂市', '德州市', '聊城市', '滨州市', '菏泽市'], '河南省' => ['郑州市', '开封市', '洛阳市', '平顶山市', '安阳市', '鹤壁市', '新乡市', '焦作市', '濮阳市', '许昌市', '漯河市', '三门峡市', '南阳市', '商丘市', '信阳市', '周口市', '驻马店市', '济源市'], '湖北省' => ['武汉市', '黄石市', '十堰市', '荆州市', '宜昌市', '襄樊市', '鄂州市', '荆门市', '孝感市', '黄冈市', '咸宁市', '随州市', '仙桃市', '天门市', '潜江市', '神农架林区', '恩施土家族苗族自治州'], '湖南省' => ['长沙市', '株洲市', '湘潭市', '衡阳市', '邵阳市', '岳阳市', '常德市', '张家界市', '益阳市', '郴州市', '永州市', '怀化市', '娄底市', '湘西土家族苗族自治州'], '广东省' => ['广州市', '深圳市', '珠海市', '汕头市', '韶关市', '佛山市', '江门市', '湛江市', '茂名市', '肇庆市', '惠州市', '梅州市', '汕尾市', '河源市', '阳江市', '清远市', '东莞市', '中山市', '潮州市', '揭阳市', '云浮市'], '甘肃省' => ['兰州市', '金昌市', '白银市', '天水市', '嘉峪关市', '武威市', '张掖市', '平凉市', '酒泉市', '庆阳市', '定西市', '陇南市', '临夏回族自治州', '甘南藏族自治州'], '四川省' => ['成都市', '自贡市', '攀枝花市', '泸州市', '德阳市', '绵阳市', '广元市', '遂宁市', '内江市', '乐山市', '南充市', '眉山市', '宜宾市', '广安市', '达州市', '雅安市', '巴中市', '资阳市', '阿坝藏族羌族自治州', '甘孜藏族自治州', '凉山彝族自治州'], '贵州省' => ['贵阳市', '六盘水市', '遵义市', '安顺市', '铜仁地区', '毕节地区', '黔西南布依族苗族自治州', '黔东南苗族侗族自治州', '黔南布依族苗族自治州'], '海南省' => ['海口市', '三亚市', '五指山市', '琼海市', '儋州市', '文昌市', '万宁市', '东方市', '澄迈县', '定安县', '屯昌县', '临高县', '白沙黎族自治县', '昌江黎族自治县', '乐东黎族自治县', '陵水黎族自治县', '保亭黎族苗族自治县', '琼中黎族苗族自治县'], '云南省' => ['昆明市', '曲靖市', '玉溪市', '保山市', '昭通市', '丽江市', '思茅市', '临沧市', '文山壮族苗族自治州', '红河哈尼族彝族自治州', '西双版纳傣族自治州', '楚雄彝族自治州', '大理白族自治州', '德宏傣族景颇族自治州', '怒江傈傈族自治州', '迪庆藏族自治州'], '青海省' => ['西宁市', '海东地区', '海北藏族自治州', '黄南藏族自治州', '海南藏族自治州', '果洛藏族自治州', '玉树藏族自治州', '海西蒙古族藏族自治州'], '陕西省' => ['西安市', '铜川市', '宝鸡市', '咸阳市', '渭南市', '延安市', '汉中市', '榆林市', '安康市', '商洛市'], '广西壮族自治区' => ['南宁市', '柳州市', '桂林市', '梧州市', '北海市', '防城港市', '钦州市', '贵港市', '玉林市', '百色市', '贺州市', '河池市', '来宾市', '崇左市'], '西藏自治区' => ['拉萨市', '那曲地区', '昌都地区', '山南地区', '日喀则地区', '阿里地区', '林芝地区'], '宁夏回族自治区' => ['银川市', '石嘴山市', '吴忠市', '固原市', '中卫市'], '新疆维吾尔自治区' => ['乌鲁木齐市', '克拉玛依市', '石河子市　', '阿拉尔市', '图木舒克市', '五家渠市', '吐鲁番市', '阿克苏市', '喀什市', '哈密市', '和田市', '阿图什市', '库尔勒市', '昌吉市　', '阜康市', '米泉市', '博乐市', '伊宁市', '奎屯市', '塔城市', '乌苏市', '阿勒泰市'], '内蒙古自治区' => ['呼和浩特市', '包头市', '乌海市', '赤峰市', '通辽市', '鄂尔多斯市', '呼伦贝尔市', '巴彦淖尔市', '乌兰察布市', '锡林郭勒盟', '兴安盟', '阿拉善盟'], '澳门特别行政区' => ['澳门特别行政区'], '香港特别行政区' => ['香港特别行政区']];

    /**
     * 中国省数据
     * @var array
     */
    private $provinces = ["黑龙江省", "辽宁省", "吉林省", "河北省", "河南省", "湖北省", "湖南省", "山东省", "山西省", "陕西省",
        "安徽省", "浙江省", "江苏省", "福建省", "广东省", "海南省", "四川省", "云南省", "贵州省", "青海省", "甘肃省",
        "江西省", "台湾省", "内蒙古", "宁夏", "新疆", "西藏", "广西", "北京市", "上海市", "天津市", "重庆市", "香港", "澳门"];

    private $unknown = '未知';

    /**
     * 构造函数，打开 QQWry.Dat 文件并初始化类中的信息
     * @param string $filename
     * @return Ip
     */
    public function __construct($filename = "qqwry.dat")
    {
        $this->fp = 0;
        if (($this->fp = fopen(dirname(__FILE__) . '/' . $filename, 'rb')) !== false) {
            $this->firstIp = $this->getLong();
            $this->lastIp = $this->getLong();
            $this->totalIp = ($this->lastIp - $this->firstIp) / 7;
        }
    }


    /**
     * 获取当前客户端IP地址
     * @return string
     */
    public function getIp()
    {
        static $ip = null;
        if ($ip !== null)
            return $ip;

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos)
                unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else
            $ip = '0.0.0.0';

        if ($ip == '::1')  //localhost开启IPv6时的特殊分处理
            $ip = '127.0.0.1';

        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? $ip : '0.0.0.0';
        return $ip;
    }


    /**
     * 设置未知的返回字段
     * @param string $unknown
     * @return self
     */
    public function setUnknown($unknown = '未知'){
        $this->unknown = $unknown;
        return $this;
    }

    /**
     * 获取省信息
     * @param string $ip
     * @return mixed
     */
    public function getProvince($ip = '')
    {
        return $this->getLocation($ip)['state'];
    }

    public function getCity($ip = ''){
        return $this->getLocation($ip)['city'];
    }

    public function getArea($ip = ''){
        return  $this->getLocation($ip)['area'];

    }

    public function getExtend($ip = ''){
        return $this->getLocation($ip)['extend'];
    }

    /**
     * 根据所给 IP 地址或域名返回所在地区信息
     * @access public
     * @param string $ip
     * @return array
     */
    public function getLocation($ip = '')
    {
        if (empty($ip))
            $ip = $this->getIp();
        if (strpos($ip, 'http://') === 0) {
            $ip = substr($ip, 7);
            $ip = gethostbyname($ip);
        }


        static $locationData = [];
        if (!isset($locationData[$ip])) {
            if (!$this->fp)
                return null;            // 如果数据文件没有被正确打开，则直接返回空
            $location['ip'] = $ip;   // 将输入的域名转化为IP地址
            $ip = $this->packIp($location['ip']);   // 将输入的IP地址转化为可比较的IP地址
            // 不合法的IP地址会被转化为255.255.255.255
            // 对分搜索
            $l = 0;                         // 搜索的下边界
            $u = $this->totalIp;            // 搜索的上边界
            $findip = $this->lastIp;        // 如果没有找到就返回最后一条IP记录（QQWry.Dat的版本信息）
            while ($l <= $u) {              // 当上边界小于下边界时，查找失败
                $i = floor(($l + $u) / 2);  // 计算近似中间记录
                fseek($this->fp, $this->firstIp + $i * 7);
                $beginip = strrev(fread($this->fp, 4));     // 获取中间记录的开始IP地址
                // strrev函数在这里的作用是将little-endian的压缩IP地址转化为big-endian的格式
                // 以便用于比较，后面相同。
                if ($ip < $beginip) {       // 用户的IP小于中间记录的开始IP地址时
                    $u = $i - 1;            // 将搜索的上边界修改为中间记录减一
                } else {
                    fseek($this->fp, $this->getLong3());
                    $endip = strrev(fread($this->fp, 4));   // 获取中间记录的结束IP地址
                    if ($ip > $endip) {     // 用户的IP大于中间记录的结束IP地址时
                        $l = $i + 1;        // 将搜索的下边界修改为中间记录加一
                    } else {                  // 用户的IP在中间记录的IP范围内时
                        $findip = $this->firstIp + $i * 7;
                        break;              // 则表示找到结果，退出循环
                    }
                }
            }


            //获取查找到的IP地理位置信息
            fseek($this->fp, $findip);
            $location['beginip'] = long2ip($this->getLong());   // 用户IP所在范围的开始地址
            $offset = $this->getLong3();
            fseek($this->fp, $offset);
            $location['endip'] = long2ip($this->getLong());     // 用户IP所在范围的结束地址
            $byte = fread($this->fp, 1);    // 标志字节


            switch (ord($byte)) {
                case 1:                     // 标志字节为1，表示国家和区域信息都被同时重定向
                    $countryOffset = $this->getLong3();         // 重定向地址
                    fseek($this->fp, $countryOffset);
                    $byte = fread($this->fp, 1);    // 标志字节
                    switch (ord($byte)) {
                        case 2:             // 标志字节为2，表示国家信息又被重定向
                            fseek($this->fp, $this->getLong3());
                            $location['all'] = $this->getString();
                            fseek($this->fp, $countryOffset + 4);
                            $location['extend'] = $this->getExtendString();
                            break;
                        default:            // 否则，表示国家信息没有被重定向
                            $location['all'] = $this->getString($byte);
                            $location['extend'] = $this->getExtendString();
                            break;
                    }
                    break;
                case 2:                     // 标志字节为2，表示国家信息被重定向
                    fseek($this->fp, $this->getLong3());
                    $location['all'] = $this->getString();
                    fseek($this->fp, $offset + 8);
                    $location['extend'] = $this->getExtendString();
                    break;
                default:                    // 否则，表示国家信息没有被重定向
                    $location['all'] = $this->getString($byte);
                    $location['extend'] = $this->getExtendString();
                    break;
            }

            if (trim($location['all']) == 'CZ88.NET') {  // CZ88.NET表示没有有效信息
                $location['all'] = $this->unknown;
            }
            if (trim($location['extend']) == 'CZ88.NET') {
                $location['extend'] = '';
            }

            $location['all'] = iconv("gb2312", "UTF-8//IGNORE", $location['all']);
            $location['extend'] = iconv("gb2312", "UTF-8//IGNORE", $location['extend']);
            $location['extend'] = $location['extend'] === null ? '' : $location['extend'];

            $parseData = $this->parseLocation($location['all']);
            $location['state'] = $parseData[0];
            $location['city'] = $parseData[1];
            $location['area'] = $parseData[2];

            $locationData[$ip] = $location;
        }
        return $locationData[$ip];
    }

    /**
     * 解析省市区县
     * @param $location
     * @return array
     *
     * @example '江苏省苏州市吴江市' , '江苏省苏州市吴中区' , '江苏省苏州市昆山市' , '黑龙江省鸡西市' , '广西桂林市' , '陕西省西安市户县' , '河南省开封市通许县' ,
     *   '内蒙古呼伦贝尔市海拉尔区','甘肃省白银市平川区','孟加拉','上海市' , '北京市朝阳区' ,'美国' ,'香港' ,  俄罗斯' ,'IANA'
     */
    private function parseLocation($location)
    {
        $state = $city = $area = $this->unknown;
        if (preg_match('/^(.+省)?(新疆|内蒙古|宁夏|西藏|广西|香港|澳门)?(.+市)?(.+市)?(.+(县|区))?/', $location, $preg)) {
            if (count($preg) == 4) {        //匹配 "浙江省杭州市"
                $state = $preg[1] ? $preg[1] : ($preg[2] ? $preg[2] : $preg[3]);
                $city = $preg[3];
            } else if (count($preg) == 7) { //匹配 "浙江省杭州市江干区"
                $state = $preg[1] ? $preg[1] : ($preg[2] ? $preg[2] : $preg[3]);
                $city = $preg[3];
                $area = $preg[5];
            } else if(count($preg) == 3) { //匹配 "香港"
                $state = $preg[1] ? $preg[1] : $preg[2];
                $city = $state;
            }
            else if(count($preg) == 2){  //匹配 "浙江省"
                $state = $preg[1] ? $preg[1] : $this->unknown;
            }
        }
        return [$state, $city, $area];
    }


    /**
     * 返回读取的长整型数
     *
     * @access private
     * @return int
     */
    private function getLong()
    {
        //将读取的little-endian编码的4个字节转化为长整型数
        $result = unpack('Vlong', fread($this->fp, 4));
        return $result['long'];
    }

    /**
     * 返回读取的3个字节的长整型数
     *
     * @access private
     * @return int
     */
    private function getLong3()
    {
        //将读取的little-endian编码的3个字节转化为长整型数
        $result = unpack('Vlong', fread($this->fp, 3) . chr(0));
        return $result['long'];
    }

    /**
     * 返回压缩后可进行比较的IP地址
     *
     * @access private
     * @param string $ip
     * @return string
     */
    private function packIp($ip)
    {
        // 将IP地址转化为长整型数，如果在PHP5中，IP地址错误，则返回False，
        // 这时intval将Flase转化为整数-1，之后压缩成big-endian编码的字符串
        return pack('N', intval(ip2long($ip)));
    }

    /**
     * 返回读取的字符串
     *
     * @access private
     * @param string $data
     * @return string
     */
    private function getString($data = "")
    {
        $char = fread($this->fp, 1);
        while (ord($char) > 0) {        // 字符串按照C格式保存，以\0结束
            $data .= $char;             // 将读取的字符连接到给定字符串之后
            $char = fread($this->fp, 1);
        }
        return $data;
    }

    /**
     * 返回地区信息
     *
     * @access private
     * @return string
     */
    private function getExtendString()
    {
        $byte = fread($this->fp, 1);    // 标志字节
        switch (ord($byte)) {
            case 0:                     // 没有区域信息
                $area = "";
                break;
            case 1:
            case 2:                     // 标志字节为1或2，表示区域信息被重定向
                fseek($this->fp, $this->getLong3());
                $area = $this->getString();
                break;
            default:                    // 否则，表示区域信息没有被重定向
                $area = $this->getString($byte);
                break;
        }
        return $area;
    }

    /**
     * 析构函数，用于在页面执行结束后自动关闭打开的文件。
     *
     */
    public function __destruct()
    {
        if ($this->fp) {
            fclose($this->fp);
        }
        $this->fp = 0;
    }
}