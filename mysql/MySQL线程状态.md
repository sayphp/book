以下列表描述了与常规查询处理关联的线程状态值，而不是更复杂的特定活动。其中许多仅用于查找服务器中的错误。

| 状态值                         | 描述                                                         |
| ------------------------------ | ------------------------------------------------------------ |
| after create                   | 创建表函数的末尾创建表时                                     |
| analyzing                      | 正在计算MyISAM表键分布                                       |
| checking permissions           | 检查服务器是否具有执行语句所需的权限                         |
| checking table                 | 执行表格检查操作                                             |
| cleaning up                    | 已经处理完一个命令并准备释放内存并重置某些状态变量           |
| closing tables                 | 正在讲更改后的表数据刷新到磁盘并关闭使用的表。这应该是一个快速的操作。如果出现，请确认磁盘是否已满或被占用 |
| converting HEAP to MyISAM      | 磁盘正在将内部临时表从MEMORY表转换为磁盘上的MyISAM表         |
| copy to tmp table              | 正在处理修改表结构语句（ALTER TABLE）。这种状态发生在创建新结构的表之后，但在数据被复制到其中之前 |
| copying to group table         | 如果一个语句具有不同的order by和group by条件，则这些行按组进行排序并复制到一个临时表中 |
| copying to tmp table           | 服务器正在复制到内存中的临时表                               |
| copying to tmp table on disk   | 服务器正在复制到磁盘上的临时表。临时表结果集变得过大。因此县城正在讲临时表从内存更改为基于磁盘的格式一节省内存。 |
| creating index                 | 正在处理MyISAM引擎表的alter table enable keys                |
| creating sort index            | 正在处理使用内部临时表解析的SELECT                           |
| creating table                 | 正在创建一个表。这包括创建临时表                             |
| creating tmp table             | 正在内存或磁盘上创建一个临时表。如果表在内存中创建，但稍后转换为磁盘上的表，则该操作期间的状态将复制到磁盘上的临时表 |
| deleting from main table       | 服务器正在执行多表删除的第一部分。它仅从第一个表中删除，并保存用于其他（引用）表中删除的列和偏移量 |
| deleting from reference tables | 服务器正在执行多表删除的第二部分，并从其他表中删除匹配的行。 |
| discard_or_import_tablespace   | 正在处理alter table ……discard tablespace或alter table ……import tablespace语句 |

[1.]: https://dev.mysql.com/doc/refman/5.5/en/general-thread-states.html	"MySQL 5.5线程状态官方文档"

