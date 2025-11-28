@echo off
REM ============================================
REM SCRIPT DE SEED - CHARGER 170 ÉLÉMENTS
REM ============================================
REM 
REM Ce script charge les données de test dans la base de données
REM 
REM Usage: seed-database.bat

echo 🌱 Chargement des donnees de test (170 elements)...

REM Vérifier si Docker est en cours d'exécution
docker ps | findstr "orume_db" >nul
if errorlevel 1 (
    echo ❌ Le conteneur MySQL n'est pas en cours d'execution.
    echo    Demarrez d'abord les conteneurs avec: docker-compose up -d
    pause
    exit /b 1
)

REM Charger le script SQL
docker exec -i orume_db mysql -u orume_user -porume_password orume < docker\mysql\seed.sql

if errorlevel 1 (
    echo ❌ Erreur lors du chargement des donnees.
    pause
    exit /b 1
) else (
    echo ✅ 170 elements charges avec succes dans la base de donnees !
    echo.
    echo Repartition :
    echo   - 20 messages
    echo   - 28 sites web
    echo   - 35 affiches
    echo   - 30 identites visuelles
    echo   - 30 shootings
)

pause

