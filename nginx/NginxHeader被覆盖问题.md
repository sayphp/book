# Nginx Header 被覆盖问题

## 出题

在公司nginx+php技术栈中，为了支持全链路压测，需要让服务具备**全链路协议**以支持压测标记的全局传递，为了减少业务感知，在nginx层通过lua加入了协议维护能力，出于设计考量，使用了`x_ocs_proto_key`。

> 说明一下，nginx默认是不支持下划线“_”的，而仅仅只支持短横线“-”，所以这个协议是基于内网东西向进行传递，外网在进入网关（router、ingress）的时候，由于配置不支持，这个协议就会丢失。

所以我们会在东西向服务的nginx中配置开启下划线

```
underscores_in_headers on;
```

而QA团队在压测平台优化过程中，想要通过篡改协议的方式直接确认是全链路压测，而不是通过我们提供的默认能力参数：`is_navigator=1`

> 这里存在一个问题，这个协议的传递与维护是在业务维护的过载保护系统内的，虽然以sidecar形式进行了部署，但是仅限于售卖中台的php服务，在外部服务中没有办法提供支持，而全局又不存在链路染色等概念支持全链路压测，所以会出现这个偏差

而由于发压平台使用过南北网关进入服务的，下划线并不支持，所以他们使用了`x-ocs-proto-key`直接篡改了协议，且协议内容写错了，最终导致下游服务感知发生了偏差，导致了事故。


## 解体

> 早先遇到过协议不生效的情况，翻阅资料的时候，找到了nginx官方一篇最佳实践，感觉是很不错的归纳总结，记录一下

* [nginx配置 陷阱与常见错误](https://www.nginx.com/resources/wiki/start/topics/tutorials/config_pitfalls/)
* [github 关于覆盖问题的issue](https://github.com/cwaldbieser/jhub_remote_user_authenticator/issues/6)

而关于本次遇到的问题，属于开启下划线后引入的风险，在文档中有一段描述：

```
Missing (disappearing) HTTP Headers

If you do not explicitly set underscores_in_headers on;, NGINX will silently drop HTTP headers with underscores (which are perfectly valid according to the HTTP standard). This is done in order to prevent ambiguities when mapping headers to CGI variables as both dashes and underscores are mapped to underscores during that process.
```

核心原因其实就是nginx本身是识别两个键的，但是在向cgi映射的时候，cgi只接收一个，就会导致覆盖，也就是说，cgi只能感知到**x_ocs_proto_key**，而值在映射过程中`x-ocs-proto-key`会把`x_ocs_proto_key`覆盖掉。

> 至于为什么是短横线覆盖下划线，在问题复现过程中是可以明确验证的。

所以，解法层面，就比较简单粗暴了

```lua
ngx.req.set_header("x-ocs-proto-key", nil); -- 在进入access阶段的时候，直接将外部协议设置为nil
```
