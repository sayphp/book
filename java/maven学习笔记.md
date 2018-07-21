## 安装Maven

```shell
#安装maven
sudo apt-get install maven
```

##使用Maven创建项目

```shell
#使用maven安装目录在com.say的名为credit的项目
mvn archetype:generate -DgroupId=com.say -DartifactId=credit -DarchetypeArtifactId=maven-archetype-quickstart -DinterativeMode=false
#然后会提示输入version，无特殊情况直接输入Y即可
```

## 通过Maven打包

```shell
mvn package
```

## 执行jar包

```shell
java -cp test.jar com.say.App #常规Java代码
java -jar test.jar #springboot框架下代码执行
```

