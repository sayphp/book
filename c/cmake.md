# 简介

CMake是一个跨平台的安装 (编译)工具，可以用简单的语句来描述左右平台的安装（编译过程）他能够输出各种各样的makefile或project文件，能测试编译器所支持的C++的特性，类似UNIX下的automake。

简单来说，在编译项目的时候，会用到很多第三方库和自己编写的工具库，在这个时候，我们通常链接这些库编译到我们的项目中

比方说:
​	
```shell
gcc test.c -lm -I/mylib/tool -o test.o
```
​	

当项目较小的时候，我们可能只需要链接简单的几个文件，而当项目越来越庞大之后，大量的库文件并不便于记忆，这个时候，我们就需要通过维护makefile来管理这些库。

# 对比

## 优点

1. 开源代码,使用类BSD许可发布
2. 跨平台(linux/unix makefile, mac xcode, windows msvc)
3. 能够管理大型项目
4. 简化编译构建过程和编译过程，只需要cmake+make就可以
5. 高效率
6. 可扩展

PHP本身的编译使用的是automake，而选择cmake的原因起始很简单——简单。

而cmake本身不是标准的GNU工具，PHP选择automake的原因大部分取决于时间，那个时候cmake是啥？另一个是automake属于GNU。

# 用法

CMake的所有的语句都写在一个教CMakeLists.txt的文件中。

当CMakeLists.txt文件确定后我们只需要输入
​	
```shell
ccmake /say/project//配置编译选项，一般不需要
cmake /say/project//根据cmakelists.txt生成Makefile
make//执行makefile文件，编译程序，生成可执行文件
```

# 难点

使用起来命令很简单，然而难点就在于CMakeLists.txt的内容
​	
```shell
cmake_minimum_required(VERSION 3.7.2)#指定cmake需要的最小版本
project(say)#指定项目名
aux_source_directory(/ say/c)#获取指定目录下的所有文件
add_executable(ccc jump.c)#将指定文件编译成可执行文件
#add_library(lib test)
message("say!!!")#输出内容
#持续试验补完
```

# 相关资料

1. http://blog.csdn.net/u012150179/article/details/17852273
2. https://my.oschina.net/u/1046919/blog/477645

