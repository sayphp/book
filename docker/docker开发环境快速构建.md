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

# apt 2.4.10 (amd64

// 安装基础软件
apt install vim wget curl git

vim --version
# VIM - Vi IMproved 8.2 (2019 Dec 12, compiled Oct 06 2023 07:49:43)
wget --version
# GNU Wget 1.21.2 built on linux-gnu
curl --version
# curl 7.81.0 (x86_64-pc-linux-gnu) libcurl/7.81.0 OpenSSL/3.0.2 zlib/1.2.11 brotli/1.0.9 zstd/1.4.8 libidn2/2.3.2 libpsl/0.21.0 (+libidn2/2.3.2) libssh/0.9.6/openssl/zlib nghttp2/1.43.0 librtmp/2.3 OpenLDAP/2.5.16
git --version
# git version 2.34.1

// 安装编程语言环境 php python3
apt install php python3

php --version
# PHP 8.1.2-1ubuntu2.14 (cli) (built: Aug 18 2023 11:41:11) (NTS)
# Copyright (c) The PHP Group
# Zend Engine v4.1.2, Copyright (c) Zend Technologies
#    with Zend OPcache v8.1.2-1ubuntu2.14, Copyright (c), by Zend Technologies
python3 --version
# Python 3.10.12

// 安装go
// https://go.dev/doc/install

wget https://go.dev/dl/go1.21.3.linux-amd64.tar.gz

rm -rf /usr/local/go && tar -C /usr/local -xzf go1.21.3.linux-amd64.tar.gz

export PATH=$PATH:/usr/local/go/bin

go version
# go version go1.21.3 linux/amd64

# go mod环境代理变更
go env -w GO111MODULE=on
go env -w GOPROXY=https://goproxy.cn,direct


# lua openresty
# 安装准备
apt install --no-install-recommends wget gnupg ca-certificates
# 设置openresty仓库
wget -O - https://openresty.org/package/pubkey.gpg | apt-key add -
# 安装openresty
echo "deb http://openresty.org/package/ubuntu focal main" | tee /etc/apt/sources.list.d/openresty.list

apt update

apt install openresty

```


## 文档信息

* 标签
    * docker
    * record
    * quick 
* 编辑时间：*2023-10-23 12:00:00*