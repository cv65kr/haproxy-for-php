# tl;dr;

Simple example how scale PHP applications with Docker and Consul. Haproxy configuration is automatically updated when we scale our PHP container.

# How use?

```bash
docker-compose up -d --scale php=5
```

Consul UI is available on [http://0.0.0.0:8500/ui](http://0.0.0.0:8500/ui).