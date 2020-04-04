#!/bin/sh
consul-template -config=/tmp/consul-template-config.hcl > /tmp/consul-template.log 2>&1