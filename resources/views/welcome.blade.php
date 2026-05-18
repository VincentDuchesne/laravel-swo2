<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SWO2 — Hostingplatform Voortgang</title>
    <meta name="description" content="Voortgangsdemo van het SWO2 hostingplatform: PHP-hosting, automatisatie, monitoring en CIS Controls.">
    <style>
        :root {
            --bg: #0b1020;
            --bg-soft: #11172e;
            --panel: #161d38;
            --border: #232b4d;
            --text: #e8ecf7;
            --muted: #9aa3c7;
            --accent: #6ea8ff;
            --accent-2: #8affc1;
            --warn: #ffd166;
            --danger: #ff7a90;
        }
        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Inter, Roboto, sans-serif;
            background: radial-gradient(1200px 600px at 10% -10%, #1b2550 0%, transparent 60%),
                        radial-gradient(900px 500px at 110% 10%, #1a3a4d 0%, transparent 55%),
                        var(--bg);
            color: var(--text);
            line-height: 1.65;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(120deg, rgba(110,168,255,0.04), transparent 25%, rgba(138,255,193,0.03));
            pointer-events: none;
        }
        a { color: var(--accent); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 24px; position: relative; }

        header.hero {
            padding: 92px 0 56px;
            position: relative;
        }
        header.hero .container { display: grid; gap: 28px; }
        header.hero::after {
            content: '';
            position: absolute;
            top: 12%; right: -8%; width: 420px; height: 420px;
            background: radial-gradient(circle, rgba(138,255,193,0.18), transparent 62%);
            filter: blur(16px);
            pointer-events: none;
        }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 12px; letter-spacing: 0.18em; text-transform: uppercase;
            color: var(--accent-2);
            padding: 8px 14px;
            border: 1px solid rgba(138,255,193,0.25);
            border-radius: 999px;
            background: rgba(138,255,193,0.08);
        }
        .eyebrow::before {
            content: '✓';
            font-size: 12px;
            color: var(--accent-2);
        }
        h1 {
            font-size: clamp(38px, 6vw, 62px);
            line-height: 1.02;
            margin: 20px 0 18px;
            letter-spacing: -0.03em;
            max-width: 760px;
        }
        .lede { font-size: 18px; color: var(--muted); max-width: 740px; margin-top: 14px; }
        .cta-row { margin-top: 32px; display: flex; gap: 14px; flex-wrap: wrap; justify-content: flex-start; }
        .btn {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 14px 20px; border-radius: 999px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #061229; font-weight: 700;
            border: 1px solid transparent;
            box-shadow: 0 16px 36px rgba(110,168,255,0.22);
        }
        .btn.secondary {
            background: rgba(255,255,255,0.06);
            color: var(--text);
            border-color: rgba(255,255,255,0.08);
            box-shadow: none;
        }
        .btn:hover { transform: translateY(-1px); }

        .hero-panel {
            margin-top: 38px;
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(280px, 0.9fr);
            gap: 26px;
            align-items: start;
        }
        .hero-panel .panel-card { min-height: 390px; }
        .hero-panel .panel-card:nth-child(2) { min-height: 420px; }
        .panel-card {
            position: relative;
            padding: 28px;
            border-radius: 24px;
            background: rgba(13,18,34,0.95);
            border: 1px solid rgba(255,255,255,0.08);
            overflow: hidden;
        }
        .timeline-grid { display: grid; gap: 14px; grid-template-columns: repeat(2, minmax(0, 1fr)); margin-top: 24px; }
        @media (max-width: 920px) { .timeline-grid { grid-template-columns: 1fr; } }
        .panel-card::after {
            content: '';
            position: absolute;
            top: -26px; right: -26px;
            width: 210px; height: 210px;
            background: radial-gradient(circle, rgba(110,168,255,0.14), transparent 58%);
            pointer-events: none;
        }
        .panel-card h2 { margin: 0 0 10px; font-size: 22px; }
        .panel-card p { color: var(--muted); margin: 0; }
        .progress-pill {
            display: inline-flex; align-items: center; gap: 10px;
            margin-top: 18px; margin-bottom: 20px;
            padding: 10px 14px; border-radius: 999px;
            background: rgba(110,168,255,0.1);
            border: 1px solid rgba(110,168,255,0.18);
            font-size: 13px; letter-spacing: 0.08em; text-transform: uppercase;
        }
        .progress-pill strong { color: var(--accent-2); }
        .progress-bar {
            margin-top: 18px;
            height: 14px;
            border-radius: 999px;
            background: rgba(255,255,255,0.06);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .progress-bar span {
            display: block;
            height: 100%;
            width: 82%;
            background: linear-gradient(90deg, var(--accent), var(--accent-2));
            box-shadow: 0 0 22px rgba(110,168,255,0.36);
        }
        .milestone-strip { margin-top: 24px; display: flex; flex-wrap: wrap; gap: 12px; }
        .milestone-chip {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 12px 16px;
            border-radius: 999px;
            background: rgba(20,32,66,0.92);
            border: 1px solid rgba(255,255,255,0.06);
            color: var(--text); font-size: 14px; font-weight: 600;
        }
        .milestone-chip span {
            width: 10px; height: 10px;
            border-radius: 999px;
            background: var(--accent-2);
            box-shadow: 0 0 16px rgba(138,255,193,0.55);
        }
        .panel-card .kpis {
            display: grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: 14px;
            margin-top: 24px;
        }
        .panel-card .kpi {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            padding: 18px;
            border-radius: 18px;
            text-align: center;
        }
        .panel-card .kpi .n { font-size: 26px; font-weight: 700; }
        .panel-card .kpi .l { margin-top: 6px; color: var(--muted); font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em; }

        section { padding: 82px 0; border-bottom: 1px solid rgba(255,255,255,0.06); }
        section:nth-of-type(even) { background: rgba(255,255,255,0.02); }
        section .container { display: grid; gap: 24px; }
        section h2 { font-size: 30px; margin: 0 0 10px; letter-spacing: -0.02em; }
        section .sub { color: var(--muted); margin-bottom: 30px; max-width: 760px; line-height: 1.75; }

        .grid { display: grid; gap: 22px; }
        .grid.cols-2 { grid-template-columns: repeat(2, minmax(0,1fr)); }
        .grid.cols-3 { grid-template-columns: repeat(3, minmax(0,1fr)); }
        .grid.cols-4 { grid-template-columns: repeat(4, minmax(0,1fr)); }
        @media (max-width: 920px) {
            .hero-panel { grid-template-columns: 1fr; }
            .timeline { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .panel-card:nth-child(2) { min-height: auto; }
        }
        @media (max-width: 760px) {
            .grid.cols-2, .grid.cols-3, .grid.cols-4 { grid-template-columns: 1fr; }
            .timeline { grid-template-columns: 1fr; }
        }

        .card {
            background: rgba(13,18,34,0.97);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 22px;
            padding: 28px;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.14);
        }
        .card + .card { margin-top: 24px; }
        footer .container { display: grid; gap: 8px; }
        footer { border-top: 1px solid rgba(255,255,255,0.08); }
        .card h3 { margin: 0 0 10px; font-size: 19px; }
        .card p { margin: 0; color: var(--muted); font-size: 15px; }
        .tag { display: inline-block; font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase; color: var(--accent); background: rgba(110,168,255,0.1); border: 1px solid rgba(110,168,255,0.25); padding: 5px 10px; border-radius: 10px; margin-bottom: 14px; }
        .tag.milestone { color: var(--accent-2); background: rgba(138,255,193,0.08); border-color: rgba(138,255,193,0.16); }

        .screenshot {
            margin-top: 32px;
            border: 1px dashed rgba(255,255,255,0.10);
            border-radius: 22px;
            overflow: hidden;
            background: rgba(10,14,28,0.96);
            min-height: 420px;
            display: grid;
            place-items: center;
            padding: 18px;
            color: var(--muted);
            position: relative;
        }
        .screenshot img {
            width: 100%;
            max-width: 100%;
            height: auto;
            object-fit: contain;
            display: block;
            border-radius: 14px;
        }
        .screenshot .placeholder { text-align: center; padding: 32px; font-size: 14px; }
        .screenshot .placeholder code { background: rgba(255,255,255,0.05); padding: 5px 8px; border-radius: 8px; color: var(--accent-2); font-size: 13px; }

        .timeline { display: grid; gap: 16px; grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .timeline-item {
            padding: 24px 22px;
            border-radius: 20px;
            background: rgba(14,21,45,0.96);
            border: 1px solid rgba(110,168,255,0.12);
            position: relative;
            overflow: hidden;
            min-height: 136px;
        }
        .timeline-item::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(circle at top right, rgba(138,255,193,0.14), transparent 25%);
            pointer-events: none;
        }
        .timeline-item strong { display: block; margin-bottom: 12px; color: var(--accent-2); }
        .timeline-item p { margin: 0; line-height: 1.75; }
        @media (max-width: 920px) { .timeline { grid-template-columns: 1fr; } }

        pre {
            background: rgba(12,16,32,0.98);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 18px;
            overflow-x: auto;
            color: #cfd6ee;
            font-size: 13px;
            margin: 18px 0 0;
        }
        ul.clean { padding-left: 18px; margin: 12px 0 0; color: var(--muted); }
        ul.clean li { margin: 8px 0; }

        .kpis { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-top: 20px; }
        .kpi { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 20px; padding: 18px; text-align: center; }
        .kpi .n { font-size: 28px; font-weight: 700; }
        .kpi .l { margin-top: 8px; color: var(--muted); font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em; }
        @media (max-width: 760px) { .kpis { grid-template-columns: repeat(2, 1fr); } }

        footer { padding: 42px 0 64px; color: var(--muted); font-size: 14px; }
        .pill { display: inline-block; padding: 4px 10px; border-radius: 999px; background: rgba(255,209,102,0.12); color: var(--warn); font-size: 12px; }
        .ok { color: var(--accent-2); }
    </style>
</head>
<body>

<header class="hero">
    <div class="container">
        <span class="eyebrow">SWO2 · Hostingplatform · Voltooid</span>
        <h1>Een geloofwaardige mijlpaalpagina voor ons eigen hostingplatform.</h1>
        <p class="lede">Deze demo toont direct wat al staat: live PHP-hosting op eigen infrastructuur, GitOps deploys, monitoring, security-scans en CIS Controls die al zijn ingevoerd.</p>
        <div class="cta-row">
            <a class="btn" href="https://swo2.online" target="_blank" rel="noopener">Bekijk live platform</a>
            <a class="btn secondary" href="https://grafana.swo2.online" target="_blank" rel="noopener">Open Grafana</a>
        </div>

        <div class="hero-panel">
            <div class="panel-card">
                <div class="progress-pill"><span></span> 6 van 6 mijlpalen behaald</div>
                <h2>Waarom deze pagina telt</h2>
                <p>Dit is geen demoplaatje: dit is een werkende website die echt draait in onze eigen hosting. Alles wat je hier ziet is al live, stabiel en beschermd.</p>
                <div class="milestone-strip">
                    <div class="milestone-chip"><span></span>Live beschikbaar</div>
                    <div class="milestone-chip"><span></span>Veilige hosting</div>
                    <div class="milestone-chip"><span></span>Dagelijkse controles</div>
                </div>
                <div class="progress-bar"><span></span></div>
                <div class="kpis">
                    <div class="kpi"><div class="n">6</div><div class="l">Voltooide mijlpalen</div></div>
                    <div class="kpi"><div class="n">2</div><div class="l">Serverlocaties</div></div>
                    <div class="kpi"><div class="n">100%</div><div class="l">Up-to-date updates</div></div>
                    <div class="kpi"><div class="n">8</div><div class="l">Beveiligingsmaatregelen</div></div>
                </div>
                <div class="timeline-grid">
                    <div class="timeline-item"><strong>Mijlpaal 01</strong><p>Een live website die 24/7 bereikbaar is voor klanten.</p></div>
                    <div class="timeline-item"><strong>Mijlpaal 02</strong><p>Automatisch werkende updates voor de software.</p></div>
                    <div class="timeline-item"><strong>Mijlpaal 03</strong><p>Monitoring die problemen direct opmerkt.</p></div>
                    <div class="timeline-item"><strong>Mijlpaal 04</strong><p>Beveiliging met wekelijkse controles.</p></div>
                    <div class="timeline-item"><strong>Mijlpaal 05</strong><p>Bescherming voor klantdata en websites.</p></div>
                    <div class="timeline-item"><strong>Mijlpaal 06</strong><p>Alles draait op onze eigen hostinginfrastructuur.</p></div>
                </div>
            </div>

            <div class="panel-card">
                <h2>Wat klanten ervaren</h2>
                <p>Een site die snel laadt, veilig blijft en automatisch up-to-date blijft. Dit is een pagina voor gebruikers en ondernemers, zonder ingewikkelde technische termen.</p>
                <div class="milestone-strip">
                    <div class="milestone-chip"><span></span>Betrouwbare hosting</div>
                    <div class="milestone-chip"><span></span>Automatische updates</div>
                    <div class="milestone-chip"><span></span>Continu toezicht</div>
                </div>
            </div>
        </div>
    </div>
</header>

<section id="php-hosting">
    <div class="container">
        <h2>1. PHP-hosting bewezen</h2>
        <p class="sub">Deze Laravel-pagina is het bewijs: we serveren PHP op onze eigen Proxmox-/Kubernetes-omgeving achter Cloudflare.</p>

        <div class="grid cols-2">
            <div class="card">
                <span class="tag">Live demo</span>
                <h3>Deze pagina = het bewijs</h3>
                <p>De pagina die je nu bekijkt is een Laravel 11 applicatie die draait binnen ons hostingplatform.
                Voor een volledig overzicht van het platform: <a href="https://swo2.online" target="_blank" rel="noopener">swo2.online</a>.</p>
            </div>
            <div class="card">
                <span class="tag">Infrastructuur</span>
                <h3>Proxmox + Kubernetes</h3>
                <p>Twee Dell PowerEdge R530 nodes draaien Proxmox VE. Daarbinnen draait onze Kubernetes-cluster
                waarop klantapplicaties en deze demo gehost worden.</p>
            </div>
        </div>

        <div class="screenshot" aria-label="Screenshot van de Proxmox-omgeving">
            @php
                $proxmox = public_path('afbeeldingen/proxmox-omgeving.jpg');
            @endphp
            @if (file_exists($proxmox))
                <img src="{{ asset('afbeeldingen/proxmox-omgeving.jpg') }}" alt="Proxmox-omgeving van SWO2">
            @else
                <div class="placeholder">
                    <strong>Placeholder voor screenshot Proxmox-omgeving</strong><br>
                    Plaats het bestand <code>proxmox-omgeving.jpg</code> in
                    <code>public/afbeeldingen/</code> en het verschijnt automatisch hier.
                </div>
            @endif
        </div>
    </div>
</section>

<section id="automatisatie">
    <div class="container">
        <h2>2. Automatisatie — GitOps van top tot teen</h2>
        <p class="sub">We automatiseren een enorm groot deel van het beheer. Code in Git is de single source of truth.</p>

        <div class="grid cols-2">
            <div class="card">
                <span class="tag">Intern · ArgoCD</span>
                <h3>ArgoCD synct onze cluster</h3>
                <p>ArgoCD bewaakt onze GitHub repositories en past wijzigingen automatisch toe op de
                Kubernetes-cluster. Geen handmatige <code>kubectl apply</code> nodig.</p>
            </div>
            <div class="card">
                <span class="tag">Voor klanten</span>
                <h3>Push naar <code>main</code> = live</h3>
                <p>Klanten krijgen een GitHub-repo voor hun site. Elke push naar de <code>main</code>-branch
                triggert automatisch een redeploy van hun website. Geen FTP, geen downtime.</p>
            </div>
        </div>

        <div class="screenshot" aria-label="ArgoCD overview screenshot">
            @php
                $argocd = public_path('afbeeldingen/argocd.jpg');
            @endphp
            @if (file_exists($argocd))
                <img src="{{ asset('afbeeldingen/argocd.jpg') }}" alt="ArgoCD dashboard">
            @else
                <div class="placeholder">
                    <strong>Placeholder voor ArgoCD afbeelding</strong><br>
                    Plaats het bestand <code>argocd.jpg</code> in
                    <code>public/afbeeldingen/</code> en het verschijnt automatisch hier.
                </div>
            @endif
        </div>
    </div>
</section>

<section id="monitoring">
    <div class="container">
        <h2>3. Centrale monitoring via Grafana</h2>
        <p class="sub">Alle relevante logs worden geautomatiseerd weggeschreven en visueel ontsloten op
            <a href="https://grafana.swo2.online" target="_blank" rel="noopener">grafana.swo2.online</a>.</p>

        <div class="grid cols-3">
            <div class="card"><h3>OPNsense firewall</h3><p>Firewall events, blocked traffic en regelhits.</p></div>
            <div class="card"><h3>pve1 &amp; pve2</h3><p>Hostmetrics, syslog en VM-events van beide Proxmox nodes.</p></div>
            <div class="card"><h3>Kubernetes</h3><p>Pod logs, control plane events en cluster-health.</p></div>
        </div>
    </div>
</section>

<section id="vulnscans">
    <div class="container">
        <h2>4. Geautomatiseerde vulnerability scans <span class="pill">CIS 7.6</span></h2>
        <p class="sub">Wekelijkse scans op alle extern bereikbare componenten van het hostingplatform.</p>

        <div class="grid cols-2">
            <div class="card">
                <span class="tag">Applicatielaag</span>
                <h3>OWASP ZAP (Docker)</h3>
                <p>Op een dedicated Ubuntu 24.04 VM binnen Proxmox draait OWASP ZAP via Docker. Een
                wekelijkse baseline scan controleert <code>https://swo2.online</code> (achter Cloudflare-proxy)
                op missing security headers, CSRF, cookie-configuratie en XSS.</p>
            </div>
            <div class="card">
                <span class="tag">Netwerklaag</span>
                <h3>Nmap op publiek IP</h3>
                <p>Het publieke IP <code>193.191.186.146</code> wordt wekelijks gescand met Nmap om open
                poorten en actieve services in kaart te brengen.</p>
            </div>
        </div>

        <div class="card">
            <h3>Cron &amp; rapportage</h3>
            <p>Beide scans draaien automatisch elke zondag om 03:00 UTC en worden opgeslagen in
                <code>/opt/zap-rapporten/</code> met datum in de bestandsnaam.</p>
<pre># /etc/cron.d/swo2-scans
0 3 * * 0 root /opt/scripts/zap-baseline.sh   >> /var/log/zap.log 2>&amp;1
5 3 * * 0 root /opt/scripts/nmap-public.sh    >> /var/log/nmap.log 2>&amp;1</pre>
            <ul class="clean">
                <li>ZAP-rapporten in <strong>HTML + JSON</strong> (mens + automatisering).</li>
                <li>Nmap-output als tekstbestand voor historische log.</li>
            </ul>
        </div>

        <div class="card">
            <h3>Resultaten initiële scan — 13 mei 2026</h3>
            <ul class="clean">
                <li><span class="ok">0 critical</span>, <span class="ok">0 high</span></li>
                <li>3 medium · 8 low (voornamelijk ontbrekende security headers)</li>
                <li>Bevindingen worden gelogd en opgevolgd tot fix.</li>
            </ul>
        </div>
    </div>
</section>

<section id="schaalbaarheid">
    <div class="container">
        <h2>5. Schaalbaarheid</h2>
        <p class="sub">Eén platform, vele klanten — netjes geïsoleerd via namespaces en pakketten.</p>
        <div class="grid cols-2">
            <div class="card">
                <h3>Gedeelde database</h3>
                <p>Gebruikers delen één performante databasecluster met strikte isolatie op rij- en schema-niveau.</p>
            </div>
            <div class="card">
                <h3>Meerdere sites per gebruiker</h3>
                <p>Afhankelijk van het gekozen pakket kan een gebruiker meerdere sites hosten onder hetzelfde account.</p>
            </div>
        </div>

        <div class="card">
            <h3>Automatische failover naar worker 2</h3>
            <p>Wanneer worker 1 vol is, wordt een nieuwe website automatisch op worker 2 geplaatst en direct uitgevoerd. Hierdoor blijft de klant zonder onderbreking online.</p>
        </div>

        <div class="screenshot" aria-label="Kubernetes logo placeholder">
            @php
                $k8sLogo = public_path('afbeeldingen/logok8s.png');
            @endphp
            @if (file_exists($k8sLogo))
                <img src="{{ asset('afbeeldingen/logok8s.png') }}" alt="Kubernetes logo">
            @else
                <div class="placeholder">
                    <strong>Placeholder voor Kubernetes logo</strong><br>
                    Plaats het bestand <code>logok8s.png</code> in
                    <code>public/afbeeldingen/</code> en het verschijnt automatisch hier.
                </div>
            @endif
        </div>
    </div>
</section>

<section id="cis-controls">
    <div class="container">
        <h2>6. CIS Controls — beveiliging op fundament-niveau</h2>
        <p class="sub">Een selectie van de CIS Controls die we concreet implementeerden op het platform.</p>

        <div class="grid cols-2">
            <div class="card">
                <span class="tag">CIS 1.1</span>
                <h3>Asset inventory</h3>
                <p>Alle hardware-assets zijn geïnventariseerd: 2× Dell PowerEdge R530, netwerkswitch en alle
                Kubernetes-nodes. Per asset: IP, hostname, OS en verantwoordelijke.</p>
            </div>
            <div class="card">
                <span class="tag">CIS 3.11</span>
                <h3>Encrypt sensitive data at rest</h3>
                <p>Klantdatabases en back-upvolumes zijn versleuteld. Secrets en API-sleutels staan nooit
                plaintext opgeslagen — uitsluitend via <code>.env</code> en CI/CD-omgevingsvariabelen.</p>
            </div>
            <div class="card">
                <span class="tag">CIS 4.4</span>
                <h3>Host-firewall</h3>
                <p>Op beide R530-servers staat een firewall. Enkel SSH, HTTPS en de Kubernetes API zijn extern
                bereikbaar. Databasepoorten zijn enkel intern toegankelijk.</p>
            </div>
            <div class="card">
                <span class="tag">CIS 4.7</span>
                <h3>Default accounts beheerd</h3>
                <p>Standaard root/admin accounts worden uitgeschakeld of hernoemd na install. Default
                wachtwoorden worden direct vervangen door sterke, unieke wachtwoorden.</p>
            </div>
            <div class="card">
                <span class="tag">CIS 5.4</span>
                <h3>Dedicated admin accounts</h3>
                <p>Beheerderstaken gebeuren uitsluitend vanuit dedicated admin-accounts. Kubernetes
                <code>cluster-admin</code> is voorbehouden aan aangewezen personen.</p>
            </div>
            <div class="card">
                <span class="tag">CIS 6.8</span>
                <h3>Role-Based Access Control</h3>
                <p>RBAC op zowel de Kubernetes-cluster als het klantendashboard. Klanten zien enkel hun
                eigen namespace en resources.</p>
            </div>
            <div class="card">
                <span class="tag">CIS 8.2</span>
                <h3>Audit logs</h3>
                <p>Auditlogs verzameld van alle relevante componenten met minstens: timestamp,
                gebruiker/service, actie en resultaat.</p>
            </div>
            <div class="card">
                <span class="tag">CIS 7.6</span>
                <h3>Vulnerability scans</h3>
                <p>Zie sectie hierboven — wekelijkse ZAP + Nmap scans, met opvolging van findings.</p>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <div>© {{ date('Y') }} SWO2 — Hostingplatform · Deze demo draait op Laravel {{ app()->version() }} · PHP {{ PHP_VERSION }}</div>
        <div style="margin-top:6px;">Meer info: <a href="https://swo2.online" target="_blank" rel="noopener">swo2.online</a> · monitoring: <a href="https://grafana.swo2.online" target="_blank" rel="noopener">grafana.swo2.online</a></div>
    </div>
</footer>

</body>
</html>
