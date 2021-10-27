PHP扩展开发中，无论是面向对象还是面向过程，都会牵扯到function，无论在PHP层面是如何调用的


```php
test($par);//最常见的方法
$say = new say(); $say->test($par);//实例化后的调用
say::test($par);//静态方法调用
```

我们可能都需要传入参数。

# zend_parse_parameters()

这个是最基本的调用传递过来参数的函数。

```c
long foo;
if(zend_parse_parameter(ZEND_NUM_ARGS() TSRMLS_CC, "l", &foo) == FAILURE)
{
	return;
}
```

通过这种调用方式，就可以把想要获取到的参数赋值给变量，事例里面是接收一个long型的参数赋值给变量foo。

# type_spec

接收参数，需要知道参数的类型，方法中使用了"l"表示long型，对照表如下：

|  参数  | 代表着的类型                                   | 对应C里的数据类型               |
| :--: | ---------------------------------------- | ----------------------- |
|  b   | Boolean 布尔                               | zend_bool               |
|  l   | Integer 整型                               | long                    |
|  d   | Floating Point 浮点                        | double                  |
|  s   | String 字符串                               | char*, int前者接收指针，后者接收长度 |
|  r   | Resource 资源                              | zval*                   |
|  a   | Array 数组                                 | zval*                   |
|  o   | Object instance 对象                       | zval*                   |
|  O   | Object instance of a specified type 特定类型的对象 | zend_class_entry*       |
|  z   | Non-specific zval 任意类型                   | zval*                   |
|  Z   | zval**类型                                 | zval**                  |
|  f   | 表示函数、方法名称                                |                         |

# 特殊参数

## |

执勤的参数都是必须得，之后的参数都是费必需的

## ！

如果接收一个PHP里的null变量，则直接转成C里的NULL，而不是zval，这样会节省cpu和内存开销

## /

如果传递过来的变量与别的变脸公用一个zval，而且不是引用，则强行分离

# 可变参数

## zend_get_arguments()

接收的是zval *

## zend_get_arguments_ex()

接收的是zval ** 

## zend_get_parameter_array_ex()

接收的是zval ***

## zend_get_parameter_array()

需要ZEND_NUM_ARGS()作为参数
