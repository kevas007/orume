# ============================================
# SCRIPT DE REINITIALISATION DE LA BASE DE DONNEES
# ============================================
# 
# Ce script supprime et recree la base de donnees
# avec toutes les relations (cles etrangeres) correctement configurees.
# 
# Usage: .\recreate-database.ps1

Write-Host "Reinitialisation de la base de donnees Orume..." -ForegroundColor Cyan
Write-Host ""

# Verifier si Docker est en cours d'execution
docker ps 2>&1 | Out-Null
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERREUR: Docker n'est pas en cours d'execution." -ForegroundColor Red
    Write-Host "   Veuillez demarrer Docker Desktop." -ForegroundColor Yellow
    Read-Host "Appuyez sur Entree pour continuer"
    exit 1
}

# Verifier si les conteneurs existent
$containerExists = docker ps -a | Select-String "orume_db"
if (-not $containerExists) {
    Write-Host "ATTENTION: Les conteneurs Docker ne sont pas encore crees." -ForegroundColor Yellow
    Write-Host "   Creation des conteneurs..." -ForegroundColor Cyan
    docker-compose up -d
    if ($LASTEXITCODE -ne 0) {
        Write-Host "ERREUR: Erreur lors de la creation des conteneurs." -ForegroundColor Red
        Read-Host "Appuyez sur Entree pour continuer"
        exit 1
    }
    Write-Host "OK: Conteneurs crees. Attente du demarrage de MySQL..." -ForegroundColor Green
    Start-Sleep -Seconds 10
} else {
    Write-Host "Arret des conteneurs..." -ForegroundColor Cyan
    docker-compose down
}

Write-Host ""
Write-Host "Suppression du volume de la base de donnees..." -ForegroundColor Yellow
docker volume rm orume_db_data 2>&1 | Out-Null
if ($LASTEXITCODE -eq 0) {
    Write-Host "OK: Volume supprime avec succes." -ForegroundColor Green
} else {
    Write-Host "ATTENTION: Le volume n'existait pas ou a deja ete supprime." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Redemarrage des conteneurs avec la nouvelle base de donnees..." -ForegroundColor Cyan
docker-compose up -d

if ($LASTEXITCODE -ne 0) {
    Write-Host "ERREUR: Erreur lors du redemarrage des conteneurs." -ForegroundColor Red
    Read-Host "Appuyez sur Entree pour continuer"
    exit 1
}

Write-Host ""
Write-Host "Attente de l'initialisation de MySQL (15 secondes)..." -ForegroundColor Cyan
Start-Sleep -Seconds 15

Write-Host ""
Write-Host "Verification de la base de donnees..." -ForegroundColor Cyan

# Verifier que MySQL est pret
$maxAttempts = 10
$attempt = 0
$mysqlReady = $false

while ($attempt -lt $maxAttempts -and -not $mysqlReady) {
    docker exec orume_db mysql -u orume_user -porume_password -e "SELECT 1" 2>&1 | Out-Null
    if ($LASTEXITCODE -eq 0) {
        $mysqlReady = $true
    } else {
        $attempt++
        Write-Host "   Tentative $attempt/$maxAttempts..." -ForegroundColor Yellow
        Start-Sleep -Seconds 3
    }
}

if (-not $mysqlReady) {
    Write-Host "ERREUR: MySQL n'est pas pret apres plusieurs tentatives." -ForegroundColor Red
    Write-Host "   Verifiez les logs avec: docker-compose logs db" -ForegroundColor Yellow
    Read-Host "Appuyez sur Entree pour continuer"
    exit 1
}

# Verifier les tables creees
Write-Host ""
Write-Host "Verification des tables creees..." -ForegroundColor Cyan
$tables = docker exec orume_db mysql -u orume_user -porume_password orume -e "SHOW TABLES;" 2>&1

if ($LASTEXITCODE -eq 0) {
    Write-Host "OK: Tables creees avec succes:" -ForegroundColor Green
    $tables | Select-String -Pattern "Tables_in_orume" -Context 0,20 | ForEach-Object {
        if ($_ -notmatch "Tables_in_orume") {
            Write-Host "   - $_" -ForegroundColor White
        }
    }
} else {
    Write-Host "ATTENTION: Impossible de verifier les tables." -ForegroundColor Yellow
}

# Verifier les relations (cles etrangeres)
Write-Host ""
Write-Host "Verification des relations (cles etrangeres)..." -ForegroundColor Cyan
$sqlQuery = "SELECT TABLE_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'orume' AND REFERENCED_TABLE_NAME IS NOT NULL ORDER BY TABLE_NAME, CONSTRAINT_NAME;"
$foreignKeys = docker exec orume_db mysql -u orume_user -porume_password orume -e $sqlQuery 2>&1

if ($LASTEXITCODE -eq 0 -and $foreignKeys -match "CONSTRAINT") {
    Write-Host "OK: Relations creees avec succes:" -ForegroundColor Green
    $foreignKeys | Select-String -Pattern "fk_" | ForEach-Object {
        Write-Host "   - $_" -ForegroundColor White
    }
} else {
    Write-Host "ATTENTION: Aucune relation trouvee ou erreur lors de la verification." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "OK: Base de donnees reinitialisee avec succes !" -ForegroundColor Green
Write-Host ""
Write-Host "Informations de connexion:" -ForegroundColor Cyan
Write-Host "   - Base de donnees: orume" -ForegroundColor White
Write-Host "   - Utilisateur: orume_user" -ForegroundColor White
Write-Host "   - Mot de passe: orume_password" -ForegroundColor White
Write-Host "   - Admin par defaut:" -ForegroundColor White
Write-Host "     * Username: admin" -ForegroundColor White
Write-Host "     * Password: admin123" -ForegroundColor White
Write-Host ""
Write-Host "Acces:" -ForegroundColor Cyan
Write-Host "   - Frontend: http://localhost:8080" -ForegroundColor White
Write-Host "   - Admin: http://localhost:8081" -ForegroundColor White
Write-Host "   - phpMyAdmin: http://localhost:8082" -ForegroundColor White
Write-Host ""

Read-Host "Appuyez sur Entree pour continuer"
