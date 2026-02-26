@echo off
echo Restarting Laravel Queue Worker...
php artisan queue:restart
echo Queue worker restart signal sent!
echo.
echo Note: This signals existing workers to restart after finishing current job.
echo If no worker is running, start one with: queue-worker.bat
pause
