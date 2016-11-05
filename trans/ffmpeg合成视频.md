#需求

#问题

##升级到最新版本

旧的版本（ubuntu apt-get 安装的是2.5.10）不支持hstack和vstack，升级到最新的3.0就完美了，不过需要去下载最新的版本，然后make-。-

#命令

##视频统一格式

ffmpeg -i demo.flv -s 320*240 demo.mp4

##将四个视频按照横竖摆放好

ffmpeg -i demo.flv -i demo.flv -i demo.flv -i demo.flv -filter_complex "[0:v][1:v]hstack[top];[2:v][3:v]hstack[bottom];[top][bottom]vstack" out.mp4

##合成视频并显示音频

ffmpeg -i t1.mp4 -i t2.mp4 -i s1.mp4 -i s2.mp4 -filter_complex "[0:v][1:v]hstack[top];[2:v][3:v]hstack[bottom];[top][bottom]vstack" -filter_complex amix=inputs=2:duration=first:dropout_transition=2,volume=4 out.mp4

##剪切任意时间开始多长时间的视频

ffmpeg -i demo.mp4 -ss 00:30:20 -t 00:00:01 -vcodec copy blank.mp4

##拼接视频

ffmpeg -i concat:"step1.flv|step2.flv" -c copy out.mp4
