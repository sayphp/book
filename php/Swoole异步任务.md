#前言

基于Swoole开发视频合成服务，服务分为两部分：

1. 自动根据当前时间，获取需要进行视频合成的课程视频，进行处理
2. 接受客户端的请求后，针对某节课程进行单独的视频合成处理

自动处理，通过swoole提供的task就可以很简单的达到我们的目的，只需要专注于业务流程编码即可。

而接受请求处理，属于二期的需求，需要提前预留可扩展，所以选择swoole_server而没有选择swoole_process。

#注意

在swoole的当前版本中（1.9.0），通过set配置task_worker_num来启动异步任务，异步任务必须要onTask，onFinish两个监听。

而worker与taskworker两者在本质上都是worker，在swoole启动的时候，都会触发onWorkerStart。

这里在实际使用过程中，如果没有针对性的进行处理，会被坑。

`    PHP Warning:  swoole_server::task(): The method can only be used in the worker process.`

造成这个错误的原因，就在于WorkerStart的回调方法中，没有区分是worker还是taskworker；而taskworker本身不能投递任务。

翻了一下资料，在群友的帮助下，找到了swoole_server::$taskworker。通过返回值可以确定当前的worker到底是不是处理任务的。
  
`   $serv = new swoole_server('127.0.0.1', '9053');
    $serv->set(array(
        'worker_num' => 1,
        'task_worker_num' => 4,
        'daemonize' => 1,
        'max_request' => 1000,
        'dispatch_mode' => 2,
        'debug_mode'=> 1,
    ));
    $serv->on('WorkerStart', 'start');//worker启动时触发回调，worker或taskworker都会触发
    $serv->on('Connect', 'conn');//有外部链接建立时触发
    $serv->on('Receive', 'receive');//回复时触发
    $serv->on('Close', 'close');//关闭连接时触发
    $serv->on('Task', 'task');//接收到投递任务时触发
    $serv->on('Finish', 'finish');//完成任务时触发
    $serv->start();//启动服务

	function start($serv, $worker_id){
		if($serv->taskworker){
			//任务处理器，不具备投递资格
		}else{
			$task_id = $serv->task('投递任务');
		}
	}
	
	function task($serv, $task_id, $from_id, $data){
		//处理任务
		$serv->finish('处理成功');//完成
	}
	//*其他代码省略`