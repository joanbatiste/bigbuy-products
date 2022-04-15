FROM php:7.4.14-apache

ENV APACHE_CONFDIR /etc/apache2
ENV APACHE_ENVVARS $APACHE_CONFDIR/envvars
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
ENV TZ=Europe/Madrid

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libonig-dev \
        libzip-dev \
        cron \
        tzdata \
        ssl-cert \
        libicu-dev \
        libxslt-dev \
	logrotate \
	wget \
	locales \
	xfonts-75dpi \
	cron

RUN pecl install xdebug-2.9.8 \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.default_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/xdebug.ini

  
RUN docker-php-ext-configure \
    gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/; \
    PHP_OPENSSL=yes docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap ; \
  docker-php-ext-install \
    gd \
    intl \
    mbstring \
    mysqli \
    pdo_mysql \
    xsl \
    zip \
    opcache \
    bcmath \
    soap \
    sockets \
    pcntl

RUN usermod -u 33 www-data \
  && sed -i 's/^ServerSignature/#ServerSignature/g' /etc/apache2/conf-enabled/security.conf \
  && sed -i 's/^ServerTokens/#ServerTokens/g' /etc/apache2/conf-enabled/security.conf \
  && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
  && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
  && echo "ServerSignature Off" >> /etc/apache2/conf-enabled/security.conf \
  && echo "ServerTokens Prod" >> /etc/apache2/conf-enabled/security.conf \
  && echo 'Header set Access-Control-Allow-Origin "*"' >> /etc/apache2/conf-enabled/security.conf \
  && echo 'Header set Access-Control-Allow-Headers "authorization,content-type"' >> /etc/apache2/conf-enabled/security.conf \
  && echo 'memory_limit = 1024M' >> /usr/local/etc/php/conf.d/config-php.ini \
  && echo 'file_uploads = On' >> /usr/local/etc/php/conf.d/config-php.ini \
  && echo 'post_max_size = 200M' >> /usr/local/etc/php/conf.d/config-php.ini \
  && echo 'upload_max_filesize = 200M' >> /usr/local/etc/php/conf.d/config-php.ini \
  && echo 'max_execution_time = 600' >> /usr/local/etc/php/conf.d/config-php.ini \
  && echo 'display_errors = off' >> /usr/local/etc/php/conf.d/custom.ini \
  && echo 'log_errors = on' >> /usr/local/etc/php/conf.d/custom.ini \
  && echo 'date.timezone = Europe/Madrid' >> /usr/local/etc/php/conf.d/custom.ini  \
  && echo ';error_reporting = E_ERROR | E_WARNING | E_PARSE' >> /usr/local/etc/php/conf.d/custom.ini \
  && mkfifo --mode 0666 /var/log/cron.log \
  && sed --regexp-extended --in-place 's/^session\s+required\s+pam_loginuid.so$/session optional pam_loginuid.so/' /etc/pam.d/cron \
  && chown www-data.www-data /var/www/ \
  && curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
  && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
  && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
  && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer \
  && rm /tmp/composer-setup.php \
  && chmod +x /usr/local/bin/composer \
  && mkdir -p /root/.composer \
  && echo 'es_ES.UTF-8 UTF-8' > /etc/locale.gen \
  && locale-gen \
  && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
  && a2enmod actions rewrite expires headers ssl \
  && a2ensite default-ssl;

# logs should go to stdout / stderr
RUN set -ex \
    && . "$APACHE_ENVVARS" \
    && ln -sfT /dev/stderr "$APACHE_LOG_DIR/error.log" \
    && ln -sfT /dev/stdout "$APACHE_LOG_DIR/access.log" \
    && ln -sfT /dev/stdout "$APACHE_LOG_DIR/other_vhosts_access.log"

COPY logrotate-docker /etc/logrotate.d

COPY start-services /usr/sbin

EXPOSE 80 443

CMD ["start-services"]

