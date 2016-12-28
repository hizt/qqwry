# QQWRY IP Location v1.0
使用QQWRY.dat IP 数据库，查找IP对应的 省、市、地区等信息。  <br />

## Version
```
QQWRY.dat 版本 : 2016-12-25
```

##Installation
`composer require zt/qqwry`

##How To Use
### 详细使用介绍查看项目`example.php`,下面列出一个简单的使用介绍

```PHP
   use \T\Ip;
   $ip = new Ip();
   $ip->getProvince('X.X.X.X');
   $ip->getCity('X.X.X.X');
   $ip->getArea('X.X.X.X');

   $ip->getProvince('http://www.baidu.com');
   $ip->getCity('http://www.baidu.com');
   $ip->getArea('http://www.baidu.com');

```

<br />


##Bug && Contact Author
* bug提交：[https://github.com/hizt/ThinkPHPUnit/issues](https://github.com/hizt/ThinkPHPUnit/issues) 
* 作者：[zthi@qq.com](mailto:zthi@qq.com)