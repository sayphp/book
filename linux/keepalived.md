#是什么

keepalived是集群管理中保证集群高可用的一个服务软件,期功能类似于heartbeat,用来防止单点故障.

#工作原理

keepalived是以VRRP协议为实现基础的,[VVRP][remark-vrrp]全称是Virtual Router Redundancy Protocol,即虚拟路由冗余协议.

虚拟路由冗余协议，可以认为是实现路由器高可用的协议，即将N台提供相同功能的路由器组成一个路由器组，这个组里面有一个master和多个backup，master上面有一个对外提供服务的vip（该路由器所在局域网内其他机器的默认路由为该vip），master会发组播，当backup收不到vrrp包时就认为master宕掉了，这时就需要根据VRRP的优先级来选举一个backup当master。这样的话就可以保证路由器的高可用了。

keepalived主要有三个模块，分别是core、check和vrrp。core模块为keepalived的核心，负责主进程的启动、维护以及全局配置文件的加载和解析。check负责健康检查，包括常见的各种检查方式。vrrp模块是来实现VRRP协议的。

#配置文件

keepalived只有一个配置文件keepalived.conf,里面主要包括几个配置区域,分别是:

1. global_defs 配置故障发生时的通知对象及其机器标示
2. static_ipaddress 配置本节点的IP信息
3. static_routes 配置本节点的路由信息
4. vrrp_script 用来做健康检查
5. vrrp_instance 用来定义对外提供 服务的VIP区域及其相关属性
6. vrrp_sync_group 用来定义vrrp_instance组,使得这个组内成员动作一致 
7. virtual_server 虚拟服务器配置信息





[remark-lvs]:http://www.linuxvirtualserver.org/ "LVS"
[remark-vrrp]:http://www.cnblogs.com/yechuan/archive/2012/04/17/2453707.html "VRRP介绍"
