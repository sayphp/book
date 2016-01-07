#基于HLS的音频点播服务

##惯例吐槽

因为业务的需要,需要一套音频点播服务,要快

恩-.-

然后,经过一天的翻文档,编译,调试总算是把这个服务折腾出来了

于是,有了这篇文章,记录在整个过程中所遇到的各种问题

##基础需求

1. 音频时长长于30分钟
2. 用户可在iOS,Android,html5,微信多端点播
3. 音频文件需要切片
4. 音频为公开课录音

##简单设计

###server

1. 提供一个独立的服务
2. 管理员上传MP3音频文件
3. 服务将mp3转码成aac格式(包装为ts后缀)
4. 切片音频文件
5. 生成m3u8文档
6. 将mp3文件,切片ts文件,m3u8根据公开课业务编号上传到阿里云OSS运存储


###client

1. 客户端通过HLS获取m3u8
2. 通过html5的audio标签,提供播放,暂停功能
3. 包装一下样式,-.-

##技术难点

1. 音频文件转码
2. 音频文件切分
3. m3u8播放兼容

##名词解析

###HLS

HTTP Live Streaming

苹果公司实现的基于HTTP流媒体通信协议.

包括一个m3u(8)的索引文件,ts媒体分片文件和key加密串文件.

hls官方草案:

https://tools.ietf.org/html/draft-pantos-http-live-streaming-06

###m3u(8)

m3u是多媒体播放列表的计算及文件格式.

可以理解为一个索引文件,存储音视频列表(m3u)或者音视频.

m3u本身还有一些很强大的功能,比方说重载,适配,通过这种方式可以进行音视频直播,视频自适应.

utf8编码的版本被称为m3u8

###ts

MPEG transport stream

一个音视频传输的标准容器格式

可以理解为比方你的音频格式是mp3,而外面用ts包装,在传输的时候使用:)

###ffmpeg

一款处理多媒体数据的软件.

依赖于libavcodec,libavformat,libavutil

称它为音视频转码神器应该不为过吧-.-

推酷的ffmpeg帮助文档

http://www.tuicool.com/articles/nquMZv

###m3u8-segmenter

m3u8分段器,可以直接将ts文件根据时间长度进行切分,并且生成m3u8文件,就是编译坑了点

相关git地址:

https://github.com/johnf/m3u8-segmenter

###h264

是一个视频编码格式,是目前针对视频记录,压缩,分配最常用的一个.

###aac

高级音频编码,基于mp3的升级版本(但是他是有损压缩-.-)

###html5 audio

html5的音频标签,在src里面直接设置m3u8的URI即可.

经过测试iOS,Android4.0+,微信webview基本上是能够满足我们的需求的,而我本机的chromium(ubuntu)很不幸的没有办法支持这种方式.

PS:

这里尝试了两种兼容方式(本质上是一种)

1.使用jwplayer播放器来进行播放(很不幸,免费版的jwplayer是不支持m3u8的,所以土豪的选择-.-)

2.自己借助flash做到浏览器兼容,支持m3u8的,这个实现需要时间,我可耻的放弃了

我尝试的方式是webview或浏览形式,如果原生iOS和Android有相关的组件

##采坑之旅

###audio标签和jwplayer

如果从网上搜索资料,会发现各种谈论html5的audio标签和video标签是不支持m3u8的这种播放列表方式的.

所以,转而翻墙去下载了最新版本的jwplayer代码,设置了key,初始化了playlist,然后各种调试不通,百度,google,才发现免费版的jwplayer不支持m3u8,这个天坑(当然由于jwplayer的官方论坛实在是太卡了,我才有先用的百度)

这里需要说明一点:

####在苹果熊的safari上,html5的audiio标签和video标签是支持m3u8的.

以测试和探索为目的,不过分考虑兼容性的情况下,可以直接通过iOS或者MacOS直接使用html5的标签载入m3u8来验证你的文档是否正确

`
	<audio type="application/vnd.apple.mpegurl" src="http://m3u8.s.cn/demo.m3u8" controls></audio>
`

###m3u8文件

m3u8是一个索引文件,但是他很强大,具备各种各样的功能,而很多范例给出的都是视频文件的案例,还有递增编号等等,会让人产生混乱.

而其实我们要做的功能暂时只需要一个很轻型,很简单m3u8文档

`
	#EXTM3U//m3u8标头,必需
	#EXT-X-TARGETDURATION:10//每个切片的长度(秒数)
	#EXTINF:10,//当下这个片的大小
	http://m3u8.s.cn/data/1.ts//该片的ts文件uri
	#EXTINF:10,
	http://m3u8.s.cn/data/2.ts
	#EXTINF:10,
	http://m3u8.s.cn/data/3.ts
	#EXT-X-ENDLIST//列表结束标签,必需
`

通过这个文档我们就可以很轻松构造一个播放列表了

###ffmpeg的编译

因为是在ubuntu下,所以只需

`
    apt-get install ffmpeg
`

就可以完成安装,但是之所以需要单独说一下,是因为在实际开发过程中需要编译.

apt提供的软件包中不包括aac,而hls支持的视频内容为h264,音频内容为aac,所以,想要正常转码mp3到aac格式,需要编译安装ffmpeg,并且保证enable了aac

当然,重新编译实在过于费事,机智如我,选择了将mp3转码为h264.

但是生产环境下,还是要准备好aac的编译安装

###m3u8分段器的编译

m3u8-segmenter本身是从git上面直接clone下来,然后按照一些帖子去编译,然而,我们大家都被骗了,那个编译会出现各种坑,根本无法编译成功.(当然,或许是我能力有限)

经历各种折腾,终于发现了,只需要通过gcc编译 m3u8-segmenter.c文件即可

`
gcc -Wall -g m3u8-segmenter.c -o segmenter -lavformat -lavcodec -lavutil
`
###实际执行过程中的注意事项

工欲善其事,必先利其器

基本工具搭建完成后,我们需要做的事情是按照设计的步骤完成转码,分片,生成m3u8这个步骤

`
	//*进入存放demo音频文件的目录
	
	cd /say/data
	
	//*使用ffmpeg转码(操作文档详细可以man),生成ts文件
	
	ffmpeg -i demo.mp3 -acodec copy -vcodec libx264 demo.ts
	
	//*使用m3u8-segmenter进行分片并生成m3u8索引文件
	
	/say/m3u8-segmenter/segmenter -i demo.ts -d 10 -p demo -m demo.m3u8 -u http://m3u8.s.cn/data/
	
	//-i 输入文件
	
	//-d 切片时间(秒)
	
	//-p 切片文件前缀
	
	//-m 生成的索引文件
	
	//-u 索引文件url前缀
`

##收尾

核心的播放功能和音频转码功能完成了,接下来需要做的就是通过OSS将生成的文件上传到云存储,这里面根据公开课业务编号设置唯一的音频m3u8路径和调用oss授权上传接口即可完成了:)
