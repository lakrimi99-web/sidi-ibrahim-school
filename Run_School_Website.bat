@echo off
title Sidi Ibrahim Pioneer School Launcher
cls

echo ========================================================
echo   Sidi Ibrahim Pioneer School - Web Launcher
echo ========================================================
echo.

set PHP_CMD=php

:: Search for PHP executable in standard Windows locations
if exist "C:\xampp\php\php.exe" (
    set PHP_CMD="C:\xampp\php\php.exe"
) else if exist "C:\wamp64\bin\php\php8.2.0\php.exe" (
    set PHP_CMD="C:\wamp64\bin\php\php8.2.0\php.exe"
) else if exist "C:\wamp64\bin\php\php8.1.0\php.exe" (
    set PHP_CMD="C:\wamp64\bin\php\php8.1.0\php.exe"
) else if exist "C:\php\php.exe" (
    set PHP_CMD="C:\php\php.exe"
) else if exist "C:\Program Files\PHP\php.exe" (
    set PHP_CMD="C:\Program Files\PHP\php.exe"
)

:: Test if PHP is available
%PHP_CMD% -v >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] PHP Server detected. Launching http://localhost:8000...
    timeout /t 1 /nobreak > nul
    start http://localhost:8000
    cd /d "%~dp0"
    %PHP_CMD% -S localhost:8000
) else (
    echo [OK] Opening website directly in default browser...
    start "" "%~dp0index.html"
)
