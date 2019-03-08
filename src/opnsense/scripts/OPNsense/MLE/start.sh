#!/bin/sh

# permission for redis
mkdir -p /var/log/redis
chown -R redis:redis /var/log/redis
chmod -R 0755 /var/log/redis

# load redis-ml module
sed -i '' 's/# loadmodule \/path\/to\/other_module.so/loadmodule \/usr\/local\/lib\/redis-ml.so/' /usr/local/etc/redis.conf

# start redis if not started
if /usr/local/etc/rc.d/redis status | grep 'is not running' > /dev/null; then
  /usr/local/etc/rc.d/redis start
fi

# start dragonfly-mle if not started
if /usr/local/etc/rc.d/dragonfly-mle status | grep 'is not running' > /dev/null; then
  /usr/local/etc/rc.d/dragonfly-mle start
fi