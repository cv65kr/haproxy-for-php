FROM haproxy:latest

ENV CONSUL_TEMPLATE_VERSION=0.24.1

RUN  apt-get update \
  && apt-get install -y wget unzip \
  && rm -rf /var/lib/apt/lists/*

RUN wget --no-check-certificate https://releases.hashicorp.com/consul-template/${CONSUL_TEMPLATE_VERSION}/consul-template_${CONSUL_TEMPLATE_VERSION}_linux_amd64.zip -O /tmp/consul_template.zip \
    && unzip /tmp/consul_template.zip -d /tmp \
    && cd /tmp \
    && mv consul-template /usr/bin \
    && rm -rf /tmp/consul-template

COPY consul-template-config.hcl /tmp/consul-template-config.hcl
COPY haproxy.ctmpl /tmp/haproxy.ctmpl
COPY start.sh /usr/bin/start.sh
COPY reload.sh /usr/bin/reload.sh

ENTRYPOINT ["/usr/bin/start.sh"]