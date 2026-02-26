# Queue System Setup Guide

## ✅ Setup Completed

The queue system has been configured for your Laravel application. This allows email notifications and other background jobs to be processed asynchronously.

## 📋 What Was Configured

1. ✅ Created `jobs` database table for queue management
2. ✅ Created helper scripts to run queue workers
3. ✅ Queue is ready to process background jobs

## 🚀 Quick Start

### Option 1: Using Batch File (Recommended for Windows)
Double-click `queue-worker.bat` or run in terminal:
```bash
queue-worker.bat
```

### Option 2: Using PowerShell
```powershell
.\queue-worker.ps1
```

### Option 3: Using Artisan Command Directly
```bash
php artisan queue:work
```

## ⚙️ Configuration Required

### Update .env File
Change the queue connection from `sync` to `database`:
```env
QUEUE_CONNECTION=database
```

After changing .env, restart your queue worker if it's already running.

## 📊 Monitoring Queued Jobs

### View Failed Jobs
```bash
php artisan queue:failed
```

### Retry Failed Jobs
```bash
php artisan queue:retry all
```

### Clear Failed Jobs
```bash
php artisan queue:flush
```

### Monitor Jobs in Real-time
```bash
php artisan queue:listen --verbose
```

## 🔄 Restarting Queue Workers

When you make code changes, restart the queue worker:

### Option 1: Use the restart script
```bash
queue-restart.bat
```

### Option 2: Manual restart
```bash
php artisan queue:restart
```

Then start the worker again with `queue-worker.bat`

## 🏭 Production Deployment

### For Production Servers (Recommended: Supervisor)

Create a supervisor configuration file `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/worker.log
stopwaitsecs=3600
```

Then reload supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### For Windows Production (Task Scheduler)

1. Open Task Scheduler
2. Create Basic Task
3. Set trigger (e.g., "When computer starts")
4. Action: "Start a program"
5. Program: `C:\path\to\php.exe`
6. Arguments: `artisan queue:work --sleep=3 --tries=3`
7. Start in: `C:\path\to\your\project`

## 🎯 Email Notification Queue

Your `ChangeMeterCompletedNotification` is now configured to:
- ✅ Queue automatically when status = 2 (completed)
- ✅ Process in the background
- ✅ Retry up to 3 times if it fails
- ✅ Generate and attach PDF automatically

## 📝 Common Queue Commands

```bash
# Start queue worker
php artisan queue:work

# Start with specific options
php artisan queue:work --tries=3 --timeout=90

# Work on specific queue
php artisan queue:work --queue=high,default

# Process jobs once then exit (useful for testing)
php artisan queue:work --once

# Stop gracefully after current job
php artisan queue:restart

# Check queue table
php artisan queue:monitor
```

## 🐛 Troubleshooting

### Jobs not processing?
1. Check if worker is running
2. Verify `QUEUE_CONNECTION=database` in .env
3. Check database connection
4. Look at `storage/logs/laravel.log` for errors

### Emails not sending?
1. Verify mail configuration in .env
2. Check failed jobs: `php artisan queue:failed`
3. Check logs: `storage/logs/laravel.log`
4. Test email manually

### Worker keeps stopping?
1. Check for PHP errors in logs
2. Increase timeout: `--timeout=300`
3. Increase memory: `--memory=512`
4. Use supervisor for auto-restart

## 💡 Tips

- **Development**: Run worker in terminal to see real-time output
- **Production**: Use Supervisor (Linux) or Task Scheduler (Windows)
- **Testing**: Use `--once` flag to process one job at a time
- **Always restart workers** after code changes!

## 📞 Support

For more information, see Laravel Queue documentation:
https://laravel.com/docs/queues
