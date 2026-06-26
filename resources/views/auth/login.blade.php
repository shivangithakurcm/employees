<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Management System — Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #0b0e1a;
            --panel: #12162a;
            --panel-light: #181d35;
            --line: #262c4a;
            --text-dim: #8d93b0;
            --amber: #f5a623;
            --amber-deep: #d68a0f;
            --teal: #2dd4bf;
        }

        * { box-sizing: border-box; }
        html, body { margin: 0; height: 100%; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--ink);
            color: #fff;
            display: flex;
            min-height: 100vh;
        }

        /* ---------- LEFT: brand + pipeline visual ---------- */
        .brand-side {
            flex: 1.1;
            position: relative;
            background: radial-gradient(circle at 20% 15%, rgba(245,166,35,0.10), transparent 50%),
                        radial-gradient(circle at 80% 85%, rgba(45,212,191,0.08), transparent 50%),
                        var(--ink);
            padding: 56px 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            border-right: 1px solid var(--line);
        }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-mark .glyph {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(155deg, var(--amber), var(--amber-deep));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-mark .glyph svg { width: 20px; height: 20px; }

        .brand-mark span {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: -0.01em;
        }

        /* the signature element: animated funnel with flowing lead-particles */
        .funnel-wrap {
            position: relative;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px 0;
        }

        .funnel-svg { width: 100%; max-width: 420px; height: auto; }

        .stage-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.62rem;
            fill: var(--text-dim);
            letter-spacing: 0.06em;
        }

        .stage-line { stroke: var(--line); stroke-width: 1; }

        .particle {
            animation: flow 4.5s linear infinite;
            offset-rotate: 0deg;
        }
        .particle.p2 { animation-delay: 1.1s; }
        .particle.p3 { animation-delay: 2.2s; }
        .particle.p4 { animation-delay: 3.3s; }

        @keyframes flow {
            0%   { offset-distance: 0%; opacity: 0; }
            6%   { opacity: 1; }
            88%  { opacity: 1; }
            100% { offset-distance: 100%; opacity: 0; }
        }

        .headline {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1.6rem;
            line-height: 1.3;
            letter-spacing: -0.01em;
            max-width: 380px;
        }

        .headline .accent { color: var(--amber); }

        .subcopy {
            color: var(--text-dim);
            font-size: 0.92rem;
            margin-top: 10px;
            max-width: 360px;
            line-height: 1.5;
        }

        .stat-row {
            display: flex;
            gap: 28px;
            margin-top: 26px;
        }

        .stat-row .stat .num {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            font-size: 1.1rem;
            color: #fff;
        }
        .stat-row .stat .num.teal { color: var(--teal); }
        .stat-row .stat .num.amber { color: var(--amber); }

        .stat-row .stat .lbl {
            font-size: 0.7rem;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 2px;
        }

        /* ---------- RIGHT: form ---------- */
        .form-side {
            flex: 0.85;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: var(--panel);
        }

        .form-card {
            width: 100%;
            max-width: 360px;
            animation: rise 0.55s ease-out;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-card .eyebrow {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--amber);
            display: block;
            margin-bottom: 8px;
        }

        .form-card h2 {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 6px;
            letter-spacing: -0.01em;
        }

        .form-card .lede {
            color: var(--text-dim);
            font-size: 0.88rem;
            margin-bottom: 28px;
        }

        .form-label {
            color: var(--text-dim);
            font-size: 0.76rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .input-wrap { position: relative; }

        .input-wrap .icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-dim);
            pointer-events: none;
            transition: color 0.15s ease;
        }

        .input-wrap:focus-within .icon { color: var(--amber); }

        .form-control {
            background: var(--panel-light);
            border: 1px solid var(--line);
            color: #fff;
            padding: 11px 14px 11px 42px;
            border-radius: 9px;
            font-size: 0.95rem;
            transition: border-color 0.15s ease, background 0.15s ease;
        }

        .form-control::placeholder { color: #565c7c; }

        .form-control:focus {
            background: #1d2340;
            color: #fff;
            border-color: var(--amber);
            box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.14);
        }

        .mb-3 { margin-bottom: 18px !important; }

        .btn-login {
            background: linear-gradient(155deg, var(--amber), var(--amber-deep));
            color: var(--ink);
            font-weight: 700;
            font-size: 0.95rem;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 9px;
            cursor: pointer;
            letter-spacing: 0.01em;
            transition: transform 0.12s ease, box-shadow 0.12s ease, filter 0.12s ease;
        }

        .btn-login:hover {
            filter: brightness(1.05);
            box-shadow: 0 10px 24px rgba(245, 166, 35, 0.22);
        }

        .btn-login:active { transform: translateY(1px); }

        .alert-danger {
            background: rgba(220, 80, 80, 0.1);
            border: 1px solid rgba(220, 80, 80, 0.35);
            color: #ff9b9b;
            border-radius: 9px;
            font-size: 0.86rem;
            margin-bottom: 18px;
            padding: 10px 14px;
        }

        .foot-note {
            text-align: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            color: #565c7c;
            margin-top: 26px;
            letter-spacing: 0.02em;
        }

        @media (max-width: 900px) {
            .brand-side { display: none; }
            .form-side { flex: 1; }
        }

        @media (prefers-reduced-motion: reduce) {
            .form-card { animation: none; }
            .particle { animation: none; opacity: 0.6; }
        }
    </style>
</head>
<body>

    <!-- BRAND / SIGNATURE SIDE -->
    <div class="brand-side">
        <div class="brand-mark">
            <div class="glyph">
                <svg viewBox="0 0 24 24" fill="none" stroke="#0b0e1a" stroke-width="2.2">
                    <path d="M3 5h18M6 5v5.5L3 17h18l-3-6.5V5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <span>Lead Management System</span>
        </div>

        <div class="funnel-wrap">
            <svg class="funnel-svg" viewBox="0 0 420 300" xmlns="http://www.w3.org/2000/svg">
                <!-- funnel outline: 4 stages narrowing down -->
                <path d="M30 20 H 390" class="stage-line"/>
                <path d="M70 100 H 350" class="stage-line"/>
                <path d="M120 180 H 300" class="stage-line"/>
                <path d="M170 260 H 250" class="stage-line"/>

                <path d="M30 20 L70 100 L120 180 L170 260" stroke="#262c4a" stroke-width="1.4" fill="none"/>
                <path d="M390 20 L350 100 L300 180 L250 260" stroke="#262c4a" stroke-width="1.4" fill="none"/>

                <text x="30" y="14" class="stage-label">NEW LEAD</text>
                <text x="70" y="94" class="stage-label">CONTACTED</text>
                <text x="120" y="174" class="stage-label">QUALIFIED</text>
                <text x="170" y="254" class="stage-label">WON</text>

                <!-- flowing particles representing leads moving through the pipeline -->
                <circle r="4.5" fill="#f5a623" class="particle p1" style="offset-path: path('M 50 22 C 120 95, 270 95, 330 165 C 290 205, 220 235, 205 275');"/>
                <circle r="4" fill="#2dd4bf" class="particle p2" style="offset-path: path('M 150 22 C 190 95, 250 95, 290 165 C 270 205, 230 235, 210 275');"/>
                <circle r="4.5" fill="#f5a623" class="particle p3" style="offset-path: path('M 250 22 C 250 95, 290 95, 310 165 C 280 205, 225 235, 208 275');"/>
                <circle r="4" fill="#2dd4bf" class="particle p4" style="offset-path: path('M 90 22 C 150 95, 260 95, 320 165 C 285 200, 222 232, 207 275');"/>
            </svg>
        </div>

        <div>
            <div class="headline">Every lead, tracked from <span class="accent">first contact</span> to closed deal.</div>
            <div class="subcopy">Sign in to manage your pipeline, follow up on time, and see exactly where every deal stands.</div>
            <div class="stat-row">
                <div class="stat">
                    <div class="num amber">04</div>
                    <div class="lbl">Pipeline stages</div>
                </div>
                <div class="stat">
                    <div class="num teal">Live</div>
                    <div class="lbl">Follow-up tracking</div>
                </div>
                <div class="stat">
                    <div class="num">1</div>
                    <div class="lbl">Place for your team</div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM SIDE -->
    <div class="form-side">
        <div class="form-card">
            <span class="eyebrow">Lead Management System</span>
            <h2>Welcome back</h2>
            <div class="lede">Sign in with the email your admin set up for you.</div>

            @if($errors->any())
                <div class="alert alert-danger py-2">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-wrap">
                        <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16v16H4V4z" stroke-linejoin="round"/>
                            <path d="M4 6l8 7 8-7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <input type="email"
                               name="email"
                               class="form-control"
                               placeholder="you@company.com"
                               value="{{ old('email') }}"
                               required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-wrap">
                        <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="5" y="11" width="14" height="9" rx="2"/>
                            <path d="M8 11V7a4 4 0 1 1 8 0v4"/>
                        </svg>
                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="••••••••"
                               required>
                    </div>
                </div>
                <button type="submit" class="btn-login mt-2">Sign in</button>
            </form>

            <div class="foot-note">ADMIN &amp; EMPLOYEE ACCESS · ONE LOGIN</div>
        </div>
    </div>

</body>
</html>