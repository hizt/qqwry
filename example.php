<html lang="zh-cn">
<head>
    <style>
        table {
            border-collapse: collapse;
        }
        table td, table th {
            padding: 5px;
            border: solid 1px #ddd;
        }
    </style>
</head>
<body>
<?php
include_once 'src/T/Ip.php';
$ip = new \T\Ip();
$ip->setUnknown('<span style="color:red">UNKNOWN</span>'); //设置未知IP的返回值


$testIp = [
    '您的IP' => [$ip->getIp()],
    '北京' => ['60.194.19.17', '58.31.104.254', '58.83.207.254'],
    '上海' => ['114.80.166.240', '222.72.132.166', '220.248.3.203'],
    '浙江' => ['124.160.75.205', '60.191.254.185', '218.108.222.19'],
    '美国' => ['56.1.1.200', '130.10.1.200', '161.58.1.200'],
    '香港' => ['61.244.148.166', '219.78.113.243', '61.93.133.133'],
    '宁夏' => ['222.75.147.47' , '222.75.147.199' , '202.100.96.69'],
    'URL地址'=> ['http://www.baidu.com'] //URL地址在解析过程中有一个域名解析的过程，效率较低
];
$html = '';
$count = 0;
foreach ($testIp as $k => $v) {
    $html .= "<h2>{$k}</h2>";
    $html .= "<table>";
    $html .= "<tr><th>IP地址</th><th>省</th><th>市</th><th>地区</th><th>附加信息</th><th>所有字段</th></tr>";
    foreach ($v as $v2) {
        $count ++;
        $province = $ip->getProvince($v2);  //获取省
        $city = $ip->getCity($v2);          //获取市
        $area = $ip->getArea($v2);          //获取地区
        $extend = $ip->getExtend($v2);      //获取扩展信息
        $all = json_encode( $ip->getLocation($v2) , JSON_UNESCAPED_UNICODE );
        $html .= "<tr><td>{$v2}</td><td>{$province}</td><td>{$city}</td><td>{$area}</td><td>{$extend}</td><td>{$all}</td></tr>";
    }
    $html .= "</table> <hr />";
}

echo "<h1>测试：{$count} 个IP， 用时：" . getRuntime() . '毫秒;</h1>' . $html ;


function getRuntime(){
    $now = microtime(true);
    return round( ( $now  -  $_SERVER['REQUEST_TIME_FLOAT'] ) * 1000 );
}


?>
</body>
</html>

