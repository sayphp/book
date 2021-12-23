# 什么是#pragma

在所有的预处理指令中，#pragma指令可能是最复杂的了，它的作用是设定编译器的状态或者是指示编译器完成一些特定的动作。

**\#pragma** 指令对每个编译器给出了一个方法，在保持与C和C++语言完全兼容的情况下，给出主机或操作系统专有的特征。

依据定义，编译指示是机器或操作系统专有的，且对于每个编译器都是不同的。

其格式一般为：
​	
	#pragma para//para为参数

# 参数详解

## message

能够在编译信息输出窗口中输出相应的信息，对于源代码信息的控制非常重要。


```c
#ifdef ECHO
#pragma message("提示错误消息")
#endif
```

在编译的过程中会提示错误，但是不会影响变异生成结果，可以正常生成可执行文件。

##code_seg

该指令用来制定函数在.obj文件中存放的节


```c
void func1(){}//默认情况下，函数被存放在.text节中
#pragma code_seg(".my_data")
void func2(){}//讲函数存放在.my_data节中
#pragma code_seg(push, r1, ".my_data")
void func3(){}//以r1为表示，存在方.my_data节中
```

## once

保证头文件被编译过一次

## hdrstop

## warning

## comment

## pack


[参考资料](http://c.biancheng.net/cpp/html/469.html)
[参考资料](http://blog.csdn.net/whatday/article/details/7100855)
