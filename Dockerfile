FROM php:7.1.1-apache
RUN apt-get -y  install curl
RUN curl -sSL https://github.com/amalgam8/amalgam8/releases/download/v0.4.0/a8sidecar.sh | sh
ENV A8_SERVICE=uiservice:v1
ENV A8_ENDPOINT_PORT=80
ENV A8_ENDPOINT_TYPE=http
ENV A8_REGISTRY_URL=http://micro-a8-registry.mybluemix.net
ENV A8_REGISTRY_POLL=60s
ENV A8_CONTROLLER_URL=http://micro-a8-controller.mybluemix.net
ENV A8_CONTROLLER_POLL=60s
ENV A8_LOG=enable_log
copy * ./
CMD ["a8sidecar", "--register", "--supervise", "php", "-a"]
