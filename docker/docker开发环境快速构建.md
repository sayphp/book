# Docker开发环境快速构建

> 开发机坏了，置换后基于MacBook进行快速构建，简单记录一下

## 1.下载Docker

前往官网[https://www.docker.com/get-started/](https://www.docker.com/get-started/)，根据自己机器型号选择下载软件，是个dmg

## 2.开发环境 ubuntu

在Docker中（[dockerhub](https://hub.docker.com/)）搜索 **ubuntu/nginx**,下载镜像（这个镜像具备nginx服务，不会启动后直接退出）

```shell
docker pull ubuntu/nginx
```
启动时镜像时请注意设置端口 + 磁盘映射

## 3.搭建改造

```shell
// 考虑到在国内，原始镜像源需要替换
sed -i 's/http:\/\/archive.ubuntu.com/http:\/\/mirrors.aliyun.com/g' /etc/apt/sources.list

// 更新升级
apt update
apt upgrade

apt --version

// 安装基础软件
apt install vim wget curl git

vim --version
wget --version
curl --version
git --version

// 安装编程语言环境 php python3
apt install php python3

php --version
python3 --version

// 安装go
// https://go.dev/doc/install

wget https://go.dev/dl/go1.21.3.linux-amd64.tar.gz

rm -rf /usr/local/go && tar -C /usr/local -xzf go1.21.3.linux-amd64.tar.gz

go version
```


## 文档信息

* 标签
    * docker
    * record
    * quick 
* 编辑时间：*2023-10-23 12:00:00*