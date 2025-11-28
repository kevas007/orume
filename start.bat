@echo off
echo ========================================
echo   Demarrage du projet Orume avec Docker
echo ========================================
echo.

REM Verifier si Docker est installe
docker --version >nul 2>&1
if errorlevel 1 (
    echo [ERREUR] Docker n'est pas installe ou n'est pas dans le PATH
    echo Veuillez installer Docker Desktop
    pause
    exit /b 1
)

REM Verifier si docker-compose est disponible
docker-compose --version >nul 2>&1
if errorlevel 1 (
    echo [ERREUR] docker-compose n'est pas disponible
    pause
    exit /b 1
)

REM Creer le fichier .env s'il n'existe pas
if not exist .env (
    echo Creation du fichier .env...
    copy env.example .env
    echo Fichier .env cree. Vous pouvez le modifier si necessaire.
    echo.
)

echo Demarrage des conteneurs Docker...
echo.

docker-compose up -d

if errorlevel 1 (
    echo [ERREUR] Echec du demarrage des conteneurs
    pause
    exit /b 1
)

echo.
echo ========================================
echo   Demarrage termine avec succes !
echo ========================================
echo.
echo Acces a l'application :
echo   - Frontend Public : http://localhost:8080/
echo   - Admin           : http://localhost:8081/
echo   - phpMyAdmin      : http://localhost:8082/
echo.
echo Commandes utiles :
echo   - Voir les logs    : docker-compose logs -f
echo   - Arreter          : docker-compose down
echo   - Redemarrer        : docker-compose restart
echo.
pause

