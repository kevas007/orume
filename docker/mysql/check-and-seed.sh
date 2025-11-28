#!/bin/bash
# Script pour vérifier si la base de données est vide et exécuter le seeder

# Attendre que MySQL soit prêt
echo "⏳ Attente de la disponibilité de MySQL..."
until mysql -h db -u orume_user -porume_password -e "SELECT 1" > /dev/null 2>&1; do
  sleep 2
done

echo "✅ MySQL est prêt"

# Vérifier si la base de données est vide (pas de données dans les tables principales)
COUNT=$(mysql -h db -u orume_user -porume_password orume -sN -e "
SELECT 
  (SELECT COUNT(*) FROM users) +
  (SELECT COUNT(*) FROM messages) +
  (SELECT COUNT(*) FROM sites) +
  (SELECT COUNT(*) FROM affiches) +
  (SELECT COUNT(*) FROM identites) +
  (SELECT COUNT(*) FROM shootings) +
  (SELECT COUNT(*) FROM clients)
AS total;
" 2>/dev/null)

if [ "$COUNT" = "0" ] || [ -z "$COUNT" ]; then
  echo "📦 La base de données est vide, exécution du seeder..."
  mysql -h db -u orume_user -porume_password orume < /docker-entrypoint-initdb.d/seed.sql
  echo "✅ Seeder exécuté avec succès"
else
  echo "ℹ️  La base de données contient déjà des données ($COUNT enregistrements), le seeder ne sera pas exécuté"
fi

