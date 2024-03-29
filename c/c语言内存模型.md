# 不明觉厉的C语言内存模型

第一次听到内存模型这个名词的时候先是一脸懵逼，紧接着跑去百度一番，才知道，这个内存模型，可以理解为C语言中内存分布、内促组织方式。

我们知道，C程序开发并编译完成后，要载入内存才能运行，变量名、函数名都会对应内存中的一块区域。

| 内存分区 | 说明 |
| ---- |:----|
| 程序代码区（code area） | 存放函数体的二进制代码 |
| 静态数据区（data area） | 也称全局数据区，包含的数据类型比较多，如全局变量、静态变量、一般常量、字符串常量。|
| 堆区（heap area）| 一般由程序员分配和释放，若程序员不释放，程序运行结束时由操作系统回收。malloc()、calloc()、free()等函数操作的就是这块内存，这也是本章要讲解的重点。 |
| 栈区（stack area） | 由系统自动分配释放，存放函数的参数值、局部变量的值等。其操作方式类似于数据结构中的栈。 |
| 命令行参数区 | 存放命令行参数和环境变量的值，如通过main()函数传递的值。 |

# 内存的规划种类

1. 常规内存（Conventional Memory）
2. 高位内存(Upper Memory)
3. 高端内存区(High Memory rea)
4. 扩展内存快(Extended Memory Block)

# 理解

了解计算机的都知道，计算机只能够识别0和1，也就是我们常说的二进制（我的理解是电的正负极）。

而人类常用的十进制可以和二进制相互转换（十六进制等也是一样的），计算机申请一段内存（说白了就是一堆正负极电路），然后将正负电极写入，然后借助逻辑运算、位运算得到想要的结果，将结果存储在寄存器上（当然，有多个寄存器）。

之前有一本日本的书叫机器是如何运行的，结合起来能够理解整个过程。

而C语言内存模型，其实可以说是摆放这些0、1的位置顺序的规则。

# 相关资料
1. http://c.biancheng.net/cpp/html/2857.html
2. https://my.oschina.net/pollybl1255/blog/140323
3. http://www.cnblogs.com/haore147/p/3921263.html