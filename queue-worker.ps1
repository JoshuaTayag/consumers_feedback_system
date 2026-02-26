# Laravel Queue Worker PowerShell Script
Write-Host "Starting Laravel Queue Worker..." -ForegroundColor Green
Write-Host ""
Write-Host "Press Ctrl+C to stop the worker" -ForegroundColor Yellow
Write-Host ""

php artisan queue:work --tries=3 --timeout=90
