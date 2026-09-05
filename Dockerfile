FROM php:8.4-fpm AS base

# تثبيت الاعتماديات النظامية اللازمة لـ Laravel وnginx
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# نسخ Composer من صورته الرسمية
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# تثبيت Node.js 22 (مطلوب لبناء فرونت React)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www/html
COPY . .

# تثبيت باكجات PHP بدون dev dependencies
RUN composer install --no-dev --optimize-autoloader

# تثبيت باكجات npm وبناء الفرونت
RUN npm install && npm run build

# تجهيز ملف env ومفتاح التطبيق
RUN cp .env.example .env \
    && php artisan key:generate

# نسخ إعدادات nginx وسكريبت التشغيل
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

# صلاحيات الكتابة اللازمة لـ Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080
CMD ["/start.sh"]