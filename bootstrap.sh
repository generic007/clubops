#!/bin/bash
# ClubOps OS — Bootstrap Script
# Run this on DreamHost to scaffold the Laravel project
# Usage: bash bootstrap.sh

set -e

echo "=== ClubOps OS Bootstrap ==="

# 1. Check PHP
PHP=$(which php 2>/dev/null || echo "/usr/bin/php8.2")
if [ ! -f "$PHP" ]; then
    echo "PHP not found. Install PHP 8.2+ on DreamHost first."
    exit 1
fi
echo "PHP: $($PHP -v | head -1)"

# 2. Install Composer if needed
if ! which composer >/dev/null 2>&1; then
    echo "Installing Composer..."
    EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"
    if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
        echo "Composer installer checksum mismatch"
        rm composer-setup.php
        exit 1
    fi
    php composer-setup.php --install-dir=~/bin
    php -r "unlink('composer-setup.php');"
    export PATH="$HOME/bin:$PATH"
fi
echo "Composer: $(composer -V 2>/dev/null | head -1)"

# 3. Create Laravel project
echo "Creating Laravel project..."
composer create-project laravel/laravel tmp-app --prefer-dist --no-interaction

# 4. Copy custom files
echo "Copying custom source files..."
cp -r src/* tmp-app/

# 5. Install additional packages
cd tmp-app
composer require laravel/breeze --dev --no-interaction
php artisan breeze:install blade --no-interaction

# 6. Install production dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# 7. Build assets locally (requires Node)
if which npm >/dev/null 2>&1; then
    echo "Building assets..."
    npm install && npm run build
else
    echo "npm not found. Assets will use CDN."
fi

# 8. Environment setup
echo "Setting up environment..."
cp .env.example .env
php artisan key:generate

echo ""
echo "=== Bootstrap Complete ==="
echo "Next steps:"
echo "  1. Edit .env with your DreamHost MySQL credentials"
echo "  2. Run: php artisan migrate"
echo "  3. Run: php artisan db:seed --class=DatabaseSeeder"
echo "  4. Point your domain to public/"
echo "  5. Set up cron: php /path/to/artisan schedule:run"
