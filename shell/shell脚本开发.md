作为一名需要在Linux的派生版本下工作的程序猿，shell的使用还是相当频繁的。

除了正常使用的ln、grep、touch等等shell命令，其实shell是可以作为一门脚本语言来使用的。

当然，秉承着PHP是最好的语言原则，一直以来都没有自己去将逻辑判断等东西写到shell脚本中，更多的是将通过PHP进行判断。

但是随着技术提升，思想改变，任何语言都有其优势，PHP也不是万能的，所以在项目开发中，加入纯shell脚本，作为程序的守护进程。

##shell文件开头
	
	#!/bin/sh//#!符号告诉系统他后面的程序使用来执行
	/bin/sh test.sh//正常情况下我们要这么执行shell脚本
	./test.sh//现在可以这样执行了，注意提升下脚本的权限
	
##sheel注释
	
	#//shell通过#注释

##shell变量
	
	a="This is Say\'s blog"//shell脚本的变量不需要提前声明，所有变量都是字符串，直接赋值使用
	echo "Test shell script! ${a}"//输出

##shell管道

	//将一个命令的输出作为另外一个命令的输入
	grep "hello" file.txt | wc -l
	
##shell重定向
	
	//将命令的结果输出到文件，而不是标准输出（屏幕）
	>//写入文件并覆盖旧文件
	>>//加到文件的尾部，保留旧文件内容
	`//可以将一个命令的输出作为另外一个命令的一个命令行参数

##IF
	
	流控制语句
	if [ $a = $b ];then
		echo "a等于b"
	elif [ $a != $c ];then
		echo "a等于c"
	else
		echo "a不等于b或c"
	fi

一些其他的用法（测试命令）
	
	[-f "somefile"]//判断是否是一个文件
	[-x "/bin/ls"]//判断是否存在并有可执行权限
	[-n "$var"]//判断是否有值
	man test//查看更多
	
##TEST比较模式

今天运行自己的shell守护进程，发现提示错误。百度之后，发现问题出在比较模式上

习惯性的会使用>、<、<=、>=来进行比较，后来发现，比较数字，需要使用其他的写法，如下
	
	$a -eq $b//a等于b
	$a -ge $b//a大于等于b
	$a -gt $b//a大于b
	$a -le $b//a小于等于b
	$a -lt $b//a小于b
	$a -ne $b//a不等于b

##shell快捷操作
	
	与（&&）、或（||） 
	
##CASE

可以用来匹配一个给定的字符串，而不是数字
	
	case ${i} in
	${a})
	echo "在变量a内";;
	${b})
	echo "在变量b内";;
	esac

##SELECT

是一种bash的扩展应用，尤其擅长于交互式使用。用户可以从一组不同的值中进行选择
	
	select var in ... ; do
	break 
	done
 
##WHILE

	while ...; do 
	.... 
	done 

##FOR

	for var in ....; do
	... 
	done

##函数

	test(){
		echo "调用test方法"
	}

##$\*
	
	$*表示所有传入的参数

##总结

	很简单，以上的各种语法、用法略加组合应用，就可以轻松地写出简单的守护进程了:)
