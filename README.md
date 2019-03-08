# Swoole by Examples

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/deminy/swoole-by-examples/blob/master/LICENSE.txt)

Examples to show different features in Swoole 4.

* [Setup the PHP Environments](#setup-the-php-environments)
* [Run Sample Scripts](#run-sample-scripts)
   * [Sleep](#sleep)
   * [Server Socket](#server-socket)
   * [Coroutines in a For Loop](#coroutines-in-a-for-loop)
   * [Nested Coroutines](#nested-coroutines)
   * [Channels](#channels)
   * [Defer](#defer)
   * [Enable Coroutines at Runtime](#enable-coroutines-at-runtime)

## Setup the PHP Environments

First, please run the following command to have the Docker container running in the background:

```bash
docker-compose up -d
```

This Docker container has PHP environments (both PHP-FPM and Swoole) built in. Now please run following command to log
into the container so that we can start running some sample scripts in it:

```bash
docker exec -ti $(docker ps | grep app | awk '{print $1}') bash
```

There are four ports mapped in the Docker container:

* port 80: an Nginx web server with PHP-FPM running behind.
    * http://127.0.0.1/index.php
    * http://127.0.0.1/phpinfo.php
* port 9501: an HTTP server created with Swoole.
    * http://127.0.0.1:9501/index.php
* port 9502: an Nginx web server with Swoole running behind.
    * http://127.0.0.1:9502/index.php
* port 9999: This port is mapped only to benchmark server sockets created with PHP and Swoole.

## Run Sample Scripts

Before running any sample scripts, please run following command to log into the running Docker container first, if you
haven't yet done that:

```bash
docker exec -ti $(docker ps | grep app | awk '{print $1}') bash
```

### Sleep

```bash
# The script takes about 3 seconds to finish, and prints out "12".
time php php/sleep.php

# The script takes about 2 seconds to finish, and prints out "21".
time php swoole/sleep1.php

# The script takes about 2 seconds to finish, and prints out "123456".
time php swoole/sleep2.php
```

### Coroutines in a For Loop

```bash
# The script takes about 1 second to finish, with 2,000 coroutines created.
# Without coroutine enabled, the script takes about 2,000 seconds to finish.
time php swoole/for.php
```

### Nested Coroutines

```bash
time php swoole/nested1.php

# The script takes about 5 seconds to finish.
time php swoole/nested2.php

# The script takes about 5 seconds to finish, and prints out "127983465".
time php swoole/nested3.php

# The script takes about 5 seconds to finish, and prints out "123456789".
time php swoole/nested4.php
```

### Channels

```bash
php swoole/channel.php

# Similar to the previous one but has debug messages printed out showing # of active coroutines.
php swoole/channel_debug.php
```

### Defer

```bash
# The script takes about 1 second to finish, and prints out "123456".
time php swoole/defer.php
```

### Enable Coroutines at Runtime

```bash
# The script takes about 2 seconds to finish.
# Without coroutines enabled at runtime, it takes about 3 seconds to finish.
time php swoole/enable-coroutine.php
```

### Server Socket

Create an HTTP server socket with one of following two commands:

```bash
# Create a server socket on port 8000 with PHP.
docker exec -t $(docker ps | grep app | awk '{print $1}') bash -c "php php/socket.php"
# Create a server socket on port 8000 with Swoole.
docker exec -t $(docker ps | grep app | awk '{print $1}') bash -c "php swoole/socket.php"
```

Now use the _ab_ command to benchmark the server socket created:

```bash
ab -n 5000 -c 5 http://127.0.0.1:9999/ # Fire 5 concurrent HTTP requests.
```
