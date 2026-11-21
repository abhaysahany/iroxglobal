<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('detailsApp.name') }} - Member Growth Platform</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@600;700&display=swap"
        rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('uploads/logo/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('uploads/logo/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('uploads/logo/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('uploads/logo/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('uploads/logo/favicon/site.webmanifest') }}" />

    <style>
        :root {
            --green: #48a51f;
            --green-dark: #1f6e25;
            --mint: #9be56a;
            --cyan: #22b8cf;
            --gold: #d9aa3f;
            --ink: #11220e;
            --muted: #63745c;
            --soft: #f4fbef;
            --white: #ffffff;
            --line: rgba(59, 119, 38, .16);
            --glass: rgba(255, 255, 255, .74);
            --glass-strong: rgba(255, 255, 255, .9);
            --shadow: 0 26px 80px rgba(45, 105, 28, .16);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: "Inter", system-ui, sans-serif;
            color: var(--ink);
            background: linear-gradient(180deg, #f8fdf5 0%, #eef8eb 46%, #f9fcf5 100%);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        img {
            max-width: 100%;
            display: block;
        }

        .site-shell {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
        }

        .site-shell::before {
            content: "";
            position: fixed;
            inset: -8% -6%;
            pointer-events: none;
            background-image: url("{{ asset('member/assets/images/leaves.png') }}");
            background-size: cover;
            background-position: center;
            opacity: .18;
            z-index: 0;
            animation: leafDrift 18s ease-in-out infinite alternate;
        }

        .nav-wrap {
            position: fixed;
            top: 18px;
            left: 50%;
            z-index: 20;
            width: min(1180px, calc(100% - 28px));
            transform: translateX(-50%);
        }

        .navbar-panel {
            min-height: 72px;
            padding: 10px 12px 10px 20px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: rgba(255, 255, 255, .78);
            box-shadow: 0 18px 54px rgba(49, 108, 31, .13);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--ink);
            font-weight: 900;
        }

        .brand-logo img {
            width: 152px;
        }

        .nav-links {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .nav-links a {
            display: inline-flex;
            align-items: center;
            min-height: 40px;
            padding: 0 13px;
            color: var(--muted);
            border-radius: 999px;
            font-size: 14px;
            font-weight: 800;
            transition: .2s ease;
        }

        .nav-links a:hover {
            color: var(--green-dark);
            background: rgba(72, 165, 31, .1);
        }

        .btn-main,
        .btn-soft {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 46px;
            padding: 0 18px;
            border-radius: 999px;
            font-weight: 900;
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
            white-space: nowrap;
        }

        .btn-main {
            color: #fff;
            background: linear-gradient(135deg, var(--green), var(--green-dark));
            box-shadow: 0 16px 34px rgba(72, 165, 31, .26);
        }

        .btn-soft {
            color: var(--green-dark);
            background: rgba(255, 255, 255, .7);
            border: 1px solid var(--line);
        }

        .btn-main:hover,
        .btn-soft:hover {
            transform: translateY(-2px);
            color: #fff;
            box-shadow: 0 18px 42px rgba(72, 165, 31, .28);
        }

        .btn-soft:hover {
            color: var(--green-dark);
            background: #fff;
        }

        .hero {
            position: relative;
            z-index: 1;
            min-height: 92vh;
            padding: 120px 0 44px;
            display: flex;
            align-items: center;
            background:
                linear-gradient(90deg, rgba(248, 253, 245, .95) 0%, rgba(248, 253, 245, .72) 44%, rgba(248, 253, 245, .42) 100%),
                url("{{ asset('member/assets/images/bg-login-light.jpg') }}") center / cover no-repeat;
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: auto 0 0;
            height: 130px;
            background: linear-gradient(180deg, transparent, #f2faee);
            pointer-events: none;
        }

        .hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: minmax(0, .96fr) minmax(380px, .82fr);
            gap: clamp(28px, 5vw, 74px);
            align-items: center;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 16px;
            padding: 9px 13px;
            border: 1px solid var(--line);
            border-radius: 999px;
            color: var(--green-dark);
            background: rgba(255, 255, 255, .68);
            box-shadow: 0 14px 30px rgba(49, 108, 31, .12);
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .eyebrow i {
            color: var(--gold);
        }

        h1,
        h2,
        h3 {
            font-family: "Space Grotesk", "Inter", sans-serif;
            letter-spacing: 0;
        }

        .hero h1 {
            max-width: 760px;
            margin: 0 0 18px;
            font-size: clamp(42px, 6vw, 82px);
            line-height: .98;
            font-weight: 700;
        }

        .hero h1 span {
            color: var(--green);
        }

        .hero-copy {
            max-width: 600px;
            margin: 0 0 28px;
            color: #40543b;
            font-size: clamp(16px, 1.4vw, 21px);
            line-height: 1.65;
            font-weight: 600;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 28px;
        }

        .hero-proof {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            max-width: 640px;
        }

        .proof-card,
        .glass-card {
            border: 1px solid var(--line);
            background: var(--glass);
            box-shadow: var(--shadow);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .proof-card {
            min-height: 92px;
            padding: 16px;
            border-radius: 18px;
        }

        .proof-card i {
            color: var(--green);
            margin-bottom: 8px;
        }

        .proof-card strong {
            display: block;
            font-size: 26px;
            line-height: 1;
        }

        .proof-card span {
            color: var(--muted);
            font-size: 13px;
            font-weight: 800;
        }

        .dashboard-preview {
            position: relative;
            padding: 18px;
            border-radius: 28px;
            background: rgba(255, 255, 255, .7);
            border: 1px solid var(--line);
            box-shadow: 0 30px 90px rgba(47, 111, 20, .22);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }

        .preview-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
        }

        .preview-title small,
        .mini-card small {
            color: var(--muted);
            font-weight: 800;
        }

        .preview-title strong {
            display: block;
            font-size: 22px;
        }

        .status-pill {
            align-self: flex-start;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 11px;
            border-radius: 999px;
            color: var(--green-dark);
            background: rgba(72, 165, 31, .12);
            font-size: 12px;
            font-weight: 900;
        }

        .status-pill::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 0 6px rgba(72, 165, 31, .13);
        }

        .preview-metrics {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .mini-card {
            min-height: 122px;
            padding: 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, .66);
            border: 1px solid var(--line);
        }

        .mini-card i {
            color: var(--green);
            font-size: 20px;
        }

        .mini-card strong {
            display: block;
            margin-top: 12px;
            font-size: 26px;
        }

        .growth-panel {
            margin-top: 12px;
            padding: 16px;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(72, 165, 31, .13), rgba(34, 184, 207, .11));
            border: 1px solid var(--line);
        }

        .growth-bars {
            display: grid;
            gap: 10px;
            margin-top: 14px;
        }

        .growth-bars span {
            display: block;
            height: 10px;
            border-radius: 999px;
            background: rgba(72, 165, 31, .15);
            overflow: hidden;
        }

        .growth-bars span::before {
            content: "";
            display: block;
            height: 100%;
            width: var(--w);
            border-radius: inherit;
            background: linear-gradient(90deg, var(--green), var(--cyan));
        }

        .section-pad {
            position: relative;
            z-index: 1;
            padding: 86px 0;
        }

        .section-head {
            max-width: 720px;
            margin-bottom: 34px;
        }

        .section-head.center {
            margin-inline: auto;
            text-align: center;
        }

        .section-head h2 {
            margin: 0 0 12px;
            font-size: clamp(30px, 4vw, 52px);
        }

        .section-head p {
            margin: 0;
            color: var(--muted);
            font-size: 17px;
            line-height: 1.65;
            font-weight: 600;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .feature-card {
            min-height: 260px;
            padding: 24px;
            border-radius: 22px;
            transition: transform .22s ease, box-shadow .22s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 54px;
            height: 54px;
            display: grid;
            place-items: center;
            margin-bottom: 24px;
            border-radius: 16px;
            color: #fff;
            background: linear-gradient(135deg, var(--green), var(--green-dark));
            box-shadow: 0 18px 34px rgba(72, 165, 31, .22);
            font-size: 21px;
        }

        .feature-card h3 {
            margin: 0 0 10px;
            font-size: 23px;
        }

        .feature-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.65;
            font-weight: 600;
        }

        .split-band {
            background:
                linear-gradient(135deg, rgba(255, 255, 255, .82), rgba(237, 248, 232, .86)),
                url("{{ asset('member/assets/images/world-map-green.png') }}") center / contain no-repeat;
        }

        .platform-grid {
            display: grid;
            grid-template-columns: minmax(0, .84fr) minmax(0, 1fr);
            gap: 34px;
            align-items: center;
        }

        .visual-frame {
            min-height: 430px;
            border-radius: 28px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, .18), rgba(255, 255, 255, .76)),
                url("{{ asset('member/assets/images/tree-light.png') }}") center bottom / contain no-repeat;
            border: 1px solid var(--line);
            box-shadow: var(--shadow);
        }

        .check-list {
            display: grid;
            gap: 14px;
            margin-top: 24px;
        }

        .check-list div {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            color: #33452f;
            font-weight: 800;
        }

        .check-list i {
            color: var(--green);
            margin-top: 3px;
        }

        .process-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .process-card {
            padding: 22px;
            border-radius: 20px;
            min-height: 210px;
        }

        .process-card strong {
            display: inline-grid;
            place-items: center;
            width: 42px;
            height: 42px;
            margin-bottom: 18px;
            border-radius: 50%;
            color: #fff;
            background: var(--green-dark);
        }

        .cta-band {
            position: relative;
            z-index: 1;
            padding: 82px 0;
            background:
                linear-gradient(135deg, rgba(22, 87, 28, .9), rgba(7, 51, 42, .92)),
                url("{{ asset('member/assets/images/bg-dashboard-light.jpg') }}") center / cover no-repeat;
            color: #fff;
        }

        .cta-panel {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 24px;
            align-items: center;
        }

        .cta-panel h2 {
            margin: 0 0 10px;
            font-size: clamp(30px, 4vw, 54px);
        }

        .cta-panel p {
            max-width: 660px;
            margin: 0;
            color: rgba(255, 255, 255, .78);
            font-weight: 600;
            line-height: 1.65;
        }

        footer {
            position: relative;
            z-index: 1;
            padding: 30px 0;
            color: var(--muted);
            font-weight: 700;
            background: #f8fcf4;
        }

        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .6s ease, transform .6s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: none;
        }

        @keyframes leafDrift {
            from {
                transform: translate3d(-1%, -1%, 0) scale(1.02);
            }

            to {
                transform: translate3d(1%, 1%, 0) scale(1.04);
            }
        }

        @media (max-width: 991px) {
            .nav-links {
                display: none;
            }

            .hero {
                min-height: auto;
                padding-top: 116px;
            }

            .hero-grid,
            .platform-grid,
            .cta-panel {
                grid-template-columns: 1fr;
            }

            .feature-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .process-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .navbar-panel {
                padding: 10px;
            }

            .brand-logo img {
                width: 126px;
            }

            .nav-actions .btn-soft {
                display: none;
            }

            .hero-proof,
            .feature-grid,
            .process-grid,
            .preview-metrics {
                grid-template-columns: 1fr;
            }

            .hero {
                padding-top: 106px;
            }

            .hero-actions .btn-main,
            .hero-actions .btn-soft {
                width: 100%;
            }

            .section-pad {
                padding: 64px 0;
            }

            .visual-frame {
                min-height: 320px;
            }
        }
    </style>
</head>

<body>
    <div class="site-shell">
        <header class="nav-wrap">
            <div class="navbar-panel d-flex align-items-center justify-content-between gap-3">
                <a href="#top" class="brand-logo"style="width:100px !important"
                    aria-label="{{ config('detailsApp.name') }} home">
                    <img src="{{ asset('uploads/logo/logo-greenlife.png') }}" alt="logo">
                </a>

                <ul class="nav-links">
                    <li><a href="#platform">Platform</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#process">Process</a></li>
                    <li><a href="#security">Security</a></li>
                </ul>

                <div class="nav-actions d-flex align-items-center gap-2">
                    <a href="{{ route('member.login') }}" class="btn-soft">Login</a>
                    <a href="{{ route('member.signup') }}" class="btn-main">Join Now <i
                            class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </header>

        <main id="top">
            <section class="hero">
                <div class="container">
                    <div class="hero-grid">
                        <div class="hero-content reveal">
                            <span class="eyebrow"><i class="fa-solid fa-seedling"></i>
                                {{ config('detailsApp.name') }}</span>
                            <h1>Grow Your Member Network With A Smarter <span>Digital Platform</span></h1>
                            <p class="hero-copy">A clean, secure and modern member system for onboarding, wallet
                                visibility, income tracking and referral growth, designed with the same premium identity
                                as your login panel.</p>
                            <div class="hero-actions">
                                <a href="{{ route('member.signup') }}" class="btn-main">Create Member Account <i
                                        class="fa-solid fa-user-plus"></i></a>
                                <a href="{{ route('member.login') }}" class="btn-soft">Member Login <i
                                        class="fa-solid fa-right-to-bracket"></i></a>
                            </div>
                            <div class="hero-proof">
                                <div class="proof-card"><i
                                        class="fa-solid fa-users"></i><strong>25K+</strong><span>Members</span></div>
                                <div class="proof-card"><i
                                        class="fa-solid fa-earth-americas"></i><strong>120+</strong><span>Countries</span>
                                </div>
                                <div class="proof-card"><i
                                        class="fa-solid fa-shield-halved"></i><strong>24/7</strong><span>Protected
                                        Access</span></div>
                            </div>
                        </div>

                        <div class="dashboard-preview reveal" aria-label="Dashboard preview">
                            <div class="preview-top">
                                <div class="preview-title">
                                    <small>Member Overview</small>
                                    <strong>Network Growth</strong>
                                </div>
                                <span class="status-pill">Live Panel</span>
                            </div>
                            <div class="preview-metrics">
                                <div class="mini-card"><i
                                        class="fa-solid fa-wallet"></i><strong>$98M+</strong><small>Total
                                        payouts</small></div>
                                <div class="mini-card"><i
                                        class="fa-solid fa-chart-line"></i><strong>10Y+</strong><small>Growth
                                        journey</small></div>
                                <div class="mini-card"><i
                                        class="fa-solid fa-ranking-star"></i><strong>Rank</strong><small>Achievement
                                        tracking</small></div>
                                <div class="mini-card"><i
                                        class="fa-solid fa-network-wired"></i><strong>Team</strong><small>Referral
                                        visibility</small></div>
                            </div>
                            <div class="growth-panel">
                                <div class="d-flex justify-content-between gap-3">
                                    <strong>Monthly Growth</strong>
                                    <span class="text-success fw-bold">+38%</span>
                                </div>
                                <div class="growth-bars">
                                    <span style="--w: 72%"></span>
                                    <span style="--w: 88%"></span>
                                    <span style="--w: 61%"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="features" class="section-pad">
                <div class="container">
                    <div class="section-head center reveal">
                        <h2>Frontend Built For Trust And Action</h2>
                        <p>Every section is designed to make visitors understand the platform quickly and move toward
                            signup or member login with confidence.</p>
                    </div>
                    <div class="feature-grid">
                        <article class="feature-card glass-card reveal">
                            <div class="feature-icon"><i class="fa-solid fa-user-check"></i></div>
                            <h3>Simple Onboarding</h3>
                            <p>Clear signup paths, sponsor-friendly flow and member-first messaging help users start
                                without confusion.</p>
                        </article>
                        <article class="feature-card glass-card reveal">
                            <div class="feature-icon"><i class="fa-solid fa-wallet"></i></div>
                            <h3>Wallet Ready</h3>
                            <p>Frontend language aligns with dashboard features like income wallet, payout history and
                                fund movement.</p>
                        </article>
                        <article class="feature-card glass-card reveal">
                            <div class="feature-icon"><i class="fa-solid fa-sitemap"></i></div>
                            <h3>Network Focused</h3>
                            <p>Promotes direct members, levels, genealogy and team growth in a polished visual
                                structure.</p>
                        </article>
                        <article class="feature-card glass-card reveal">
                            <div class="feature-icon"><i class="fa-solid fa-lock"></i></div>
                            <h3>Secure Access</h3>
                            <p>Login and signup CTAs are always nearby, with trust messaging around verified and
                                protected access.</p>
                        </article>
                        <article class="feature-card glass-card reveal">
                            <div class="feature-icon"><i class="fa-solid fa-award"></i></div>
                            <h3>Ranks And Rewards</h3>
                            <p>Achievement cards communicate progress, recognition and long-term participation.</p>
                        </article>
                        <article class="feature-card glass-card reveal">
                            <div class="feature-icon"><i class="fa-solid fa-headset"></i></div>
                            <h3>Member Support</h3>
                            <p>Support-focused content prepares users for ticketing, communication and service
                                confidence.</p>
                        </article>
                    </div>
                </div>
            </section>

            <section id="platform" class="section-pad split-band">
                <div class="container">
                    <div class="platform-grid">
                        <div class="visual-frame reveal"></div>
                        <div class="reveal">
                            <div class="section-head">
                                <h2>A Natural Extension Of Your Member Panel</h2>
                                <p>The public frontend uses the same GreenLife visual language as your login page: soft
                                    nature imagery, glass surfaces, green highlights and professional CTAs.</p>
                            </div>
                            <div class="check-list">
                                <div><i class="fa-solid fa-circle-check"></i><span>Homepage directly connects to member
                                        login and registration routes.</span></div>
                                <div><i class="fa-solid fa-circle-check"></i><span>Responsive design for desktop,
                                        tablet and mobile screens.</span></div>
                                <div><i class="fa-solid fa-circle-check"></i><span>Uses existing project images, logo
                                        and Laravel Blade helpers.</span></div>
                                <div><i class="fa-solid fa-circle-check"></i><span>Professional content blocks for
                                        trust, growth and platform features.</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="process" class="section-pad">
                <div class="container">
                    <div class="section-head center reveal">
                        <h2>Clear Member Journey</h2>
                        <p>The frontend guides visitors from discovery to signup, then into the member panel.</p>
                    </div>
                    <div class="process-grid">
                        <div class="process-card glass-card reveal"><strong>1</strong>
                            <h3>Visit</h3>
                            <p class="text-muted fw-semibold">Understand the platform through clean sections and stats.
                            </p>
                        </div>
                        <div class="process-card glass-card reveal"><strong>2</strong>
                            <h3>Register</h3>
                            <p class="text-muted fw-semibold">Create a member account using the connected signup route.
                            </p>
                        </div>
                        <div class="process-card glass-card reveal"><strong>3</strong>
                            <h3>Activate</h3>
                            <p class="text-muted fw-semibold">Enter the dashboard to manage profile, wallet and package
                                activity.</p>
                        </div>
                        <div class="process-card glass-card reveal"><strong>4</strong>
                            <h3>Grow</h3>
                            <p class="text-muted fw-semibold">Track team growth, incomes, rewards and support from the
                                panel.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="security" class="cta-band">
                <div class="container">
                    <div class="cta-panel reveal">
                        <div>
                            <h2>Ready To Grow With {{ config('detailsApp.name') }}?</h2>
                            <p>Start from a polished frontend, move into a secure member login, and manage your complete
                                network journey from the dashboard.</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                            <a href="{{ route('member.signup') }}" class="btn-main">Create Account</a>
                            <a href="{{ route('member.login') }}" class="btn-soft">Login Now</a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
                <span>© {{ date('Y') }} {{ config('detailsApp.name') }}. All rights reserved.</span>
                <span>Secure platform for member growth.</span>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var items = document.querySelectorAll('.reveal');
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.15
            });

            items.forEach(function(item) {
                observer.observe(item);
            });
        });
    </script>
</body>

</html>
