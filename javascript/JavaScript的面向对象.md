##类

函数即为类的定义的实现​
```javascript
function MyClass(name,age){//废话少说，上代码
    this.name = name;
    this.age = age;
    this.toString() = function(){
        alert(this.name + ":" + this.age);
    };
};
//*prototype
MyClass.prototype = {
    sayHelllo:function(){
         alert(this.name + ",你好！");
    }
};
//*使用时
var cls1 = new MyClass("Say",25);
cls1.toString();
```

##静态类

无需实例化

```javascript
var StaticClass = function(){}
StaticClass.name = "Say";
StaticClass.Sum = function(val1,val2){
    return val1 + val2;
};
//*使用时
alert(StaticClass.name);
```

##继承

神奇的apply函数
```javascript
function PeopleClass(){this.type = "人";};//人类
PeopleClass.prototype = {
	getType:function(){alert("这是一个人");}
};
function StudentClass(name,sex){
	PeopleClass.apply(this,arguments);//apply
	this.name = name;
	this.sex = sex;
}
//实例化
var stu = new StudentClass("Say","男");
alert(stu.type);
```
