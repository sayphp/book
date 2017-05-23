#什么是Docker

在LXC的基础上，Docker进一步优化了容器的使用体验。

Docker提供了各种容器管理工具(如分发、版本、移植等)让用户无需关注底层的操作，可以简单明了地管理和使用容器。

可以简单地将Docker容器理解为一种沙河（Sandbox）。

#Docker的优点

1. 更快速的交付和部署
2. 更高效的资源利用
3. 更轻松的迁移和扩展
4. 更简单的更新管理

#对比虚拟机

1. 更快
2. 对系统资源需求更少
3. 通过类似Git操作来方便用户获取、分发和更新应用镜像，指令简明，学习成本较低
4. 通过Dockerfile配置文件来支持灵活的自动化创建和部署机制，提高工作效率
5. Docker在隔离性上采用的是安全隔离而不是虚拟机的完全隔离（内存、磁盘）



#安装

##Docker官网

http://www.docker.com

##ubuntu获取地址：

http://www.docker.com/docker-ubuntu

##ubuntu

    
    sudo apt-get install docker.io

