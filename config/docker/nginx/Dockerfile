FROM nginx:alpine

RUN apk update && \
    apk add autoconf automake libtool

COPY default.conf /etc/nginx/conf.d
