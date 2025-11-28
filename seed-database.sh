#!/bin/bash
# ============================================
# SCRIPT DE SEED - CHARGER 170 ÉLÉMENTS
# ============================================
# 
# Ce script charge les données de test dans la base de données
# 
# Usage: ./seed-database.sh

echo "🌱 Chargement des données de test (170 éléments)..."

# Vérifier si Docker est en cours d'exécution
if ! docker ps | grep -q orume_db; then
    echo "❌ Le conteneur MySQL n'est pas en cours d'exécution."
    echo "   Démarrez d'abord les conteneurs avec: docker-compose up -d"
    exit 1
fi

# Charger le script SQL
docker exec -i orume_db mysql -u orume_user -porume_password orume < docker/mysql/seed.sql

if [ $? -eq 0 ]; then
    echo "✅ 170 éléments chargés avec succès dans la base de données !"
    echo ""
    echo "Répartition :"
    echo "  - 20 messages"
    echo "  - 50 sites web"
    echo "  - 50 affiches"
    echo "  - 50 identités visuelles"
    echo "  - 50 shootings"
else
    echo "❌ Erreur lors du chargement des données."
    exit 1
fi

