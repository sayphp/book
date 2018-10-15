#常用命令

记录一些开发中比较常用的shell命令，不需要每次都百度。

##查询某个目录下，某个关键词所在的文件


```shell
find /var/www/php/ -name "*.php" | xargs grep "keyword"
```
​	

##ubuntu下面查询某个软件的安装信息

```shell
dpkg -L [softname]
```

## 获取某个命令运行的返回状态

```shell
#第一个命令正确的时候返回0
ffmpeg -version && echo $?
#第一个命令错误的时候返回状态码 1-255
ffmpeg -v || echo $?
#第一个命令无论正确与否，返回状态码0或1-255
ffmpeg -version ; echo $?
#不输出内容，只返回状态码
ffmpeg -version &>/dev/null ; echo $?
```

##查找并替换文本内容

```shell
sed -i "s/abc/def/g" demo.txt
```

## 清空一个文件

```shell
> demo.txt
```

## 批量替换文件后缀

```shell
#将.conf后缀的文件名批量替换为.conf.bak
rename .conf .conf.bak *.conf
```

