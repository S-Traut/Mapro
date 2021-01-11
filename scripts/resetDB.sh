find migrations/. -name "*.php" -type f -delete
php bin/console d:d:d --force
php bin/console d:d:c
php bin/console make:migration
php bin/console d:m:m
php bin/console d:f:l