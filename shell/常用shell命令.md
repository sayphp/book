#常用命令

记录一些开发中比较常用的shell命令，不需要每次都百度。

##查询某个目录下，某个关键词所在的文件

	
	find /var/www/php/ -name "*.php" | xargs grep "keyword"



