#目录

[TOC]

# 1 基本原理

## 1.1 准备工作和背景知识

###1.1.1 环境搭建

1. 获取PHP源码（github获取）

2. 准备编译环境（linux环境，clang、autoconf、flex等的安装）

3. 编译（autoconf ./buildconf ./configure make makeinstall）

### 1.1.2 源码结构、阅读代码方法

####PHP源码目录结构

* /                          根目录，一些readme
* build                  放置源码编译相关文件
* ext                     官方扩展目录，包括绝大多数PHP的**函数**的定义和实现
* main                  PHP核心文件，PHP的基础设施
* Zend                  Zend引擎的实现目录，词法语法，opcode及扩展机制实现
* pear                   PHP扩展与应用仓库，包含pear核心文件
* sapi                    包含各种服务器抽象层的代码，apache的mod_php,cgi,fpm
* TSRM                 PHP线程安全资源管理器
* tests                   PHP的测试脚本集合 run-test.php及test目录下文件，苦逼的时候可以看看熟悉的PHP代码
* win32                 Windows平台相关实现

#### PHP源码阅读工具

* vim+ctags
* IDE查看代码 （可以使用clion-。-）

###1.1.3 常用代码

```c
//1."##"及"#"
//##双井号 链接符 当作一个代码生成器，减少代码密度，一种代码重用手段
//#单井号 预处理运算符，将其后面的宏参数进行字符串化操作

//2. do{}while(0)
#define ALLOC_ZVAL(z)                             \
do{                                               \
    (z) = (zval*)emalloc(sizeof(zval_gc_info));   \
}while(0)
//这种用法，防止简写代码无法通过编译，例如:
#define T(a, b) a++;b++;
if(1) T(a, b);
else printf("else\r\n");
//会报编译错误，而使用do{}while(0)则可以有效防止
//除此之外，还可以用于定义空操作
#ifdef DEBUG
	#define BUG() do_anything()
#else
	#define BUG() do{}while(0)
#endif

//3.#line预处理
#line 838 "Zend/zend_language_scanner.c"
//保证文件名及行号固定，防止中间文件，导致查询不到文件

//4.PHP中的全局变量宏
// /main/php_globals.h
struct _php_core_globals{};
```

## 1.2 用户代码的执行

1. 输入编写程序
2. 词法分析
3. 语法分析
4. Zend Engine执行
5. 输出内容

### 1.2.1 生命周期和Zend引擎

#### 一切的开始SAPI

SAPI（Server Applicaion Programming Interface）指的是PHP具体应用的编程接口，封装了一层，让PHP层面可以使用统一的接口在不同平台上达到一致的效果。

#### 开始和结束

1. MINIT 模块初始化阶段
2. RINIT 模块激活阶段（请求开始）
3. 执行PHP程序
4. RSHUTDOWN 模块请求结束阶段（请求关闭）
5. MSHUTDOWN 模块技术阶段

#### 单进程SAPI生命周期

1. **启动**
   1. 初始化若干全局变量
   2. 初始化若干常量
   3. 初始化Zend引擎和核心组件
   4. 解析php.ini
   5. 全局操作函数的初始化
   6. 初始化静态构建的模块和共享模块（MINIT）
   7. 禁用函数和类
2. **ACTIVATION**
   1. 激活Zend引擎
   2. 激活SAPI（/main/php_content_types.c）
   3. 环境初始化（/main/php_variables.c）
   4. 模块请求初始化
3. **运行**
   1. 词法分析
   2. 语法分析
   3. 中间代码生成
   4. zend_execute执行中间代码
   5. EG()返回结果
4. **DEACTIVATION**
   1. 调用所有通过register_shutdown_function()注册的函数
   2. 执行所有可用的__destruct函数
   3. 将所有的输出刷除去
   4. 发送HTTP应答头
   5. 便利每个模块的关闭请求方法
   6. 销毁全局变量表的变量
   7. 通过zend_deactivate函数,关闭词法分析其、语法分析器和中间代码执行器
   8. 调用每个扩展的post-RSHUTDOWN函数
   9. 关闭SAPI
   10. 关闭流的包装器、关闭流的过滤器
   11. 关闭内存管理
   12. 重型设置最大执行时间
5. **结束**
   1. flush
   2. 关闭Zend引擎

#### 多进程SAPI声明周期

#### 多线程的SAPI生命周期

#### Zend引擎

Zend引擎是PHP实现的核心，提供了语言实现上的基础设施。

### 1.2.2 SAPI概述

这个目录存放了PHP对各个服务器抽象层的代码， 例如命令行程序的实现，Apache的mod_php模块实现以及fastcgi的实现等等。

在各个服务器抽象层之间遵守着相同的约定，这里我们称之为SAPI接口。 每个SAPI实现都是一个_sapi_module_struct结构体变量。（SAPI接口）。

在PHP7中，大部分的SAPI已经被清理，只保留常用的

/main/SAPI.c

/main/SAPI.h

* apache2handler
* embed
* cgi(fastcgi)
* fpm
* cli

# 扩展开发及实践

# Better Explain

# 参考

[1.]: https://www.php-internals.com/book/?p=chapt02/02-02-03-fastcgi "FastCGI"
[2.]: https://www.php-internals.com/book/?p=chapt02/02-02-02-embedding-php "嵌入式"
[3.]: https://www.php-internals.com/book/?p=chapt02/02-02-01-apache-php-module "apache2handler"

