FROM php:7.4-cli-alpine

#--------------------------------------------------------------------------
# PHP Composer
#--------------------------------------------------------------------------
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#--------------------------------------------------------------------------
# working
#--------------------------------------------------------------------------
RUN mkdir /work
VOLUME /work
WORKDIR /work

RUN addgroup docker \
    && adduser -h /home/docker -s /bin/bash -G docker -D docker \
    && mkdir -p /home/docker \
    && chown -R docker:docker /home/docker

USER docker
ENV HOME /home/docker
ENV LANG C.UTF-8
