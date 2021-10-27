# PHP扩展开发之旅

> 很久以前，就想要动手写一个自己的扩展了，不过一直在思考要做的多么强大，反倒一直没有开始行动。索性就从今天开始，从一个最简单部分开始，逐渐完善自己的扩展。

##什么样的扩展

本来想要把自己做的API框架做成扩展，结果发现一口吃个大胖子不太现实，索性就从最简单的来做，然后一点点的丰满。

```php
function get($key, $default=0, $is=0);//获取参数$_GET[key]/$_POST[key]
function req($url, $method=1, $data=false);//获取请求结果,支持各类请求方式，和请求数据
function prt($data, $format='json');//输出结果，支持json/xml/binary等
```

## 第一次生成扩展

```shell
#0. 检查php版本，由于我用的是php7.3的dev版本，所以可能和其他的不太一样
php -v
#1. 进入php的扩展目录
cd /say/code/php-src/ext 
#2. 查看php的扩展生成脚本，相较于php5的生成器，php7.3把这个生成器变成了php脚本，更方便阅读与使用
vim ext_skel.php
#3. 生成say扩展
php ext_skel.php --ext say
#4. 编译安装say扩展
cd /say/code/php-src/ext/say
phpize
./configure --enable-say
make
make install
#5. 加载say扩展
vim /say/soft/php/lib/php.ini
extension=say
#6. 测试扩展
php say.php
```

**say.php**

```php
/**
 * 测试脚本内容
 */
say_test1();//输出：The extension say is loaded and working!
say_test2();//返回：Hello World
```

测试成功以后，就可以在这个扩展里根据自己的需求进行功能开发了。

## 函数get开发

get函数，主要就是通过一个函数接收\$\_GET[key]或\$\_POST[key]，同时对值进行trim、addslashes等安全过滤，返回一个值。

```php
function get($key, $default=null, $is=0){
    $val = isset($_GET[$key])?$_GET[$key]:(isset($_POST[$key])?$_POST[$key]:(isset($default)?$default:false));//接收值，优先使用GET、其后使用POST
    if($is && $val!==false) return NULL;
    if(is_array($val)){//数组特殊处理
        foreach($val as $vk => $vv){
            if(is_array($vv)) return NULL;//不接收多维数组处理
            $vv = trim($vv);//过滤两边的空格等
    		$val[trim($vk)] = addslashes($vv);//防注入
        }
    }else{//非数组
        $val = trim($val);//过滤两边的空格等
    	$val = addslashes($val);//防注入
    }
    return $val;//返回结果    
}
```

### 第一步 构造

对于这个函数的开发，需要了解扩展的几部分内容：

1. 通过ZEND宏接收参数
2. 使用hash_find查找超级变量\$\_GET、\$\_POST中的值
3. 使用Z_TYPE_INFO_P()、Z_TYPE_P()判断参数类别
4. 使用RETURN\_\*()函数返回结果
5. 使用Z_*_P()获取zval的对应值
6. IS_NULL、IS_FALSE、IS_TRUE、IS_STRING、IS_LONG、IS_ARRAY等常量的含义
7. 使用zend_throw_exception()、zend_throw_exception_ex()在遇到错误时抛异常

> **注意**
>
> 以上内容主要集中在
>
> Zend/zend_API.h
>
> Zend/zend_type.h
>
> 在使用zend_throw_exception()、zend_throw_exception_ex()时，编译扩展会出现warining提示，但是并不影响功能的使用，有时间探究一下实际原因。

基于以上的PHP扩展内容，再比照ext中的其他扩展实现，已经能够将一个get()方法构造出来。接下来，则是要针对输入的参数进行安全过滤了。

### 第二步 完善

