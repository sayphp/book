# 什么是Docker

在LXC的基础上，Docker进一步优化了容器的使用体验。

Docker提供了各种容器管理工具(如分发、版本、移植等)让用户无需关注底层的操作，可以简单明了地管理和使用容器。

可以简单地将Docker容器理解为一种沙盒（Sandbox）。

# Docker的优点

1. 更快速的交付和部署
2. 更高效的资源利用
3. 更轻松的迁移和扩展
4. 更简单的更新管理

# 对比虚拟机

1. 更快
2. 对系统资源需求更少
3. 通过类似Git操作来方便用户获取、分发和更新应用镜像，指令简明，学习成本较低
4. 通过Dockerfile配置文件来支持灵活的自动化创建和部署机制，提高工作效率
5. Docker在隔离性上采用的是安全隔离而不是虚拟机的完全隔离（内存、磁盘）



# 安装

##Docker官网

http://www.docker.com

##ubuntu获取地址：

http://www.docker.com/docker-ubuntu

##ubuntu


    sudo apt-get install docker.io

# 常用命令

## host

```shell
//默认
hub.docker.com

//国内
dl.dockerpool:5000/
```

## 测试

```shell
//hello world 测试
sudo docker run hello-world
```

## 镜像

```shell
//搜索镜像
sudo docker search ubuntu

//下载镜像
sudo docker pull ubuntu

//删除镜像
sudo docker rmi ubuntu//标签模式
sudo docker rmi fe7c4bd//镜像ID模式

//查看镜像信息
sudo docker images

//添加标签
sudo  docker tag hub.sayphp.cn:5000/ubuntu:latest ubuntu:latest

//镜像详情
sudo docker inspect fe7c4bd

//创建镜像
sudo  docker commit -m "添加一个新的文件" -a "TEST" fe7c4bd test abdfg123adasdaeasd

//上传镜像
sudo docker push sayphp/lnmp:latest
```

## 容器

```shell
//创建容器
sudo docker create -it ubuntu:latest adasd131huida12

//启动容器
sudo docker start adasd131huida12

//重启容器
sudo docker restart adasd131huida12

//终止容器
sudo docker stop adasd131huida12

//容器状态
sudo docker ps -a

//运行容器
sudo docker run -it ubuntu /bin/bash

//删除容器
sudo docker rm adasd131huida12

//容器日志
sudo docker logs adasd131huida12

//进入容器
sudo docker exec -it adasd131huida12 /bin/bash
```

