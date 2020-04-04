#!/bin/sh
haproxy -f /usr/local/etc/haproxy/haproxy.cfg  -D -sf $(pidof haproxy)