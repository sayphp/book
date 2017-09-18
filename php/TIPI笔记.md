#目录

[TOC]

# 1 基本原理

## 1.1 准备工作和背景知识

###1.1.1 环境搭建

1. 获取PHP源码（github获取）

2. 准备编译环境（linux环境，clang、autoconf、flex、lex、yacc、bison等的安装）

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

### 1.2.3 PHP脚本的执行

脚本执行有Zend引擎来完成。

由**解释器**执行这些源码，实际上还是会经过编译环节，只不过他们一般会在运行的时候实时进行编译。

#### 词法分析和语言法分析

编程语言的编译器（compiler）或解释器（interpreter）一版包括两大部分：

* 读取源程序，并处理语言结构
* 处理与眼界够并生成目标程序


##### Lex和Yacc的作用(或者flex->re2c/bison,PHP用的这个,当年还被坑过)

1. 将代码切分为一个个的标记(token)
2. 处理程序的层级结构

##### Lex/Flex

Lex读取词法规则文件，生成词法分析器。

```flex
定义段
%%
规则段
%%
用户代码段
```

#####Yacc/Bison	

#### opcode

opcode是计算机指令中的一部分，用户指定要执行的操作，指令的格式和规范又出力气的指令规范指定。

##### PHP的opcode

PHP的opcode是Zend虚拟机中的指令

```c
struct _zend_op{
    opcode_handler_t handler;//执行该opcode时调用的处理函数
  	znode result;
  	znode op1;
  	znode op2;
  	ulong exnteded_value;
  	unit lineno;
  	zend_uchar opcode;//opcode代码
}
```





#### opcode处理函数查找

## 1.3 变量及数据类型

### 1.3.1 变量的结构和类型

### 1.3.2 常量

### 1.3.3 预定义变量

### 1.3.4 静态变量

### 1.3.5 类型提示的实现

### 1.3.6 变量的生命周期

### 1.3.7 数据类型转换

## 1.4 函数的实现

## 1.5 类和面向对象

## 1.6 内存管理

## 1.7 Zend虚拟机

### 1.7.1 Zend虚拟机概述

Zend虚拟机在本质上是抽象的计算机。在某终较底层的语言上抽象出另外一种语言，有自己的指令集，有自己的内存管理体系。

#### 虚拟机的类型

* 基于栈的Stack Machines：操作数保存在栈上，JVM、HHVM
* 基于累加器 的Accumulator Machines：使用累加器寄存器来报存一个操作数以及操作的结果
* 基于通用寄存器的General-Purpose-Register Machines：操作数保存在这些没有特殊用的寄存器中，Zend VM

#### Zend虚拟机核心实现代码

1. PHP语法实现
   * Zend/zend_language_scanner.l
   * Zend/zend_language_parser.y
2. Opcode编译
   * Zend/zend_compile.c
3. 执行引擎
   * Zend/zend_vm_*
   * Zend/zend_execute.c

####Zend虚拟机体系结构

- 解释层（词法分析、语法分析、编译生成中间代码）
- 中间数据层（中间代码、PHP自带函数列表、用户自定义的函数列表、PHP自带的类、用户自定义的类）
- 执行引擎

### 1.7.2 语法的实现

#### 词法解析

re2c的应用，核心代码在Zend/zend_language_scanner.c

#### 语法分析

bison是一种通用目的的分析器生成器，	

#### 实现自己的语法

```bison
%{
这里可以用来定义在动作中使用类型和变量，或者使用预处理器命令在那里来定义宏, 或者使用#include包含需要的文件。
如在示例中我们声明了YYSTYPE，包含了头文件math.h等，还声明了词法分析器yylex和错误打印程序yyerror。
%}
 
Bison 的一些声明
在这里声明终结符和非终结符以及操作符的优先级和各种符号语义值的各种类型
如示例中的%token　NUM。我们在PHP的源码中可以看到更多的类型和符号声明，如%left，%right的使用
 
%%
在这里定义如何从每一个非终结符的部分构建其整体的语法规则。
%%
 
这里存放附加的内容
这里就比较自由了，你可以放任何你想放的代码。
在开始声明的函数，如yylex等，经常是在这里实现的，我们的示例就是这么搞的。
```



## 1.8 线程安全

## 1.9 错误和异常处理

## 1.10 输出缓冲



> **注意**
>
> TIPI的后半部分基本为空，只留下了一些章节标题，暂时先把前10章的内容完全阅读，然后结合《Extending and Embedding PHP》一书及walu（现在好像叫寸谋了）的blog来补全整个PHP的内容吧。而且因为本身是PHP5的内容，现在PHP已经到了7.2的版本了。在自己大致吃透这些内容后，不排除自己做一个PHP7的cookbook的可能性

# 2 扩展开发及实践

## 2.11 扩展开发

## 2.12 文件和流

## 2.13 网络编程

## 2.14 配置文件

## 2.15 开发实例

### 2.15.1 opcode缓存扩展

### 2.15.2 性能监控及优化扩展

### 2.15.3 扩展PHP语法，为PHP增加语法特性



# 3 Better Explain

## 3.16 PHP与亚呢性的实现

## 3.17 PHP性功能

## 3.18 CPHP以外：PHP编译器

## 3.19 PHP个版本中的那些变动及优化

## 3.20 怎样系列（Guides：how to *）

# 4 深入阅读

[1]: https://www.php-internals.com/book/?p=chapt02/02-02-03-fastcgi "FastCGI"
[2]: https://www.php-internals.com/book/?p=chapt02/02-02-02-embedding-php "嵌入式"
[3]: https://www.php-internals.com/book/?p=chapt02/02-02-01-apache-php-module "apache2handler"
[4]: http://pecl.php.net/package/vld "Opcode"
[5]: https://www.ibm.com/developerworks/cn/linux/sdk/lex/index.html "Yacc与Lex快速入门"

