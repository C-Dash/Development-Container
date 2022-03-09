

# Omeka-S web publishing platform for digital heritage collections (https://omeka.org/s/)

FROM php:7.4-apache

RUN a2enmod rewrite
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get -qq update && apt-get -qq install -y --no-install-recommends apt-utils \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libzip-dev \
    libjpeg-dev \
    libmemcached-dev \
    zlib1g-dev \
    ghostscript \
    imagemagick \
    libmagickwand-dev \
    libgs-dev \
    vim 

# Install the PHP extensions we need
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-configure zip 
RUN docker-php-ext-install -j$(nproc) iconv pdo pdo_mysql mysqli gd xml zip 
##RUN pecl install mcrypt-1.0.2  SUpposedly Replaced with SSL in PHP 7.2 
##RUN docker-php-ext-enable mcrypt 
RUN pecl install imagick 
RUN docker-php-ext-enable imagick 
COPY ./imagemagick-policy.xml /etc/ImageMagick/policy.xml

# Add the Omeka-S PHP code
RUN mkdir -p /var/www/html/omeka-s/
##COPY ./omeka-s-2.1.0.zip /var/www/html
#RUN unzip -q /var/www/html/omeka-s-2.1.0.zip -d /var/www/html 
COPY ./omeka-s-3.1.1.zip /var/www/html
RUN unzip -q /var/www/html/omeka-s-3.1.1.zip -d /var/www/html
RUN cd /var/www/html/omeka-s && chgrp www-data files && chmod g+w files

COPY ./database.ini /var/www/html/omeka-s/config/

#COPY CSVImport_20191203.zip /var/www/html/omeka-s/modules
#RUN unzip -q /var/www/html/omeka-s/modules/CSVImport_20191203.zip -d /var/www/html/omeka-s/modules 


# Below we create symlinks for all of the installation-specific Omeks-S resources
# that we need to persist when the Omeka container is re-spawned.  
# Dummy directories are created here to be the targets of the symlinks. THese are
# clobbered by the persistent data from the chcomekafiles volume is that is mounted 
# to this container according to the Azure container's application settings.
# 

    RUN mkdir -p /var/www/html/cdash-persist/
    RUN rm -r /var/www/html/omeka-s/config \
    && ln -sfv /var/www/html/cdash-persist/config/ /var/www/html/omeka-s/config \
    && rm -r /var/www/html/omeka-s/files \
    && ln -sfv /var/www/html/cdash-persist/files /var/www/html/omeka-s/files \
    && rm -r /var/www/html/omeka-s/themes \
    && ln -sfv /var/www/html/cdash-persist/themes /var/www/html/omeka-s/themes \
    && rm -r /var/www/html/omeka-s/modules \
    && ln -sfv /var/www/html/cdash-persist/modules /var/www/html/omeka-s/modules 

##    && rm -r /var/www/html/omeka-s/application \
##    && ln -sfv /var/www/html/chc_omeka_files/application /var/www/html/omeka-s/application \
##    && rm -r /var/www/html/omeka-s/logs \
##    && ln -sfv /var/www/html/chc_omeka_files/logs /var/www/html/omeka-s/logs 

## FOR DEVELOPMENT --Comment out for production set-up
## Composer is essential for working with PhP code.  Necessary for 
## installing some Omeka modules. Also handy for pl;aying atound 
## with php tutorials. 
# Get and Install Composer  (php package manager)
RUN apt-get update && apt-get install -y wget sqlite3
RUN wget -O composer-setup.php https://getcomposer.org/installer
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
## php Intl extensions necessary for Laminas tutorials
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-enable intl
#### End of Dev modules


RUN ln -s /var/www/html/cdash-persist/themes/cdash_images/cdash_ico.ico /var/www/html/favicon.ico 

COPY ./.htaccess /var/www/html/omeka-s/.htaccess
COPY ./index.htm /var/www/html/index.htm

CMD ["apache2-foreground"]
