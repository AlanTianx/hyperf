# 简介

这是一个基于 [Hyperf](https://hyperf.io) 框架的应用脚手架程序。

# 服务器要求

Hyperf 对系统环境有一些要求，仅可运行于 Linux 和 Mac 环境下。当您不想采用 Docker 来作为运行的环境基础时，您需要确保您的运行环境达到了以下的要求：   

 - PHP >= 7.2
 - Swoole PHP 扩展 >= 4.4，并关闭了 `Short Name`
 - OpenSSL PHP 扩展
 - JSON PHP 扩展
 - PDO PHP 扩展 （如需要使用到 MySQL 客户端）
 - Redis PHP 扩展 （如需要使用到 Redis 客户端）
 - Protobuf PHP 扩展 （如需要使用到 gRPC 服务端或客户端）

# Composer 安装

```bash
composer create-project daosoft/scaffold
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

# Docker 下开发

假设您的本机环境并不能达到 Hyperf 的环境要求，或对于环境配置不是那么熟悉，那么您可以通过以下方法来运行及开发 Hyperf 项目：

```bash
# 下载并运行 hyperf/hyperf 镜像，并将镜像内的项目目录绑定到宿主机的 /tmp/hyperf 目录
docker run -v /tmp/hyperf:/hyperf -p 9501:9501 -it --entrypoint /bin/sh hyperf/hyperf:7.2-alpine-cli

# 镜像容器运行后，在容器内安装 Composer
wget https://mirrors.aliyun.com/composer/composer.phar
chmod u+x composer.phar
mv composer.phar /usr/local/bin/composer
# 将 Composer 镜像设置为阿里云镜像，加速国内下载速度
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer

# 通过 Composer 安装 daosoft/hyperf 项目
composer create-project daosoft/hyperf

# 进入安装好的 Hyperf 项目目录
cd hyperf
# 启动 Hyperf
php artisan start
```

接下来，就可以在 `/tmp/hyperf` 中看到您安装好的代码了。由于 Hyperf 是持久化的 CLI 框架，当您修改完您的代码后，通过 `CTRL + C` 终止当前启动的进程实例，并重新执行 `php artisan start` 启动命令即可。

# 常见问题

## Swoole 短名未关闭

```bash
[ERROR] Swoole short name have to disable before start server, please set swoole.use_shortname = 'Off' into your php.ini.
```

您需要在您的 php.ini 配置文件增加 `swoole.use_shortname = 'Off'` 配置项

```bash
# echo 'swoole.use_shortname = "Off"' >> /etc/php.ini
swoole.use_shortname = 'Off'
```

> 注意该配置必须于 php.ini 内配置，无法通过 ini_set() 函数来重写

当然，也可以通过以下的命令来启动服务，在执行 PHP 命令时关闭掉 Swoole 短名功能

```bash
php -d swoole.use_shortname=Off bin/hyperf.php start
```

## 存在兼容性问题的扩展

由于 Hyperf 基于 Swoole 协程实现，而 Swoole 4 带来的协程功能是 PHP 前所未有的，所以与不少扩展都仍存在兼容性的问题。   
以下扩展（包括但不限于）都会造成一定的兼容性问题，不能与之共用或共存：

- xhprof
- xdebug
- blackfire
- trace
- uopz
