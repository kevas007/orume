@echo off
REM ============================================
REM SCRIPT DE RÉINITIALISATION DE LA BASE DE DONNÉES (CMD)
REM ============================================
REM 
REM Ce script supprime et recrée la base de données
REM avec toutes les relations (clés étrangères) correctement configurées.
REM 
REM Usage: recreate-database.bat

echo.
echo 🗄️  Réinitialisation de la base de données Orüme...
echo.

REM Vérifier si Docker est en cours d'exécution
docker ps >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker n'est pas en cours d'exécution.
    echo    Veuillez démarrer Docker Desktop.
    pause
    exit /b 1
)

REM Vérifier si les conteneurs existent
docker ps -a | findstr "orume_db" >nul 2>&1
if errorlevel 1 (
    echo ⚠️  Les conteneurs Docker ne sont pas encore créés.
    echo    Création des conteneurs...
    docker-compose up -d
    if errorlevel 1 (
        echo ❌ Erreur lors de la création des conteneurs.
        pause
        exit /b 1
    )
    echo ✅ Conteneurs créés. Attente du démarrage de MySQL...
    timeout /t 10 /nobreak >nul
) else (
    echo 📦 Arrêt des conteneurs...
    docker-compose down
)

echo.
echo 🗑️  Suppression du volume de la base de données...
docker volume rm orume_db_data >nul 2>&1
if errorlevel 1 (
    echo ⚠️  Le volume n'existait pas ou a déjà été supprimé.
) else (
    echo ✅ Volume supprimé avec succès.
)

echo.
echo 🚀 Redémarrage des conteneurs avec la nouvelle base de données...
docker-compose up -d

if errorlevel 1 (
    echo ❌ Erreur lors du redémarrage des conteneurs.
    pause
    exit /b 1
)

echo.
echo ⏳ Attente de l'initialisation de MySQL (15 secondes)...
timeout /t 15 /nobreak >nul

echo.
echo 🔍 Vérification de la base de données...

REM Vérifier que MySQL est prêt
set /a attempt=0
set /a maxAttempts=10
set mysqlReady=0

:check_mysql
docker exec orume_db mysql -u orume_user -porume_password -e "SELECT 1" >nul 2>&1
if errorlevel 1 (
    set /a attempt+=1
    if %attempt% lss %maxAttempts% (
        echo    Tentative %attempt%/%maxAttempts%...
        timeout /t 3 /nobreak >nul
        goto check_mysql
    )
    echo ❌ MySQL n'est pas prêt après plusieurs tentatives.
    echo    Vérifiez les logs avec: docker-compose logs db
    pause
    exit /b 1
) else (
    set mysqlReady=1
)

echo.
echo ✅ Base de données réinitialisée avec succès !
echo.
echo 📝 Informations de connexion:
echo    - Base de données: orume
echo    - Utilisateur: orume_user
echo    - Mot de passe: orume_password
echo    - Admin par défaut:
echo      * Username: admin
echo      * Password: admin123
echo.
echo 🌐 Accès:
echo    - Frontend: http://localhost:8080
echo    - Admin: http://localhost:8081
echo    - phpMyAdmin: http://localhost:8082
echo.

pause

