# PHP2Shell（CVE-2026-109618 / CVE-2026-109619）の修正版 8.2.35 に固定する
# 浮動タグ（8.2-fpm）だとキャッシュ済みの古いイメージが使われ続けるため、パッチ番号まで明示する
FROM php:8.2.35-fpm

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

# Node.js 20.x をインストール
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# GD拡張機能の有効化
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# zip, pdo_mysql, mbstring拡張機能の有効化
RUN docker-php-ext-install pdo_mysql zip mbstring

# セキュリティ用の PHP 設定（脆弱性の攻撃経路を塞ぐ）
COPY security.ini /usr/local/etc/php/conf.d/zz-security.ini

# Composerのインストール
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html
