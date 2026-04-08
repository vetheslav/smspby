FROM php:8.4.17-cli-alpine3.23

ARG UID
ARG GID

RUN addgroup -S app -g $GID && adduser -S app -G app -u $UID

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --2.7.2 \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer


RUN apk --update --no-cache add  \
    git \
    linux-headers \
    $PHPIZE_DEPS \
    && apk del $PHPIZE_DEPS

USER app

WORKDIR /opt/app
