# 前言

之前在项目中做过RSA加密相关的一些工作，自认为对于RSA加密、解密也算是熟悉了，公钥、私钥也都耍的有模有样。

然而，今天在同事处理对第三方的RSA加密的过程中，关于私钥加密->公钥解密和公钥加密->私钥解密产生了争论。

原本在我的印象中，公钥用于加密，私钥用于解密。

公钥被公开，而私钥自己保存，以保证安全性。而不对称加密中，私钥是A，公钥是B（AB的那个不对称加密理论）。

而在讨论中，得出了私钥可以用于加密，公钥用于解密的说法。

当时我的心里是觉得很可笑的，公钥用于解密，私钥的私密性算什么？？？

# 打脸

但是，实际代码测试显示，私钥加密，公钥解密，一切正常-。-

# 原理

我这爆脾气，果断各种百度、google，好吧，最后在知乎发现了想要的答案！居然是知乎！

很多解释都把RSA分为两类：

1. 加密/解密 公钥加密，私钥解密
2. 签名/验证 私钥加密，公钥解密

但是无论加密还是签名，说白了还是可以相互加解密。

无论公钥还是私钥本质上都是密钥，而在RSA中，公钥与私钥是对等的。所以，两者相互之间可以相互加解密。

而私钥是具有私密性的，公钥是开放给外部的。

所以，才会产生上面RSA的两类应用。

# 结论

最后，私钥和公钥是由私密性决定的，而不是由谁加密、谁解密决定的！

公钥、私钥可以相互加解密！ 

公钥可以用来加密、私钥进行解密;私钥可以用来进行加密、公钥进行解密（说成签名和验证也可以）。

本质上来说，是对一些概念的混淆和不求甚解导致了这个问题，不过遇到了，学习了，掌握了，就可以了。
