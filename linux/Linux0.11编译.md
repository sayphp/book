## 环境

内核：Linux 3.19.0-26-generic

位数：x86_64

协议：GNU/Linux

发行版本：Ubuntu 15.04

## 问题

## make: as86：命令未找到

这个问题是as86汇编器未安装，所以我们需要安装一下就可以解决了

Ubuntu下
	
	sudo apt-get install bin86

CentOS下
	
	sudo yum install dev86

## 衍生问题

由于as86不支持/**/所需要把源代码中注释改为！ 

毕竟是老问题，对此我们也只能默默地解决掉他

## make: gas：命令未找到

这次安装了一下binutils,结果发现已经安装过了工具，在查了下资料，发现时gas、gld的名称已经过时了，现在GNU assembler的名称是as

这个时候就要修改Makefile文件
	
	AS =gas//修改为as
	LD =gld//修改为ld

## Error: unsupported instruction

一大串的不支持错误提示，这是由于64位机器上编译的元婴，需要告诉编译器，我们要编译32位的code，修改Makefile吧
	
	AS = as --32//添加--32以按照32位编译

## Error: alignment not a power of 2

align 2 是汇编语言指示符，其含义是指存储边界对齐调整；

“2”表示把随后的代码或数据的偏移位置调整到地址值最后2比特位为零的位置（2^2），即按4字节对齐内存地址。

不过现在GNU as直接是写出对齐的值而非2的次方值了。
	
	.align 2//2修改为4(2^2)
	.align 3//3修改为8(2^3)

## gcc: error: unrecognized command line option ‘-fcombine-regs’



## gcc: error: unrecognized command line option ‘-mstring-insns’



终于，gcc报错了，查了一下，发现是因为现在的gcc不支持这两个参数造成的，果断删除这两个参数

## In file included from init/main.c:8:0:

	In file included from init/main.c:8:0:
	include/unistd.h:207:1: warning: function return types not compatible due to ‘volatile’
	 volatile void exit(int status);
	 ^
	include/unistd.h:207:1: warning: function return types not compatible due to ‘volatile’
	include/unistd.h:208:1: warning: function return types not compatible due to ‘volatile’
	 volatile void _exit(int status);
	 ^
	include/unistd.h:208:1: warning: function return types not compatible due to ‘volatile’
	In file included from init/main.c:9:0:
	include/time.h:39:8: warning: conflicting types for built-in function ‘strftime’
	 size_t strftime(char * s, size_t smax, const char * fmt, const struct tm * tp);
		    ^
	In file included from init/main.c:8:0:
	init/main.c:23:29: error: static declaration of ‘fork’ follows non-static declaration
	 static inline _syscall0(int,fork)
	                         ^
	include/unistd.h:134:6: note: in definition of macro ‘_syscall0’
	 type name(void) \
		  ^
	include/unistd.h:210:5: note: previous declaration of ‘fork’ was here
	 int fork(void);
		 ^
	init/main.c:24:29: error: static declaration of ‘pause’ follows non-static declaration
	 static inline _syscall0(int,pause)
		                         ^
	include/unistd.h:134:6: note: in definition of macro ‘_syscall0’
	 type name(void) \
	      ^
	include/unistd.h:224:5: note: previous declaration of ‘pause’ was here
	 int pause(void);
	     ^
	……

编队这一大堆让人头晕的编译错误，是由于include/unistd.h 中声明了一次pause() sync() fork(), 而在main.c 中通过宏又定义了这三个函数，但定义时多了static 限定，与声明不同，所以出错。所以直接把unistd.h中的声明去掉。

## warning: function return types not compatible due to 'volatile'

静态方法声明问题，需要修改init/main.c文件

## 然后莫名其妙的发现，我的kernel编程linux0.11的了，然后一堆bug，好吧，未完待续

