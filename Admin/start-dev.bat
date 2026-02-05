@echo off
REM Add Node.js to PATH and start development server
setx PATH "C:\Program Files\nodejs;%PATH%"
echo Node.js added to PATH!
echo.
cd /d C:\xampp\htdocs\ppds\Admin
echo Starting Laravel development server...
npm run dev
pause
