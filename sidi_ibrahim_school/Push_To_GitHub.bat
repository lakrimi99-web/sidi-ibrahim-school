@echo off
title Push to GitHub - Sidi Ibrahim School
cls

echo ========================================================
echo   Sidi Ibrahim Pioneer School - GitHub Auto-Push
echo ========================================================
echo.

cd /d "C:\Users\mlk\.gemini\antigravity\scratch\sidi_ibrahim_school"

:: جلب التاريخ والوقت الحالي للـ commit message
set DATETIME=%date% %time%

:: اضغط Enter للاستخدام أو اكتب وصف تعديلاتك
set /p MSG="Enter commit message (or press Enter for auto): "
if "%MSG%"=="" set MSG=Auto update: %DATETIME%

echo.
echo [1/3] Adding all changes...
git add .

echo [2/3] Committing: %MSG%
git commit -m "%MSG%"

echo [3/3] Pushing to GitHub...
git push

echo.
echo ========================================================
echo Done! Your site will be live in ~60 seconds at:
echo https://YOUR_USERNAME.github.io/sidi-ibrahim-school/
echo ========================================================
pause
