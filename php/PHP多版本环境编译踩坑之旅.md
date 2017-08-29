#概述

重装完开发机系统之后，ubuntu16.10的自带PHP版本默认为7，平时自己在github上面拉下来了PHP-SRC的源代码作为扩展开发的源码环境，同时也方便平时查阅源代码。

由于需要介入Discuz开发，万恶的mysql扩展在php7中被取消掉了，所以只能使用php5的版本来进行支持。

开发环境是标准的lnmp，php7+phpfpm，所以php5想要使用fastcgi来进行。

#问题

然而，apt-get里面没有php5的版本，本地编译自然是最快的，所以很自然的切了个php5的分支（5.6.25）直接开始编译安装

#然后

## 总是提示找不到一些PHP扩展的、甚至zend扩展的方法缺失

再反复出错，Google无果之后,直接查找了master分支对应扩展(为了方便对比，直接在GitHub上面查看)，再对比本地的PHP-5.6.25分支，果然发现这些报错的文件都是在PHP7新版本中加入的，而PHP5的分支中并不存在。

看尿性，编译是按照PHP7来执行的，而不是PHP5。

再在./configure后，git diff一下，发现了/php-src/main/php_version.h总是在./configure后被改变了这一现象(由5.6.25变成了7.2.0)，根据这个怪异的现象进行了搜索，依旧没有结果，但是可以知道，我的分支checkout到了5.6.25,configure后版本不应该改变才对。

这个诡异的现象背后，可能就是问题的真正原因。

这时候回顾一下编译的前置，php编译需要基于bison、flex、autoconf等软件（记得以前看到过相关的帖子，但是一时间没有找到）。

而在./configure之前，我们还需在根目录autoconf -> ./buildconf (类似于扩展编译前phpize)

果然，增加了这两步之后，PHP的版本信息果然没有再被重写。继续……

## 编译失败，Zend核心直接上百行报错

这个一眼望去就头大了，Google、百度甚至搜狗都上了-。#，然并软……

折腾了很久，最后无奈地发现，是git里面的分支根本没办法编译成功，在官网上下载了PHP-5.6.29的源码，果断安装成功。

瞬间泪流满面……

#结语

思考一番，最终还是决定把这个多版本编译的坑爹经历记录下来，说不定，在世界的另一个角落，也会有遇到这个问题的战友呢：）

#补充

##Unable to find the wrapper "https"

缺少openssl库,php没法愉快的发送https请求了,使用apt-get打包开发版即可解决

##make: *** [sapi/cli/php] Error 1

```shell
/usr/bin/ld: ext/ldap/.libs/ldap.o: undefined reference to symbol 'ber_scanf'
/usr/bin/ld: note: 'ber_scanf' is defined in DSO /lib64/liblber-2.4.so.2 so try adding it to the linker command line /lib64/liblber-2.4.so.2: could not read symbols: Invalid operation
collect2: ld returned 1 exit status
make: *** [sapi/cli/php] Error 1 
```


这个问题有两部分，

第一部类似其他的找不到库，直接打包liblber-dev即可，但同时由于ubuntu将库存放在x86_64的目录下，需要使用ln做一个链接到/usr/lib下面去

再次编译，会出现另外一个问题，解决方案是打开MakeFile,找到EXTRA_LIBS,加上'-llber'即可。