> **前言**
>
> 最近又要开始维护Java的积分服务了，索性接着这个机会将Java语法层面的东西做一下整理与记录，算是一个深入学习的机会吧。

## Java包

```java
package com.sanhao.test;
//声明当前包，即位置com/sanhao/test
import com.sanhao.test.config.TestConfig;
//引入com/sanhao/test/config下的TestConfig类
public class Test{
    public void main(){
    	System.out.println("Hello World!");  
    }
}
```

[1.]: http://www.runoob.com/java/java-package.html	"Java包"

## Jar包和War包

简单来说，jar包是java打得包，只有编译后的class和一些部署文件。war包是javaweb打得包，是一个完整的javaweb项目，tomcat可以自动解压、编译，包括class、依赖的包、配置文件、所有网站页面（包括jsp、html）