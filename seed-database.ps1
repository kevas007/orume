# ============================================
# SCRIPT DE SEED - CHARGER 170 ÉLÉMENTS (PowerShell)
# ============================================
# 
# Ce script charge les données de test dans la base de données
# 
# Usage: .\seed-database.ps1

Write-Host "🌱 Chargement des donnees de test (170 elements)..." -ForegroundColor Green

# Vérifier si Docker est en cours d'exécution
$containerRunning = docker ps | Select-String "orume_db"
if (-not $containerRunning) {
    Write-Host "❌ Le conteneur MySQL n'est pas en cours d'execution." -ForegroundColor Red
    Write-Host "   Demarrez d'abord les conteneurs avec: docker-compose up -d" -ForegroundColor Yellow
    Read-Host "Appuyez sur Entree pour continuer"
    exit 1
}

# Vérifier que le fichier SQL existe
$sqlFile = "docker\mysql\seed.sql"
if (-not (Test-Path $sqlFile)) {
    Write-Host "❌ Le fichier $sqlFile n'existe pas." -ForegroundColor Red
    Read-Host "Appuyez sur Entree pour continuer"
    exit 1
}

# Charger le script SQL
Write-Host "📥 Chargement du fichier SQL..." -ForegroundColor Cyan
Get-Content $sqlFile | docker exec -i orume_db mysql -u orume_user -porume_password orume

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Erreur lors du chargement des donnees." -ForegroundColor Red
    Read-Host "Appuyez sur Entree pour continuer"
    exit 1
} else {
    Write-Host "✅ 170 elements charges avec succes dans la base de donnees !" -ForegroundColor Green
    Write-Host ""
    Write-Host "Repartition :" -ForegroundColor Cyan
    Write-Host "  - 20 messages"
    Write-Host "  - 50 sites web"
    Write-Host "  - 50 affiches"
    Write-Host "  - 50 identites visuelles"
    Write-Host "  - 50 shootings"
}

Read-Host "Appuyez sur Entree pour continuer"

