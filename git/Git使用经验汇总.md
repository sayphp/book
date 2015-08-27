之前看过Git的书籍，也顺手注册了下GitHub这个网站的账户，看了下，甚至还克隆了下php的源代码开始研究。

不过，之后也不了了之了。今天算是抽出了些许时间，开始正式在Git上建立了自己的第一个项目。

熟练使用Git的同时，也同时完善自己的项目。

##Git提交流程
	
	git add//提交修改或添加
	git status//查看下提交状态
	git commit -m "加点注释什么的"//提交
	git push//把代码推送到远程服务器上面去
	git pull//从远程服务器拖取最新的代码
	
##Git误删问题
	
	git checkout [orgin develop要还原的分支] filename//直接checkout被删掉的文件，问题解决了

##Git分支操作
	
	git branch//查看本地分支
	git branch -a//查看所有分支
	git branch -d branchname//删除本地指定分支
	git checkout -b say master//创建新的分支
	git checkout branchname//切换分支
	git merge --no-ff say//将目标分支合并到当前分支
        
##获取Git无版本控制代码
	
	git archive --format=tar --output /var/www/a.tar master//讲主干分支归档到压缩文件中
	//然后很淡定的tar

其实吧，我觉得git中的文件复制粘贴到别的项目中，不会有什么影响，所以，可以直接在自己的git项目中cp非.git的所有目录和文件过去

