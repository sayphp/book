# MySQL集群搭建及实验

> 随着用户量的逐渐增大，单机的MySQL已经没有办法应付日渐高涨的数据读写压力了，数据库的主从和读写分离，以及横向、纵向拆分已经是未来必须要做的事情了。怎么做效率更高？怎么做对系统来说是最优解？这些都是需要进行测试及实验的。

## 集群搭建

###1. 安装mysql，通常来说安装mysql-server即可，但是为了使用方便，我们把client也一并进行安装

```shell
yum install mysql
```

### 2. 查看mysql配置文件位置

```shell
locate my.cnf
```

### 3.编辑mysql配置内容

```shell
vim /etc/my.cnf
```

#### master配置

```shell
[mysqld]
#在mysql的下方追加信息
#1.开启binlog
log-bin=/var/log/mysqld/mysql-bin
log-bin-index=/var/log/mysqld/mysql-bin.index
#2.设置server-id
server-id=62
#3.绑定IP地址
bind-address=192.168.1.62
binlog-do-db=test
```

> **注意**
>
> 在开启binlog的时候，如果log-bin使用了绝对路径，那么一定要追加log-bin-index，不然无法开启binlog
>
> 同时，由于MySQL5.7默认binlog使用row格式，所以不额外进行配置

#### master复制帐号开启

```mysql
#1.创建从库复制（replication）帐号
create user 'test'@'192.168.1.63' identified by '123456';
#设置帐号、主机（IP）、密码
#2.给用户test赋予从库复制权限
grant replication slave on *.* to 'test'@'192.168.1.63';
#3.查看用户
select  user,host from mysql.user;
#4.修改IP段
update mysql.user set host='192.168.1.%' where user = 'test';
#同一账号在多从库复制时使用，多帐号可返回1创建帐号
```

#### master检查

```mysql
#1.binlog是否开启
show variables like "%log_bin%";
+---------------------------------+---------------------------------+
| Variable_name                   | Value                           |
+---------------------------------+---------------------------------+
| log_bin                         | ON                              |
| log_bin_basename                | /var/log/mysqld/mysql-bin       |
| log_bin_index                   | /var/log/mysqld/mysql-bin.index |
| log_bin_trust_function_creators | OFF                             |
| log_bin_use_v1_row_events       | OFF                             |
| sql_log_bin                     | ON                              |
+---------------------------------+---------------------------------+
#PS:mysqld目录如果没有写权限也是会报错的，这里主要依赖journalctl -xe、/var/log/mysqld.log来分析问题
#sudo chmod -R 777 /var/log/mysqld
#2.master状态检查
show master status;
+------------------+----------+--------------+------------------+-------------------+
| File             | Position | Binlog_Do_DB | Binlog_Ignore_DB | Executed_Gtid_Set |
+------------------+----------+--------------+------------------+-------------------+
| mysql-bin.000001 |      154 | test         |                  |                   |
+------------------+----------+--------------+------------------+-------------------+
#返回文件(File)及偏移量(Position),这两个数据后面要用到
```

#### slave配置

```shell
[mysqld]
#在mysql的下方追加信息
#1.开启binlog
log-bin=/var/log/mysqld/mysql-bin
log-bin-index=/var/log/mysqld/mysql-bin.index
server-id=64
#2.设置server-id
bind-address=192.168.1.63
#3.绑定IP地址
replicate-do-db=test
```

#### slave建立链接

```mysql
change master to master_host='192.168.1.62', master_user='test', master_password='123456', master_log_file='mysql-bin000001',master_log_pos=154;
#注意file、pos的值取自master status
start slave;
show slave status\G;
#为了方便查看使用\G
```

### 4.修改UUID

```shell
#因为使用yum安装，相同镜像，所以各台服务器安装的mysql的uuid是一样的，uuid相同无法进行复制，所以需要修改一下
#1.auto.cnf位置
locate auto.cnf
#2.修改UUID的值
vim auto.cnf
#server-uuid=0e8b921a-b86b-11e6-8e8a-005056b67f22
```

### 5.关闭防火墙策略

```shell
#iptables默认策略不开放3306端口，如果不关闭的话，会提示2003(113)
#防火墙是双向的，需要把主从的防火墙都关闭，不然会报1130
service iptables save
service iptables restart
```

### 6.数据同步测试

1. 暂停主从复制stop slave
2. 在主库、从库创建数据库test
3. 回复主从复制start slave
4. 在主库进行操作（创建表、增删数据等）

> **第一部分总结**
>
> 总体来说，1主2从的简单集群就这样搭建完成了，遇到了一些问题，但是通过百度就可以轻松解决，甚至不需要Google。

## 实验

### 1.压力测试

### 2.读写分离



