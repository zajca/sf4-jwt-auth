FROM zajca/php:symfony
RUN apt-get update \
&& apt-get install -y ca-certificates\
&& rm -rf /var/lib/apt/lists/*
RUN mkdir -p /usr/local/bin/
RUN	curl -s -o installer.php https://getcomposer.org/installer
RUN	php installer.php --check
RUN	php installer.php --install-dir=/usr/local/bin --filename=composer -vvv
RUN mkdir -p /usr/app
COPY ./composer.json /usr/app/
COPY ./composer.lock /usr/app/
COPY ./symfony.lock /usr/app/
RUN composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader -d /usr/app
COPY ./src /usr/app
WORKDIR /usr/app/public
EXPOSE 80
