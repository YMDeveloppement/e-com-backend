#!/bin/sh

# Attendre que MySQL soit prêt (optionnel mais recommandé)
echo "Waiting for database..."
sleep 5 

# Exécuter les tâches automatiques
echo "Running migrations..."
php artisan migrate --force

echo "Clearing cache..."
php artisan config:clear
php artisan cache:clear

# Lancer la commande principale (php-fpm)
exec "$@"