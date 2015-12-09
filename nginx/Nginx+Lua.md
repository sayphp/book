#Nginx+Lua

##始于需求

前不久,高可用架构微信群进行了一场OpenResty的专场分享.虽然因为工作原因错过了直播,但是通过后续的文章了解到了一些新的东西

最近手头上刚好有一个首页静态化的工作,想到分站指向页面的处理,如果能够直接在nginx上处理,会是一个不错的方式,便和运维商量了一下,打算做一个nginx+lua的尝试

##启动环境

OpenResty是一个nginx和它的各种第三方模块的打包而成的软件平台

Nginx通过加载模块的方式,可以支持lua,从而通过lua脚本进行web开发(赞美lua,最早知道他是来自于魔兽世界的插件开发)

###安装依赖

安装OpenResty首先需要安装一些依赖的库,包括readline ncurses5 pcre3 ssl perl

ubuntu或centos下的apt-get或yum的库的名字有细微区别,自己蛋疼,编译安装也ok,直接安装就好

###安装ngx_openresty

本来想从github上下载,不过貌似失败了,老老实实的参照官网的介绍,下载tar,然后解压,make

因为要使用lua进行web编程还需要真是IP,configure的时候注意增加--with-luajit --with-http_realip_module

我比较不爽unbuntu的配置方式,所以--prefix=/usr/local/ngx_openresty

去对应目录下,会发现有一些lualib的目录,看来是安装成功了

###配置开发环境

在这里要吐槽一句,因为我之前安装过nginx所以没有注意,先通过 nginx -V查看一下自己的nginx都在入了什么模块,其他的不在乎,lua的模块一定要有,如果没有,可以选择编译安装,我比较偷懒,直接apt-get了nginx-common nginx-full

总之,确认自己的nginx具备lua模块后,开始配置服务

http部分,我们要加载lua的模块路径,分为两种c的和lua的,我看了下自己的OpenResty,只有.C,但是都需要配置一下,说不定啥时候就要用到了呢


`
	//;分割多个模块路径,;;表示默认搜索路径(想知道我的nginx默认路径,不告诉-.-)
    lua_package_path "/usr/local/ngx_openresty/lualib/?.lua;;";//lua模块
	lua_package_cpath "/usr/local/ngx_openresty/lualib/?.c;;";//C模块
`

然后测试一下是否配置成功

`
	nginx -t
`

成功之后我们就可以开始lua变成之旅了,但是呢,我们总不能把代码写到配置文件里面吧,感觉怪怪的.所以呢,我么要陪只一下

`
	location / {
		default_type 'text/html;charset=utf-8';//我是utf8党,不要问我为什么
		lua_code_cache off;//如果不关掉缓存的话,只能不停的restart服务器了,关闭缓存之后,哇哦,lua变身php,可以直接开发内容了
		content_by_lua_file /var/www/lua/test.lua;//我的开发环境lua测试文件
	}
`

然后我们就可以在/var/www/lua/test.lua里面编写一些hello world之类的基础输出了.
