##MySQL安装、卸载

Ubuntu下
```shell
sudo apt-get install mysql-server//安装
sudo apt-get autoremove mysql-server//卸载
```

##MySQL管理

MacOS
```shell
sudo /Library/StartupItems/MySQLCOM/MySQLCOM start//启动
sudo /Library/StartupItems/MySQLCOM/MySQLCOM stop//停止
sudo /Library/StartupItems/MySQLCOM/MySQLCOM restart//重启
```

Ubuntu
```shell
//init.d方式
sudo /etc/init.d/mysql start//启动
sudo /etc/init.d/mysql stop//停止
sudo /etc/init.d/mysql restart//重启
//startup方式
sudo start mysql//启动
sudo stop mysql//停止
sudo restart mysql//重启
//service方式
sudo service mysql start//启动
sudo service mysql stop//停止
sudo service mysql restart//重启
```

#MySQL操作                                                                                             

```mysql
mysql -u root -p//登录MySQL,提示输入Password
status;//查看服务器状态
show databases;//查询数据库列表
use `dbname`;//选择数据库
show tables;//查看数据库下的所有表
//查询表结构
describe table;
desc table;
show columns from table;
reset query cache;//清除查询缓存，虽然很多时候会关闭缓存
```

#MySQL BINGLOG

```shell
mysqlbinlog mysql-bin.000001 > test01.log//MySQL binlog二进制转文本，文件普遍偏大，VIM打开很吃力
```

# SQL

```sql
--1.SQL占位符使用，如下SQL，_下划线代表的是匹配任何A*B，如ACB、ADB开头内容
select * from `test` where `test_content` like 'A_B%';
--TODO:经测试，性能表现很诡异，有时间需要了解下like的查询机制相关
```

