FROM php:8.2-fpm

# 必要な拡張機能をインストール
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    imagemagick \
    libmagickwand-dev \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Node.js 18.x をインストール
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# GD拡張機能の有効化
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# zip, pdo_mysql, mbstring拡張機能の有効化
RUN docker-php-ext-install pdo_mysql zip mbstring

# Composerのインストール
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html
