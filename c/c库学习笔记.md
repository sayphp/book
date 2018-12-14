# C库学习笔记

> 抽出些许时间，打算用C写一些小东西，一是练习下久违的C，二是写一些高性能的东西，玩儿一下底层的东西。

```shell
#库文件存储目录
/usr/include
```

## socket库

```c
//*ipv4结构
struct  sockaddr_in{
    unsigned short int sin_family;//协议簇
 	unsigned short int sin_port;//端口
    struct in_addr{
        unsigned int s_addr;//IP
    } sin_addr;
    unsigned char sin_zero[sizeof(struct sockaddr) - sizeof(unsigned short int) - sizeof(unsigned short int) - sizeof(struct in_addr)];
};
```

## 判定字符串是否为空

```c
char *s;
s = malloc(50*sizeof(char));
//1.使用string.h strcmp函数判定字符和""的区别，0为一样，即为空
if(strcmp(s, "")==0){
    printf("字符串s为空\n");
}
//2.检查s的第一个字符是否为0
if(s[0]==0){
    printf("字符串s为空\n");
}
//3.检查*s是否为空（指向第一个字符）
if(*s==0){
    printf("字符串s为空\n");
}
```

