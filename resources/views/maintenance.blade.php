<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Under Maintenance</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at top left, #e8f3ff 0%, transparent 35%),
                radial-gradient(circle at bottom right, #fff4d6 0%, transparent 35%),
                #f7f9fc;
            color: #1f2937;
            padding: 24px;
        }

        .maintenance-container {
            width: 100%;
            max-width: 620px;
            text-align: center;
        }

        /* Illustration */
        .illustration {
            position: relative;
            width: 190px;
            height: 190px;
            margin: 0 auto 35px;
        }

        .circle {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: #ffffff;
            box-shadow:
                0 20px 50px rgba(15, 23, 42, 0.10),
                inset 0 0 0 1px rgba(15, 23, 42, 0.04);
        }

        .gear {
            position: absolute;
            font-size: 72px;
            color: #2563eb;
            animation: rotate 8s linear infinite;
        }

        .gear.one {
            left: 27px;
            top: 42px;
        }

        .gear.two {
            right: 25px;
            bottom: 35px;
            font-size: 50px;
            color: #f59e0b;
            animation-direction: reverse;
            animation-duration: 6s;
        }

        .tools {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            font-size: 48px;
            z-index: 2;
        }

        .spark {
            position: absolute;
            font-size: 18px;
            color: #f59e0b;
            animation: pulse 1.8s ease-in-out infinite;
        }

        .spark.one {
            top: 18px;
            right: 35px;
        }

        .spark.two {
            bottom: 28px;
            left: 25px;
            animation-delay: .6s;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: .35;
                transform: scale(.8);
            }
            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        /* Content */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            border-radius: 50px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 18px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #f59e0b;
            animation: pulse-dot 1.5s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% {
                opacity: .5;
            }
            50% {
                opacity: 1;
            }
        }

        h1 {
            font-size: clamp(32px, 6vw, 48px);
            line-height: 1.15;
            font-weight: 750;
            letter-spacing: -1.5px;
            margin-bottom: 18px;
            color: #111827;
        }

        .description {
            max-width: 500px;
            margin: 0 auto;
            color: #6b7280;
            font-size: 16px;
            line-height: 1.7;
        }

        /* Progress */
        .progress-wrapper {
            max-width: 420px;
            margin: 35px auto 0;
            text-align: left;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 9px;
            font-size: 13px;
            color: #6b7280;
        }

        .progress-bar {
            height: 7px;
            background: #e5e7eb;
            border-radius: 20px;
            overflow: hidden;
        }

        .progress {
            width: 72%;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #2563eb, #60a5fa);
            animation: loading 2.5s ease-in-out infinite alternate;
        }

        @keyframes loading {
            from {
                width: 60%;
            }
            to {
                width: 82%;
            }
        }

        .notice {
            margin-top: 32px;
            padding: 15px 20px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .75);
            border: 1px solid rgba(15, 23, 42, .06);
            color: #6b7280;
            font-size: 13px;
        }

        .notice strong {
            color: #374151;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #9ca3af;
        }

        @media (max-width: 480px) {
            .illustration {
                width: 155px;
                height: 155px;
            }

            .gear {
                font-size: 58px;
            }

            .gear.one {
                left: 20px;
                top: 35px;
            }

            .gear.two {
                right: 20px;
                bottom: 27px;
                font-size: 40px;
            }

            .tools {
                font-size: 40px;
            }

            h1 {
                letter-spacing: -1px;
            }

            .description {
                font-size: 15px;
            }
        }
    </style>
</head>

<body>

    <main class="maintenance-container">

        <!-- Maintenance Illustration -->
        <div class="illustration">

            <div class="circle"></div>

            <div class="gear one">⚙</div>

            <div class="gear two">⚙</div>

            <div class="tools">🔧</div>

            <div class="spark one">✦</div>
            <div class="spark two">✦</div>

        </div>

        <!-- Status -->
        <div class="badge">
            <span class="status-dot"></span>
            Maintenance in Progress
        </div>

        <!-- Heading -->
        <h1>
            We’re Making Things Better
        </h1>

        <p class="description">
            Our website is currently undergoing scheduled maintenance
            and improvements. We’re working hard to make your experience
            faster, better, and more reliable.
        </p>

        <!-- Progress -->
        <div class="progress-wrapper">

            <div class="progress-label">
                <span>Maintenance progress</span>
                <span>In progress</span>
            </div>

            <div class="progress-bar">
                <div class="progress"></div>
            </div>

        </div>

        <!-- Notice -->
        <div class="notice">
            <strong>Please check back soon.</strong>
            &nbsp; Thank you for your patience and understanding.
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} &mdash; All rights reserved.
        </div>

    </main>

</body>
</html>