FROM comicrelief/php7-slim

COPY . /src
COPY . /public
COPY . /config
COPY ./.env.example /.env
COPY . /composer.json

WORKDIR /public

RUN php ../composer.phar install
