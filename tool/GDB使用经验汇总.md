# GDB使用经验汇总

最近在编写PHP扩展，与之前的Demo不同，出错无法避免，gdb就成了调试扩展必不可少的工具了。针对PHP突然爆出一个“段错误”（segment failed），使用gdb可以很轻松的定义到错误所在，然后，我们只需要研究这一段的写法、用法即可。

```shell
#安装gdb
sudo apt-get install gdb
#查看gdb版本
gdb -v
#提示段错误后如果根目录没有core文件，需要打开
ulimit -c unlimited
#然后执行下脚本
php say.php
#ls之后会发现core文件，错误提示也变成了:段错误 (核心已转储)
gdb php -c core
run say.php
#然后只需要阅读gdb输出的核心信息就可以了-。-
```



[1.]: http://www.gnu.org/software/gdb/	"gdb官网"

