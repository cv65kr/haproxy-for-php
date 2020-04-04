consul {
    address = "consul:8500"
    retry {
        enabled = true
        attempts = 12
        backoff = "250ms"
    }
}
template {
    source      = "/tmp/haproxy.ctmpl"
    destination = "/usr/local/etc/haproxy/haproxy.cfg"
    perms = 0600
    command = "/usr/bin/reload.sh"
}