#!/bin/sh
set -e

IP=`ip addr | grep -E 'eth0.*state UP' -A2 | tail -n 1 | awk '{print $2}' | cut -f1 -d '/'`
PORT=9000
CONTAINER_ID=$(cat /proc/1/cpuset | cut -c9-)
CONSUL_URL="http://consul:8500"

dockerize -wait $CONSUL_URL

BODY=$(cat << EOF
{
  "id": "$CONTAINER_ID",
  "name": "app",
  "tags": [
    "php"
  ],
  "address": "$IP",
  "port": $PORT,
  "check": {
    "tcp": "$IP:$PORT",
    "interval": "10s",
    "timeout": "1s"
  }
}
EOF
)

curl -XPUT $CONSUL_URL/v1/agent/service/register -d "$BODY"

export CONTAINER_ID=$CONTAINER_ID
export CONTAINER_IP=$IP

exec /usr/sbin/php-fpm7 -R --nodaemonize