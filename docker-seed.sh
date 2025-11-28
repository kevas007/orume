#!/bin/bash
# Script pour exécuter le seeder après le démarrage de Docker

echo "🚀 Démarrage des conteneurs Docker..."
docker-compose up -d

echo "⏳ Attente que MySQL soit prêt..."
sleep 10

# Attendre que MySQL soit vraiment prêt
until docker exec orume_db mysqladmin ping -h localhost -u orume_user -porume_password --silent; do
  echo "⏳ En attente de MySQL..."
  sleep 2
done

echo "✅ MySQL est prêt"

# Exécuter le seeder
echo "🌱 Vérification et exécution du seeder..."
docker-compose run --rm seeder

echo "✅ Terminé !"

