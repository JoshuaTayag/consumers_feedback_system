<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>45th AGMA Raffle Winners</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg:        #07101f;
            --surface:   #0d1b35;
            --surface2:  #112247;
            --border:    #1e3460;
            --accent:    #3b82f6;
            --gold:      #f0c040;
            --gold-dim:  #c49a1a;
            --text:      #e2e8f0;
            --text-dim:  #8eaac8;
            --radius:    14px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 48px 20px 64px;
        }

        /* ── Header ─────────────────────────────────────── */
        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header .eyebrow {
            display: inline-block;
            font-family: 'Outfit', sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--gold);
            background: rgba(240,192,64,0.1);
            border: 1px solid rgba(240,192,64,0.25);
            border-radius: 100px;
            padding: 5px 16px;
            margin-bottom: 16px;
        }

        .page-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #f8fafc;
            line-height: 1.15;
        }

        .page-header h1 span {
            background: linear-gradient(90deg, var(--gold), #ffe082);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-header .subtitle {
            margin-top: 10px;
            font-size: 0.92rem;
            color: var(--text-dim);
            letter-spacing: 0.01em;
        }

        /* ── Card ────────────────────────────────────────── */
        .card {
            max-width: 90%;
            margin: 0 auto;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 24px 80px rgba(0,0,0,0.45);
        }

        /* ── Table ───────────────────────────────────────── */
        .table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
        }

        thead th {
            font-family: 'Outfit', sans-serif;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--text-dim);
            padding: 16px 20px;
            white-space: nowrap;
            text-align: left;
        }

        thead th:first-child { padding-left: 24px; }
        thead th:last-child  { padding-right: 24px; }

        tbody tr {
            border-bottom: 1px solid rgba(30, 52, 96, 0.5);
            transition: background 0.15s ease;
        }

        tbody tr:last-child { border-bottom: none; }

        tbody tr:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        tbody td {
            padding: 15px 20px;
            font-size: 0.9rem;
            color: var(--text);
            vertical-align: middle;
        }

        tbody td:first-child { padding-left: 24px; }
        tbody td:last-child  { padding-right: 24px; }

        /* rank number column */
        .rank {
            font-family: 'Outfit', sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-dim);
            letter-spacing: 0.05em;
            min-width: 36px;
        }

        /* account no */
        .acct {
            font-family: 'Outfit', sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--accent);
            letter-spacing: 0.03em;
        }

        /* name */
        .name {
            font-weight: 500;
            white-space: nowrap;
        }

        .actions {
            color: var(--text-dim);
            text-align: center;
        }

        /* address */
        .address {
            color: var(--text-dim);
            font-size: 0.85rem;
        }

        /* prize badge */
        .prize-badge {
            display: inline-block;
            font-family: 'Outfit', sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--gold);
            background: rgba(240, 192, 64, 0.1);
            border: 1px solid rgba(240, 192, 64, 0.22);
            border-radius: 6px;
            padding: 4px 12px;
            white-space: nowrap;
        }

        /* ── Footer count ────────────────────────────────── */
        .card-footer {
            padding: 14px 24px;
            background: var(--surface2);
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .card-footer span {
            font-size: 0.78rem;
            color: var(--text-dim);
        }

        .card-footer strong {
            font-family: 'Outfit', sans-serif;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text);
        }

        /* ── Empty state ─────────────────────────────────── */
        .empty {
            text-align: center;
            padding: 60px 24px;
            color: var(--text-dim);
            font-size: 0.9rem;
        }

        /* ── Responsive ──────────────────────────────────── */
        @media (max-width: 600px) {
            .page-header h1 { font-size: 1.6rem; }
            tbody td, thead th { padding: 12px 14px; }
            tbody td:first-child, thead th:first-child { padding-left: 16px; }
            tbody td:last-child, thead th:last-child   { padding-right: 16px; }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <header class="page-header">
        <div class="eyebrow">🏆 Official Raffle Results</div>
        <h1>45th AGMA <span>Winners</span></h1>
        <p class="subtitle">Congratulations to all prize recipients</p>
    </header>

    <div class="card">
        @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Done!',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false,
                });
            </script>
        @endif
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Account No</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Municipality</th>
                        <th>Prize</th>
                        <th class="actions">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($winners as $i => $winner)
                        <tr>
                            <td class="rank">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td><span class="acct">{{ $winner['account_no'] }}</span></td>
                            <td class="name">{{ $winner['name'] }}</td>
                            <td class="address">{{ $winner['address'] }}</td>
                            <td class="municipality">{{ $winner['municipality'] }}</td>
                            <td><span class="prize-badge">{{ $winner['prize'] }}</span></td>
                            <td class="actions">
                                <form method="POST" action="{{ route('raffle.winner.destroy', $winner['id']) }}" class="delete-form">
                                    @method('DELETE')
                                    @csrf
                                    <button type="button" class="delete-btn" style="background:none;border:none;color:var(--text-dim);cursor:pointer;">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty">No winners have been recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(count($winners) > 0)
        <div class="card-footer">
            <a href="{{ route('raffle.winners.export') }}" 
            style="display:inline-flex;align-items:center;gap:8px;background:#0F6E56;color:#9FE1CB;padding:10px 18px;border-radius:8px;font-size:14px;font-weight:500;text-decoration:none;">
                <i class="ti ti-download"></i> Export CSV
            </a>
            <span>Total winners:</span>
            <strong>{{ count($winners) }}</strong>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.delete-btn');
            if (!btn) return;

            e.preventDefault();  // ← block any default form trigger
            e.stopPropagation(); // ← stop event bubbling up to form

            const form = btn.closest('.delete-form');

            Swal.fire({
                title: 'Remove winner?',
                text: 'This will permanently delete this winner record.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ef4444',
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>