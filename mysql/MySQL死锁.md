##死锁的定义

死锁是指两个或者多个事务在同一资源上相互占用，并请求锁定对方占用的资源，从而导致恶性循环的现象。

##死锁的产生

当多个事务试图以不同的顺序锁定资源时，就可能会产生死锁。

比方说用户下单后完成支付这个场景，我们需要修改用户余额、修改订单状态、修改支付单状态、增加收支单(此处暂时省略)：

```mysql
#事务1(旧版本支付回调处理)
	start transaction;
	update `user` set `user_balance` = 500 where `user_id` = 1 and `user_balance` = 1000;
	update `order` set `order_status` = 3 where `order_id` = 1 and `order_status` = 2;
	update `pay` set `pay_status` = 3 where `pay_id` = 1 and `pay_status` = 2;
	commit;
#事务2(新版本支付回调处理)
	start transaction;
	update `order` set `order_status` = 3 where `order_id` = 1 and `order_status` = 2;
	update `user` set `user_balance` = 500 where `user_id` = 1 and `user_balance` = 1000;
	update `pay` set `pay_status` = 3 where `pay_id` = 1 and `pay_status` = 2;
	commit;
#事务3(订单过期服务)
	start transaction;
	update `pay` set `pay_status` = 4 where `pay_id` = 1 and `pay_status` = 2;
	update `order` set `order_status` = 4 where `order_id` = 1 and `order_status` = 2;
	commit;
#A.事务1 vs 事务2
#并发时，1先锁住了user::user_id=1那一行，2先锁住了order::order_id=1那一行,然后双方互相等对方释放锁
#B.事务1 vs 事务3
#界点时，事务1锁住了order::order_id=1那一行，事务3锁住了pay::pay_id=1那一行，然后双方互相等待对方释放锁
```

在这个场景中，用户采用余额+第三方支付的形式完成了一笔订单交易，支付完成是以第三方回调通知成功为触发的，而在通知的过程中，由于各种问题，会出现多条同时回调的情况(反正**微信**使出过这种情况，**支付宝**不记得了-。-)，这种情况下就会出现并发情况。

当然，由于代码是固定的，所以一般不会出现事务执行顺序不一致的情况，但是，当有两个版本的回调服务同时生效(**事务1**和**事务2**通常不是由同一个人，同一时间段开发的)，或者一些服务在执行的界点的时候(**事务3**的订单过期服务)，乱序就会产生，从而产生死锁。

锁的行为和顺序是和存储引擎相关的。以同样的顺序执行语句，有些存储引擎会产生死锁，有些则不会。思索的产生有双重原因：有些事因为真正的数据冲突，这种情况通常很难避免，但有些择完全是由于存储引擎的实现方式导致的。

## 死锁的解决

为了解决这种问题，数据库系统实现了各种死锁检测和死锁超时机制。越复杂的系统，比如InnoDB存储引擎，越能检测到思索的循环依赖，并立即返回一个错误。这种解决方式很有效，否则死锁会导致出现非常慢的查询。

InnoDB目前处理死锁的方法是，将持有最少行及排它锁的事务进行回滚。

架构师在技术选型的时候，需要选择更靠谱的数据库（万能的MySQL，万能的InnoDB），新技术尝试要谨慎；而开发人员在写业务的时候也要注意保持事务顺序性，尽量减少事务冲突，同时在程序设计的时候必需考虑如何处理死锁。（简单粗暴的来讲，当事务1 vs 事务3的时候，事务3将会被回滚，事务1执行成功，当我们再次重试事务3的时候，发现无法执行过期，毕竟**状态**发生了改变嘛）



