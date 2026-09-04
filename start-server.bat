@echo off
title Mortgage Payoff Calculator WordPress Server
echo =======================================================
echo Starting Mortgage Payoff Calculator WordPress Web Server
echo URL: http://localhost:8080/
echo Admin: http://localhost:8080/wp-admin/ (User: admin / Pass: AdminPass@2026)
echo =======================================================
"C:\xampp\php\php.exe" -d extension=sqlite3 -S localhost:8080
pause
