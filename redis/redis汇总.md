# Redis汇总

Redis是一个开源的使用ANSI C语言编写、支持网络、可基于内存亦可持久化的日志型、Key-Value数据库，并提供多种语言的API。

在日常工作中：Redis最大的作用就是缓存；其次，无论是做TOP100、服务限流、秒杀活动等业务甚至简单消息队列，Redis简直是大利器。

想要了解Redis的内容：

[1]: https://redis.io/	"Redis官网"
[2]: https://redis.io/commands	"Redis命令（API）"
[3]: http://try.redis.io/	"Redis在线体验"
[4]: https://github.com/antirez/redis	"Redis源码"

Redis固然好用，但是滥用还是会出现问题的，随着对Redis的依赖日渐严重，而大量第三方的调用接入，大面积提升了API的并发请求数，突然发现1%的请求会产生read error on connection的错误，于是开始排查问题。细节不一一细表，直接讨论结论：

Redis是单线程的，无论是慢查询、AOF重写都会影响其性能；虽然看似高效，但是在“粗心”程序员下意识的使用中，可能会让Redis处在悬崖边缘。（这个时候，下意识的想到了“雪崩”-。-）Redis很快，想要慢查询却很简单，keys *就可以简单造成。

> 这里检讨一下，为了应对两个版本服务框架的缓存系统兼容，同时简化开发人员对缓存的操作，老版本框架内部大量使用了keys方法查询并批量删除key；而正则匹配复杂度O(n)也让Redis慢查询达到30ms，在大量请求进入，同时服务RDB和AOF的情况下，大量报出timeout错误。

发现了问题所在，应对措施也就相对简单直接一些了。

* 关闭AOF（因为大多数是缓存数据，RDB已经足够了）
* 关闭服务监控（早前使用缓存额外做了一份Redis的接口临时调用监控，对Redis造成了额外的负担，暂时关闭，等到将本质问题解决后，再开启）
* 老版本服务框架缓存清楚策略（大面积使用keys，造成大量的慢查询，这里由于内部人员问题，处理起来并不简单，暂时的方案是迁移部分大并发量接口到新版，然后逐步规范化，清除老的缓存策略；新的缓存机制也要更加规范化、精确化-。-）
* 基于Redis主从，进行keys \*查询分离（执行keys \*命令的Redis查询走从库，常规读写走主库）

> 基于Redis主从的解决方案，可以有效的让主库不再处理慢查询，提升性能。但是每次请求无形中增加了一次数据库链接，变相增加了服务的维护成本。

关于Redis问题的内容有很多，网上找到了一些资料：

[1]: https://www.oschina.net/translate/redis-latency-problems-troubleshooting?lang=chs	"redis延迟问题排查"
[2]: http://carlosfu.iteye.com/blog/2254154	"美团在Redis上踩过的一些坑"
[3]: https://www.cnblogs.com/me115/p/5032177.html	"Redis延时问题分析及应对"
[4]: https://blog.csdn.net/moxiaomomo/article/details/21368945	"Redis 3.0源码目录"
[5]: https://www.cnblogs.com/mushroom/p/4738170.html	"Redis性能问题排查解决手册"
[6]: https://www.cnblogs.com/chimeiwangliang/p/7776968.html	"Redis慢查询"
[7]: https://www.cnblogs.com/SailorXiao/p/5808871.html	"Redis SlowLog"

