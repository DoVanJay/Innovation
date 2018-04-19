FROM php:7.0-fpm

RUN apt-get update -y && \
    apt-get install -y git --fix-missing

RUN cd ~ && \
    git clone https://github.com/VanjayDo/NCS.git
