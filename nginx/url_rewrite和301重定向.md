# Nginx的URL重写（伪静态）和301重定向

由于业务需求，需要对已经进行伪静态的页面进行重定向，以保证之前被百度大人收录的PHP页面重定向到伪静态的HTML页面。

# 关于重写

简单说下url rewrite

在apache中也存在类似的重写，格式贴近与xml；而nginx则更贴近与json

加上万能的正则表达式，我们就可以完成URL重写与301重定向了

# 重写日志

```nginx
#开启冲写日志，并设定日志位置及记录级别notice
rewrite_log on;
error_log /home/weblogs/nginx_rewrite.log notice;
```

# 正则匹配

* ～大小写敏感匹配
* !~ 大小写敏感不匹配
* ~* 大小写不敏感匹配
* !~* 大小写不敏感不匹配
* -f 用来判断

# flag标记

1. last 相当于Apache的[L]标记，表示完成rewrite
2. break 终止匹配，不在匹配后面的规则
3. redirect 返回302临时重定向，地址栏会显示跳转后的地址
4. permanent 返回301永久重定向,地址栏会显示跳转后的地址

# if语法

```nginx
location / {
    #注意if判断的空格，缺少了会报错误
    if ( $document_uri != '/index.php' ) {
        #重写（内部重定向）到index.php下，并将uri作为参数传递
        rewrite '^/(.*)$' /index.php?$1;
        #使用try_files,达到指向效果
        try_files $uri /index.php?$1;
    }
}
```

# try_files语法

按顺序检查文件是否存在，返回第一个找到的文件。如果所有的文件都找不到，会进行一个内部重定向到最后一个参数。

# 全局变量

| 变量                               |  版本   | 说明                                   |
| :------------------------------- | :---: | :----------------------------------- |
| $args                            | 1.0.8 | 请求中的参数                               |
| $binary_remote_addr              | 1.0.8 | 远程地址的二进制表示                           |
| $body_bytes_sents                | 1.0.8 | 已发送的消息体字节数                           |
| $content_length                  | 1.0.8 | HTTP请求信息里的“Content-Type”             |
| $document_root                   | 1.0.8 | 远程地址的二进制表示                           |
| $document_uri | 1.0.8 | 与$uri相同  |       |                                      |
| $host                            | 1.0.8 | 请求信息中的“host”，如果请求中没有host行，则等于设置的服务器名 |
| $hostname                        | 1.0.8 |                                      |
| $http_cookie                     | 1.0.8 | cookie信息                             |
| $http_post                       | 1.0.8 |                                      |
| $http_referer                    | 1.0.8 | 引用地址                                 |
| $http_user_agent                 | 1.0.8 | 客户端代理信息                              |
| $http_via                        | 1.0.8 | 最后一个访问服务器的IP地址                       |
| $http_x_forwarded_for            | 1.0.8 | 相当于网络访问路径                            |
| $is_args                         | 1.0.8 |                                      |
| $limit_rate                      | 1.0.8 | 对连接速率的限制                             |
| $nginx_version                   | 1.0.8 |                                      |
| $pid                             | 1.0.8 |                                      |
| $query_string | 1.0.8 | 与$args相同 |       |                                      |
| $realpath_root                   | 1.0.8 |                                      |
