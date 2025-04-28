# FROM php:8.1-apache

# # ติดตั้ง mysqli
# RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# # ตั้งค่า timezone ถ้าต้องการ
# RUN echo "date.timezone=Asia/Bangkok" > /usr/local/etc/php/conf.d/timezone.ini

FROM php:8.2-fpm

WORKDIR /var/www/html

# ติดตั้ง extension ที่จำเป็น เช่น mysqli
RUN docker-php-ext-install mysqli pdo pdo_mysql