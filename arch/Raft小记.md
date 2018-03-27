# 什么是Raft

Raft是一个旨在易于理解的共识算法，容错与性能和Paxos相当。区别在于，Raft被分解为相对独立的子问题，它清楚地解决了实际系统所需的主要部分。

# 什么是共识

共识是容错分布式系统的一个基本问题。共识涉及多个服务器价值达成一致。一旦他们做出关于价值的决定，那么这个决定就是最终的决定。当大多数服务器可用时，典型的一致性算法会取得进展；例如，即使2台服务器出现故障，5台服务器也可以继续运行。如果更多的服务器失败，他们会停止进展（但不会返回错误的结果）。

# Raft算法参考

* State
  * 所有服务器上的持久状态
    * currentTerm 当前任期（计数器，初始化为0）
    * voteedFor 现在接受投票的候选人编号
    * log[] 日志条目，每个条目包含状态机的命令
  * 所有服务器上的易失性状态
    * commitIndex 已知最高日志条目的索引
    * committed 初始化为0,单调递增
    * lastApplied 应用于状态的最高日志条目的索引
    * machine 初始化为0,单调递增
  * Leader服务器上的易失性状态（选举后重置）
    * nextIndex[] 遍历每台服务器，发送到该服务器的下一个日志条目的索引(初始化为leader最后的日志索引+1）
    * matchIndex[] 遍历每台服务器，已知在服务器上复制的最高日志条目的索引（初始化为0）
* Append Entries RPC
  * 参数
    * term Leader的任期
    * leaderId follower可重定向的Leader编号
    * prevLogIndex 前一个日志索引 
    * prevLogTerm 前一个日志任期
    * entries[]
    * leaderCommit leader的提交索引
  * 结果
    * term 当前任期，用于leader更新自己
    * success 如果follower包涵prevLogIndex和prevLogTerm匹配的项目，则返回true
  * 接收器实现
    * 如果term <currentTerm，则返回false
    * 如果日志不包含与prevLogTerm相匹配的prevLogIndex条目，则返回false
    * 如果现有条目与新条目（相同索引但任期不同）冲突，请删除现有条目及其后的所有条目
    * 添加日志中尚未包含的新条目
    * 如果leaderCommit > commitIndex，则设置commitIndex = min(leaderCommit, 最后一个心条目的索引)
* RequestVote RPC
  * 参数
    * term 候选人任期
    * candidateId 候选人编号
    * lastLogIndex 最后的日志条目
    * lastLogTerm 最后的候选人任期条目
  * 结果
    * term 让候选人更新自己
    * voteGranted 候选人收到了投票
  * 接收器实现
    * 如果term < currentTerm，则返回false
    * 如果vodedFor为null或candidateId，并且候选人的日志至少与接受者的日志一样最新，那么投票表决
* Rules for Servers
  * All 所有服务器
    * 如果commitIndex > lastApplied 增加lastApplied，则应用将[lastApplied]记录到状态机
    * 如果RPC请求或响应包含任期 T > currentTerm,则设置currentTerm = T,并转换成跟随者
  * Follower 跟随者
    * 回应候选人和领导的PRC
    * 如果没有受到当前领导者的追加条目RPC或给予候选人投票选举超时，则转换为候选人
  * Candidate 候选人
    * 转换为候选人后，开始选举
    * 递增currentTerm
    * 为自己投票
    * 重置选举计时器
    * 发送RequestVote RPC到所有其他服务器
    * 如果大多数服务器受到投票：成为领导者
    * 如果从新领导收到AppendEntries RPC：转换为跟随者
    * 如果选举超时已过：开始新的选举
  * Leader 领导
    * 选举后：想每台服务器发送初始空AppendEntries RPC（心跳）；在空闲期间重复一方之选举超时
    * 如果从客户端接受到的命令：将条目附加到本地日志，则在应用到状态机的条目之后惊醒响应
    * 如果追随者的最后日志索引>=nextIndex:发送带有从nextIndex开始的日志条目的AppendEntries RPC
    * 如果成功：为follower更新nextIndex和matchIndex
    * 如果AppendEntries RPC由于日志不一致而失败，则递减nextIndex并重试
    * 如果存在N，使得N>commitIndex，则大部分matchIndex[i]>=N,并且log[N].term == currentTerm，则设置commitIndex = N



# Raft关键属性

* 选举安全
* 领导者追加
* 日志匹配
* 领导者完整性
* 状态机安全性

# Raft相关

* 领导者选举过程中，为了防止分裂投票，选举超时是从固定的时间间隔中随机选择的（如150ms-300ms），减少再次分裂投票的可能性
* 选举之后，领导者要将日志同步到所有服务器，服务器失败则无限重试
* 广播时间应该比选举超时少一个数量级
* 选举超时应该比单台服务器平均鼓掌时间少几个数量级

# 相关资料

[1]: https://raft.github.io/	"Raft项目地址"
[2]: https://raft.github.io/raft.pdf	"Raft论文地址"
[3]: http://www.jdon.com/artichect/raft.html 	"Raft中文解析"