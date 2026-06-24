<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Wheel of Names</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Source+Sans+3:wght@300;400;500;600;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:   #4F46E5;
            --secondary: #818CF8;
            --cta:       #F97316;
            --bg:        #0f172a;
            --surface:   #1e1b4b;
            --text:      #f8fafc;
            --text-dim:  #cbd5e1;
            --radius:    16px;
            --glow-sm:   0 0 12px rgba(79, 70, 229, 0.3);
            --glow-md:   0 0 24px rgba(79, 70, 229, 0.4);
            --glow-lg:   0 0 40px rgba(249, 115, 22, 0.5);
        }

        body {
            font-family: 'Source Sans 3', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1a1a2e 100%);
            color: #e2e8f0;
            min-height: 100vh;
            padding: 24px 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: 
                repeating-linear-gradient(0deg, rgba(79, 70, 229, 0.03) 0px, rgba(79, 70, 229, 0.03) 1px, transparent 1px, transparent 2px);
            pointer-events: none;
            z-index: -1;
            animation: scanlines 8s linear infinite;
        }
        
        @keyframes scanlines {
            0% { transform: translateY(0); }
            100% { transform: translateY(10px); }
        }

        .page-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .page-header .eyebrow {
            display: inline-block;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--cta);
            background: rgba(249, 115, 22, 0.1);
            border: 2px solid var(--cta);
            border-radius: 50px;
            padding: 8px 20px;
            margin-bottom: 20px;
            box-shadow: var(--glow-lg);
            animation: pulse-glow 3s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: var(--glow-lg); }
            50% { box-shadow: 0 0 60px rgba(249, 115, 22, 0.8); }
        }

        .page-header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2.2rem, 6vw, 3.5rem);
            font-weight: 900;
            letter-spacing: -0.01em;
            color: #f8fafc;
            line-height: 1.1;
            text-transform: uppercase;
            text-shadow: 0 0 30px rgba(79, 70, 229, 0.5);
        }

        .page-header h1 span {
            background: linear-gradient(90deg, var(--cta) 0%, #fbbf24 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 20px rgba(249, 115, 22, 0.4));
        }

        .page-header .subtitle {
            margin-top: 12px;
            font-size: 1rem;
            color: var(--text-dim);
            letter-spacing: 0.02em;
            font-weight: 300;
        }

        h2 {
            text-align: center;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            font-weight: 900;
            letter-spacing: 0.05em;
            margin-bottom: 32px;
            color: #f8fafc;
            text-transform: uppercase;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 48px;
            max-width: 1200px;
            margin: 0 auto;
            flex-wrap: wrap;
        }

        select {
            width: 100%;
            padding: 12px 16px;
            border-radius: var(--radius);
            border: 2px solid var(--primary);
            background: rgba(79, 70, 229, 0.05);
            color: #e2e8f0;
            font-size: 0.95rem;
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
            box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.1);
        }
        select:hover {
            box-shadow: var(--glow-sm);
            border-color: var(--secondary);
        }
        select:focus {
            border-color: var(--cta);
            box-shadow: var(--glow-md);
            background: rgba(79, 70, 229, 0.1);
        }

        /* Style dropdown options */
        option {
            background: #1e1b4b;
            color: #e2e8f0;
            padding: 10px 8px;
            border: none;
        }
        option:hover {
            background: rgba(79, 70, 229, 0.4);
            color: #fff;
        }
        option:checked {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
        }

        /* Wheel */
        #wheel-container {
            /* flex: 1 1 600px; */
            text-align: center;
            position: relative;
            display: inline-block;
            padding: 20px;
        }

        #wheel {
            width: 620px;
            height: 620px;
            border-radius: 50%;
            box-shadow: 0 0 40px rgba(79, 70, 229, 0.4), 0 0 80px rgba(249, 115, 22, 0.2), inset 0 0 40px rgba(79, 70, 229, 0.1);
            display: block;
            filter: drop-shadow(0 0 20px rgba(249, 115, 22, 0.3));
            transition: transform 0.3s ease;
        }
        
        #wheel:hover {
            box-shadow: 0 0 60px rgba(79, 70, 229, 0.6), 0 0 100px rgba(249, 115, 22, 0.3), inset 0 0 40px rgba(79, 70, 229, 0.15);
        }

        /* Controls */
        #controls {
            flex: 1 1 340px;
            max-width: 420px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 50px;
            background: rgba(79, 70, 229, 0.05);
            border: 2px solid rgba(79, 70, 229, 0.2);
            border-radius: var(--radius);
            padding: 28px;
            box-shadow: 0 0 30px rgba(79, 70, 229, 0.15);
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px 16px;
            border-radius: var(--radius);
            border: 2px solid var(--primary);
            background: rgba(79, 70, 229, 0.05);
            color: #e2e8f0;
            font-size: 0.95rem;
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
        }
        input:hover { 
            box-shadow: var(--glow-sm);
            border-color: var(--secondary);
        }
        input:focus { 
            border-color: var(--cta);
            box-shadow: var(--glow-md);
            background: rgba(79, 70, 229, 0.1);
        }

        .participant-box {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(249, 115, 22, 0.05));
            border: 2px solid var(--primary);
            border-radius: var(--radius);
            padding: 16px 18px;
            font-size: 0.9rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            box-shadow: 0 0 20px rgba(79, 70, 229, 0.2);
        }
        .participant-box span {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--cta);
            text-shadow: 0 0 10px rgba(249, 115, 22, 0.4);
        }

        .btn {
            padding: 12px 16px;
            border-radius: var(--radius);
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
            width: 100%;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: left 0.3s ease;
        }
        
        .btn:hover::before { left: 100%; }
        
        .btn:active { transform: scale(0.97); }
        .btn:hover  { opacity: 0.95; }

        .btn-spin    { 
            background: linear-gradient(135deg, var(--cta), #fb923c);
            color: #fff; 
            font-size: 1.05rem; 
            padding: 16px 18px;
            box-shadow: var(--glow-lg);
            border: 2px solid var(--cta);
        }
        .btn-spin:hover {
            box-shadow: 0 0 60px rgba(249, 115, 22, 0.8);
        }
        
        .btn-export  { 
            background: linear-gradient(135deg, #0ea5e9, #06b6d4);
            color: #fff;
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.4);
            border: 2px solid #0ea5e9;
        }
        .btn-export:hover {
            box-shadow: 0 0 40px rgba(6, 182, 212, 0.6);
        }
        
        .btn-clear   { 
            background: linear-gradient(135deg, #ef4444, #f87171);
            color: #fff;
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.4);
            border: 2px solid #ef4444;
        }
        .btn-clear:hover {
            box-shadow: 0 0 40px rgba(239, 68, 68, 0.6);
        }
        
        .btn-history { 
            background: rgba(79, 70, 229, 0.1);
            border: 2px solid var(--primary);
            color: var(--text-dim);
            font-weight: 600;
            text-transform: none;
            font-family: 'Source Sans 3', sans-serif;
        }
        .btn-history:hover {
            background: rgba(79, 70, 229, 0.2);
            color: var(--text);
            box-shadow: var(--glow-sm);
        }

        /* History */
        .history-container { margin-top: 8px; }

        .history-list {
            max-height: 220px;
            overflow-y: auto;
            background: rgba(79, 70, 229, 0.05);
            border: 2px solid var(--primary);
            border-radius: var(--radius);
            padding: 12px 16px;
            display: none;
            margin-top: 10px;
        }

        .history-list li {
            list-style: none;
            padding: 8px 0;
            border-bottom: 1px solid rgba(79, 70, 229, 0.2);
            font-size: 0.87rem;
            color: #cbd5e1;
        }
        .history-list li:last-child { border-bottom: none; }

        .history-empty {
            color: #64748b;
            font-size: 0.87rem;
            text-align: center;
            padding: 14px 0;
        }

        .history-list::-webkit-scrollbar { width: 6px; }
        .history-list::-webkit-scrollbar-track { background: transparent; }
        .history-list::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 3px; }

        /* SweetAlert2 Customization */
        :root {
            --swal2-popup-bg: #1e1b4b;
            --swal2-html-container-color: #e2e8f0;
            --swal2-title-color: #f8fafc;
            --swal2-border-color: rgba(79, 70, 229, 0.3);
        }

        .swal2-container {
            z-index: 9999 !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
        }

        .swal2-backdrop {
            background: rgba(0, 0, 0, 0.7) !important;
            animation: fadeIn 0.3s ease-out !important;
        }
        
        .swal2-backdrop-hide {
            display: none !important;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .swal2-popup {
            border-radius: 20px !important;
            border: 2px solid var(--swal2-border-color) !important;
            background: linear-gradient(135deg, #1e1b4b 0%, #2e1f4b 100%) !important;
            box-shadow: 0 0 60px rgba(79, 70, 229, 0.6), 0 0 100px rgba(249, 115, 22, 0.3) !important;
            backdrop-filter: blur(10px);
            max-width: 800px !important;
            min-width: 600px !important;
            padding: 40px !important;
            animation: popIn 0.5s cubic-bezier(0.23, 1, 0.320, 1) !important;
        }

        @keyframes popIn {
            0% {
                opacity: 0;
                transform: scale(0.7) rotateX(-10deg);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                opacity: 1;
                transform: scale(1) rotateX(0);
            }
        }

        .swal2-popup.swal2-show {
            animation: popIn 0.5s cubic-bezier(0.23, 1, 0.320, 1) !important;
        }

        .swal2-popup:focus {
            outline: none !important;
        }

        .swal2-title {
            font-size: 2rem !important;
            font-family: 'Bebas Neue', sans-serif !important;
            letter-spacing: 0.05em !important;
            text-transform: uppercase !important;
            font-weight: 900 !important;
            text-shadow: 0 0 20px rgba(79, 70, 229, 0.4) !important;
        }

        .swal2-html-container {
            font-family: 'Source Sans 3', sans-serif !important;
            font-size: 1.1rem !important;
            line-height: 1.8 !important;
            color: #e2e8f0 !important;
        }

        .swal2-html-container ul {
            text-align: center !important;
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100%;
        }

        .swal2-html-container li {
            /* margin: 16px 0 !important; */
            /* padding: 10px 0 !important; */
            border-bottom: 1px solid rgba(79, 70, 229, 0.2) !important;
            /* line-height: 1.8 !important; */
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            hyphens: auto !important;
        }

        .swal2-html-container li:last-child {
            border-bottom: none !important;
        }

        .swal2-html-container li span {
            display: block !important;
            margin-bottom: 6px !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        .swal2-confirm, .swal2-cancel {
            font-family: 'Bebas Neue', sans-serif !important;
            font-size: 1rem !important;
            letter-spacing: 0.05em !important;
            text-transform: uppercase !important;
            font-weight: 700 !important;
            padding: 12px 32px !important;
            border-radius: 12px !important;
            border: 2px solid transparent !important;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1) !important;
            margin: 10px !important;
            min-width: 140px !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
        }

        .swal2-confirm {
            background: linear-gradient(135deg, var(--cta), #fb923c) !important;
            color: #fff !important;
            box-shadow: 0 0 30px rgba(249, 115, 22, 0.5) !important;
            border-color: var(--cta) !important;
        }

        .swal2-confirm:hover {
            box-shadow: 0 0 50px rgba(249, 115, 22, 0.8), 0 8px 20px rgba(0, 0, 0, 0.3) !important;
            transform: translateY(-2px) !important;
        }

        .swal2-confirm:active {
            transform: translateY(0) !important;
        }

        .swal2-cancel {
            background: rgba(79, 70, 229, 0.2) !important;
            color: #cbd5e1 !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 20px rgba(79, 70, 229, 0.3) !important;
        }

        .swal2-cancel:hover {
            background: rgba(79, 70, 229, 0.3) !important;
            box-shadow: 0 0 40px rgba(79, 70, 229, 0.5), 0 8px 20px rgba(0, 0, 0, 0.3) !important;
            transform: translateY(-2px) !important;
        }

        .swal2-cancel:active {
            transform: translateY(0) !important;
        }

        .swal2-actions {
            gap: 10px !important;
            justify-content: center !important;
            margin-top: 30px !important;
            padding: 0 !important;
        }

        .swal2-icon {
            margin: 20px 0 15px 0 !important;
        }

        .swal2-timer-progress-bar {
            background: linear-gradient(90deg, var(--cta), #fb923c) !important;
            height: 4px !important;
        }

    </style>
</head>
<body>

  <header class="page-header">
    <div class="eyebrow">Official Raffle System</div>
    <h1>LEYECO V 45th AGMA <span>Raffle</span></h1>
  </header>

<div class="container">
    <div id="wheel-container">
        <canvas id="wheel" width="500" height="500"></canvas>
        {{-- <div class="wheel-pointer"></div> --}}
    </div>

    <div id="controls">

        {{-- Participant count from DB --}}
        <div class="participant-box">
            👥 <span id="participantCount">{{ count($participants) }}</span>&nbsp;participants loaded from database
        </div>

        <input type="text"   id="prize"      placeholder="Prize (e.g. Grocery Pack)" required>
        <input type="number" id="batchCount" min="1" value="1" placeholder="Batch Winners">
        <select id="municipality" class="municipality-select">
            <option value="">🌐 All Municipalities</option>
            @foreach($municipalities as $m)
                <option value="{{ $m }}">{{ $m }}</option>
            @endforeach
        </select>
        <button class="btn btn-spin"   onclick="spin()">🎡 Spin</button>
        {{-- <button class="btn btn-export" onclick="exportCSV()">⬇ Export History (CSV)</button> --}}
        <button class="btn btn-clear"  onclick="clearHistory()">🗑 Clear History</button>

        {{-- <div class="history-container">
            <button class="btn btn-history" onclick="toggleHistory()">📜 Show / Hide Winner History</button>
            <ul id="historyList" class="history-list"></ul>
        </div> --}}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Inject participants from Laravel controller --}}
<script>
    // PARTICIPANTS is the source of truth — comes from the agmms DB table
    // Each entry: { account_no: "...", name: "..." }
    const PARTICIPANTS = @json($participants);
</script>

<script>
    const canvas    = document.getElementById("wheel");
    const ctx       = canvas.getContext("2d");
    const CSRF      = document.querySelector('meta[name="csrf-token"]').content;
    const BASE_URL  = '/raffle/winners';

    let angle    = 0;
    let spinning = false;

    // Working copy so we can remove winners mid-session without mutating PARTICIPANTS
    let pool = [...PARTICIPANTS];

    // Filter pool by selected municipality
    function getFilteredPool() {
        const selected = document.getElementById("municipality").value;
        if (!selected) return pool; // no filter = all
        return pool.filter(p => p.municipality === selected);
    }

    // Wheel only shows filtered pool (max 100)
    function getWheelNames() {
        const filtered = getFilteredPool();
        if (!filtered.length) return [];
        const display = filtered.length <= 100
            ? filtered
            : [...filtered].sort(() => 0.5 - Math.random()).slice(0, 100);
        return display.map(p => p.name);
    }

    // ── Draw ─────────────────────────────────────────────────
    function drawWheel(names) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (!names.length) {
            // Draw an empty grey circle when there are no participants
            ctx.save();
            ctx.translate(canvas.width / 2, canvas.height / 2);
            ctx.beginPath();
            ctx.arc(0, 0, canvas.width / 2, 0, 2 * Math.PI);
            ctx.fillStyle = "#1e293b";
            ctx.fill();
            ctx.fillStyle = "#475569";
            ctx.font = "bold 16px Segoe UI, Arial";
            ctx.textAlign = "center";
            ctx.fillText("No participants", 0, -8);
            ctx.fillText("loaded", 0, 14);
            ctx.restore();
            return;
        }

        const radius = canvas.width / 2;
        const slice  = (2 * Math.PI) / names.length;
        
        // Dynamic font size based on number of slices (more slices = smaller font)
        let fontSize = 13;
        if (names.length > 30) fontSize = 9;
        else if (names.length > 20) fontSize = 10;
        else if (names.length > 10) fontSize = 11;

        ctx.save();
        ctx.translate(radius, radius);

        names.forEach((name, i) => {
            // Slice
            ctx.beginPath();
            ctx.fillStyle = `hsl(${i * 360 / names.length}, 70%, 55%)`;
            ctx.moveTo(0, 0);
            ctx.arc(0, 0, radius, slice * i, slice * (i + 1));
            ctx.fill();

            // Slice border
            ctx.beginPath();
            ctx.strokeStyle = "rgba(0,0,0,0.15)";
            ctx.lineWidth = 1;
            ctx.moveTo(0, 0);
            ctx.arc(0, 0, radius, slice * i, slice * (i + 1));
            ctx.closePath();
            ctx.stroke();

            // Label - truncate long names
            let displayName = name;
            if (name.length > 30) {
                displayName = name.substring(0, 30) + '...';
            } else if (name.length > 20 && names.length > 20) {
                displayName = name.substring(0, 20) + '...';
            }

            // Draw text label
            ctx.save();
            ctx.rotate(slice * i + slice / 2);
            ctx.textAlign   = "right";
            ctx.fillStyle   = "#fff";
            ctx.font        = `bold ${fontSize}px Segoe UI, Arial`;
            ctx.shadowColor = "rgba(0,0,0,0.5)";
            ctx.shadowBlur  = 3;
            ctx.fillText(displayName, radius - 30, 5);
            ctx.restore();
        });

        ctx.restore();
    }

    // Draw immediately using the injected PARTICIPANTS
    drawWheel(getWheelNames());
    updateCount();

    // ── Spin ─────────────────────────────────────────────────
    function spin() {
        if (spinning) return;

        const prize    = document.getElementById("prize").value.trim();
        const filtered = getFilteredPool();
        const batch    = parseInt(document.getElementById("batchCount").value);

        if (!prize) {
            Swal.fire({ icon: 'warning', title: 'Prize Required', text: 'Please enter a prize before spinning.' });
            return;
        }
        if (!filtered.length) {
            Swal.fire({ icon: 'error', title: 'No Participants', text: 'No participants for the selected municipality.' });
            return;
        }
        if (batch > filtered.length) {
            Swal.fire({ icon: 'error', title: 'Invalid Batch Count', text: 'Batch count exceeds participants in this municipality.' });
            return;
        }

        spinning = true;

        const wheelNames = getWheelNames();

        // ── Tunable constants ──────────────────────────────────
        const INITIAL_VELOCITY = 0.35;   // starting speed (radians per frame) — higher = faster launch
        const FRICTION         = 0.991;  // deceleration per frame — closer to 1 = longer spin, closer to 0.98 = short
        const MIN_VELOCITY     = 0.001;  // stop threshold
        // ──────────────────────────────────────────────────────

        let velocity = INITIAL_VELOCITY;
        let lastTime = null;

        function frame(timestamp) {
            if (!lastTime) lastTime = timestamp;

            const delta = (timestamp - lastTime) / (1000 / 60); // normalize to 60fps
            lastTime = timestamp;

            velocity *= Math.pow(FRICTION, delta); // frame-rate independent friction
            angle    += velocity * delta;

            // Redraw
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.save();
            ctx.translate(canvas.width / 2, canvas.height / 2);
            ctx.rotate(angle);
            ctx.translate(-canvas.width / 2, -canvas.height / 2);
            drawWheel(wheelNames);
            ctx.restore();

            if (velocity > MIN_VELOCITY) {
                requestAnimationFrame(frame);
            } else {
                // Snap angle cleanly to avoid sub-pixel jitter at rest
                angle = Math.round(angle * 1000) / 1000;
                drawWheel(wheelNames);
                pickWinners(batch, prize, filtered);
                spinning = false;
            }
        }

        requestAnimationFrame(frame);
    }

    // ── Cleanup SweetAlert DOM ───────────────────────────────
    function cleanupSwalDOM() {
        // Remove all residual swal2 containers and backdrops
        document.querySelectorAll('.swal2-container').forEach(el => {
            el.style.display = 'none';
            setTimeout(() => el.remove(), 50);
        });
        document.querySelectorAll('.swal2-backdrop').forEach(el => {
            el.style.display = 'none';
            setTimeout(() => el.remove(), 50);
        });
        // Reset body overflow
        document.body.style.overflow = 'auto';
        document.body.style.paddingRight = '0';
    }
    
    // MutationObserver to auto-clean leftover swal2 elements
    const observer = new MutationObserver(() => {
        const containers = document.querySelectorAll('.swal2-container.swal2-backdrop-hide');
        if (containers.length > 0) {
            containers.forEach(el => {
                if (el.style.display !== 'none') {
                    el.style.display = 'none';
                }
            });
        }
    });
    
    observer.observe(document.body, { 
        childList: true, 
        subtree: true,
        attributes: true,
        attributeFilter: ['style', 'class']
    });

    // ── Pick Winners ─────────────────────────────────────────
    function pickWinners(batch, prize, filtered) {
        // Fisher-Yates partial shuffle — O(batch) regardless of pool size
        let available = [...filtered];
        let winners   = [];

        for (let i = 0; i < batch; i++) {
            // Pick random index from remaining (i to end)
            const j = i + Math.floor(Math.random() * (available.length - i));
            // Swap to front
            [available[i], available[j]] = [available[j], available[i]];
            winners.push(available[i]);
        }

        saveHistory(winners, prize);

        Swal.fire({
            icon: 'success',
            title: '🎉 Congratulations!',
            html: `<strong style="color: #fbbf24; font-size: 1.1rem; text-shadow: 0 0 10px rgba(249,115,22,0.4);">Prize:</strong> <span style="color: #f0f9ff; font-size: 1.15rem; font-weight: 600;">${prize}</span><br><br>
                <strong style="color: #e2e8f0; display: block; margin: 20px 0 15px 0;">Winner${winners.length > 1 ? 's' : ''}:</strong>
                <ul style="text-align: center; list-style: none; padding: 0; margin: 0;">
                    ${winners.map(w => `<li style="margin: 10px 0; border-bottom: 1px solid rgba(79,70,229,0.2); line-height: 1.6;"><span style="color: #fbbf24; font-weight: 700; font-size: 1.1rem; display: block; word-wrap: break-word; overflow-wrap: break-word;">${w.name}</span><small style="color: #cbd5e1; font-size: 0.9rem; display: block; margin-top: 2px;">🆔 ${w.account_no} · 📍 ${w.municipality}</small></li>`).join('')}
                </ul>`,
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didClose: () => {
                cleanupSwalDOM();
            }
        }).then((result) => {
            if (result.isConfirmed || result.dismiss) {
                showRemoveConfirmation(winners);
            }
        }).catch((error) => {
            console.error('Error in congratulations modal:', error);
            cleanupSwalDOM();
        });
    }

    // ── Remove Confirmation Dialog ────────────────────────────
    function showRemoveConfirmation(winners) {
        Swal.fire({
            title: 'Remove Winners?',
            text: 'Remove winners from the spin pool?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove',
            cancelButtonText: 'No',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didClose: () => {
                cleanupSwalDOM();
            }
        }).then((result) => {
            if (result.isConfirmed) {
                try {
                    const winnerNos = new Set(winners.map(w => w.account_no));
                    pool = pool.filter(p => !winnerNos.has(p.account_no));
                    drawWheel(getWheelNames());
                    updateCount();
                    
                    // Auto-close success toast with no user interaction
                    Swal.fire({
                        title: '✅ Updated!',
                        text: 'Winners removed from the pool.',
                        icon: 'success',
                        timer: 1200,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didClose: () => {
                            cleanupSwalDOM();
                        }
                    });
                } catch (error) {
                    console.error('Error removing winners:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to remove winners: ' + error.message,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        didClose: () => {
                            cleanupSwalDOM();
                        }
                    });
                }
            } else {
                cleanupSwalDOM();
            }
        }).catch((error) => {
            console.error('Error in remove confirmation modal:', error);
            cleanupSwalDOM();
        });
    }

    document.getElementById("municipality").addEventListener("change", () => {
        drawWheel(getWheelNames());
        updateCount();
    });

    // Update updateCount() to reflect filtered count
    function updateCount() {
        document.getElementById("participantCount").textContent = getFilteredPool().length;
    }

    // ── API: Save History ─────────────────────────────────────
    async function saveHistory(winners, prize) {
        try {
            await fetch(BASE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF
                },
                body: JSON.stringify({ winners, prize })
            });
            renderHistory();
        } catch (e) {
            console.error('Failed to save history:', e);
        }
    }

    // ── API: Render History ───────────────────────────────────
    async function renderHistory() {
        try {
            const res  = await fetch(BASE_URL);
            const data = await res.json();
            const list = document.getElementById("historyList");
            
            // Safety check - historyList may not exist if commented out
            if (!list) return;
            
            list.innerHTML = "";

            if (!data.length) {
                list.innerHTML = '<li class="history-empty">No winners yet.</li>';
                return;
            }

            data.forEach(h => {
                const li = document.createElement("li");
                const dt = new Date(h.created_at).toLocaleString('en-PH', {
                    year: 'numeric', month: 'short', day: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });
                li.textContent = `${dt} — ${h.name} · ${h.account_no} (${h.prize})`;
                list.appendChild(li);
            });
        } catch (e) {
            console.error('Failed to load history:', e);
        }
    }

    // ── Toggle History ────────────────────────────────────────
    function toggleHistory() {
        const list = document.getElementById("historyList");
        if (!list) {
            console.warn('History list not found in DOM');
            return;
        }
        list.style.display = list.style.display === "block" ? "none" : "block";
    }

    // ── API: Export CSV ───────────────────────────────────────
    function exportCSV() {
        window.location.href = `${BASE_URL}/export`;
    }

    // ── API: Clear History ────────────────────────────────────
    async function clearHistory() {
        const result = await Swal.fire({
            title: 'Clear History?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, clear',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                await fetch(BASE_URL, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF }
                });
                renderHistory();
                Swal.fire('Cleared!', 'Winner history has been cleared.', 'success');
            } catch (e) {
                Swal.fire('Error', 'Could not clear history. Please try again.', 'error');
            }
        }
    }

    // Load history on page load
    renderHistory();
    
    // Cleanup any leftover SweetAlert DOM elements
    cleanupSwalDOM();
</script>

</body>
</html>