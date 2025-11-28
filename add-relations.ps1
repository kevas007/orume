# ============================================
# SCRIPT D'AJOUT DE RELATIONS (PowerShell)
# ============================================
# 
# Ce script ajoute des relations entre les tables de la base de données
# 
# Usage: .\add-relations.ps1

Write-Host "🔗 Ajout des relations entre les tables..." -ForegroundColor Green

# Vérifier si Docker est en cours d'exécution
$containerRunning = docker ps | Select-String "orume_db"
if (-not $containerRunning) {
    Write-Host "❌ Le conteneur MySQL n'est pas en cours d'execution." -ForegroundColor Red
    Write-Host "   Demarrez d'abord les conteneurs avec: docker-compose up -d" -ForegroundColor Yellow
    Read-Host "Appuyez sur Entree pour continuer"
    exit 1
}

# Vérifier que le fichier SQL existe
$sqlFile = "docker\mysql\add-relations.sql"
if (-not (Test-Path $sqlFile)) {
    Write-Host "❌ Le fichier $sqlFile n'existe pas." -ForegroundColor Red
    Read-Host "Appuyez sur Entree pour continuer"
    exit 1
}

# Charger le script SQL
Write-Host "📥 Application des relations..." -ForegroundColor Cyan
Get-Content $sqlFile | docker exec -i orume_db mysql -u orume_user -porume_password orume

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Erreur lors de l'ajout des relations." -ForegroundColor Red
    Read-Host "Appuyez sur Entree pour continuer"
    exit 1
} else {
    Write-Host "✅ Relations ajoutees avec succes !" -ForegroundColor Green
    Write-Host ""
    Write-Host "Relations creees :" -ForegroundColor Cyan
    Write-Host "  - Table 'clients' creee"
    Write-Host "  - Relations avec 'users' (user_id)"
    Write-Host "  - Relations avec 'clients' (client_id)"
    Write-Host "  - Index crees pour les performances"
}

Read-Host "Appuyez sur Entree pour continuer"

