# ベースとなる公式 PHP + Apache イメージ（mysqli を含む）
FROM php:8.2-apache

# mysqli を有効化
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# ルートディレクトリにコードをコピー
COPY . /var/www/html/

# Apache のドキュメントルートを公開
EXPOSE 80

# 動画アップロード用ディレクトリも権限付与（必要に応じて）
RUN chown -R www-data:www-data /var/www/html/videos/uploads
