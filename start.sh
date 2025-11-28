#!/bin/bash

echo "========================================"
echo "  Démarrage du projet Orüme avec Docker"
echo "========================================"
echo ""

# Vérifier si Docker est installé
if ! command -v docker &> /dev/null; then
    echo "[ERREUR] Docker n'est pas installé"
    exit 1
fi

# Vérifier si docker-compose est disponible
if ! command -v docker-compose &> /dev/null; then
    echo "[ERREUR] docker-compose n'est pas disponible"
    exit 1
fi

# Créer le fichier .env s'il n'existe pas
if [ ! -f .env ]; then
    echo "Création du fichier .env..."
    cp env.example .env
    echo "Fichier .env créé. Vous pouvez le modifier si nécessaire."
    echo ""
fi

echo "Démarrage des conteneurs Docker..."
echo ""

docker-compose up -d

if [ $? -ne 0 ]; then
    echo "[ERREUR] Échec du démarrage des conteneurs"
    exit 1
fi

echo ""
echo "========================================"
echo "  Démarrage terminé avec succès !"
echo "========================================"
echo ""
echo "Accès à l'application :"
echo "  - Frontend Public : http://localhost:8080/"
echo "  - Admin           : http://localhost:8081/"
echo "  - phpMyAdmin      : http://localhost:8082/"
echo ""
echo "Commandes utiles :"
echo "  - Voir les logs    : docker-compose logs -f"
echo "  - Arrêter          : docker-compose down"
echo "  - Redémarrer        : docker-compose restart"
echo ""

