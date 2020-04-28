# 简介

这是一个基于 [Hyperf](https://hyperf.io) 框架的应用脚手架程序。

# 必要环境

 - PHP >= 7.2
 - Swoole PHP extension >= 4.4，and Disabled `Short Name`
 - OpenSSL PHP extension
 - JSON PHP extension
 - PDO PHP extension （If you need to use MySQL Client）
 - Redis PHP extension （If you need to use Redis Client）
 - Protobuf PHP extension （If you need to use gRPC Server of Client）

# Composer 安装

```bash
composer create-project daosoft/hyperf
```

# Supervisor 部署

[Supervisor](http://www.supervisord.org/) 是 `Linux/Unix` 系统下的一个进程管理工具。可以很方便的监听、启动、停止和重启一个或多个进程。通过 [Supervisor](http://www.supervisord.org/) 管理的进程，当进程意外被 `Kill` 时，[Supervisor](http://www.supervisord.org/) 会自动将它重启，可以很方便地做到进程自动恢复的目的，而无需自己编写 `shell` 脚本来管理进程。

## 安装 Supervisor

这里仅举例 `CentOS` 系统下的安装方式：

```bash
# 安装 epel 源，如果此前安装过，此步骤跳过
yum install -y epel-release
yum install -y supervisor
```

## 创建一个配置文件

创建新的配置文件 `/etc/supervisord.d/hyperf.ini`，并在文件中添加以下内容后保存文件：

```ini
# 新建一个应用并设置一个名称，这里设置为 hyperf
[program:hyperf]
# 设置命令在指定的目录内执行
directory=/mnt/www/hyperf/
# 这里为您要管理的项目的启动命令
command=php artisan start
# 以哪个用户来运行该进程
user=root
# supervisor 启动时自动该应用
autostart=true
# 进程退出后自动重启进程
autorestart=true
# 进程持续运行多久才认为是启动成功
startsecs=1
# 重试次数
startretries=3
# stderr 日志输出位置
stderr_logfile=/mnt/www/hyperf/runtime/stderr.log
# stdout 日志输出位置
stdout_logfile=/mnt/www/hyperf/runtime/stdout.log
```

## 启动 Supervisor

运行下面的命令基于配置文件启动 Supervisor 程序：

```bash
supervisord -c /etc/supervisord.conf
```

## 使用 supervisorctl 管理项目

```bash
# 启动 hyperf 应用
supervisorctl start hyperf
# 重启 hyperf 应用
supervisorctl restart hyperf
# 停止 hyperf 应用
supervisorctl stop hyperf  
# 查看所有被管理项目运行状态
supervisorctl status
# 重新加载配置文件
supervisorctl update
# 重新启动所有程序
supervisorctl reload
```

# Nginx 反向代理

[Nginx](http://nginx.org/) 是一个高性能的 `HTTP` 和反向代理服务器，代码完全用 `C` 实现，基于它的高性能以及诸多优点，我们可以把它设置为 `hyperf` 的前置服务器，实现负载均衡或 HTTPS 前置服务器等。

## 配置 Http 代理

```nginx
# 至少需要一个 Hyperf 节点，多个配置多行
upstream swoole {
    # Hyperf HTTP Server 的 IP 及 端口
    server 127.0.0.1:9501;
    server 127.0.0.1:9502;
}

server {
    # 监听端口
    listen 80; 
    # 绑定的域名，填写您的域名
    server_name proxy.hyperf.io;
    # 静态文件目录
    root /mnt/www/hyperf/public;

    location / {
        try_files $uri @hyperf;
    }

    location @hyperf {
        # 将客户端的 Host 和 IP 信息一并转发到对应节点  
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Real-PORT $remote_port;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;

        proxy_set_header Scheme $scheme;
        proxy_set_header Server-Protocol $server_protocol;
        proxy_set_header Server-Name $server_name;
        proxy_set_header Server-Addr $server_addr;
        proxy_set_header Server-Port $server_port;

        # 转发Cookie，设置 SameSite
        proxy_cookie_path / "/; secure; HttpOnly; SameSite=strict";

        # 执行代理访问真实服务器
        proxy_pass http://swoole;
    }
}
```

## 配置 Websocket 代理

```nginx
# 至少需要一个 Hyperf 节点，多个配置多行
upstream websocket {
    # 设置负载均衡模式为 IP Hash 算法模式，这样不同的客户端每次请求都会与同一节点进行交互
    ip_hash;
    # Hyperf WebSocket Server 的 IP 及 端口
    server 127.0.0.1:9503;
    server 127.0.0.1:9504;
}

server {
    listen 80;
    server_name websocket.hyperf.io;

    location / {
        # WebSocket Header
        proxy_http_version 1.1;
        proxy_set_header Upgrade websocket;
        proxy_set_header Connection "Upgrade";

        # 将客户端的 Host 和 IP 信息一并转发到对应节点  
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header Host $http_host;

        # 客户端与服务端无交互 60s 后自动断开连接，请根据实际业务场景设置
        proxy_read_timeout 60s;

        # 执行代理访问真实服务器
        proxy_pass http://websocket;
    }
}
```
