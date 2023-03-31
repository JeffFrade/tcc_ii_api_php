FROM nginx:1.16

USER root
RUN apt-get update && \
    apt-get install -y \
        net-tools
COPY ./* /etc/nginx/conf.d/
WORKDIR /api
