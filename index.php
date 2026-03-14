<?php
require_once 'config/database.php';

// Fetch settings
$slogan = getSetting($conn, 'slogan') ?: 'Empowering Businesses, Elevating Digitals';
$intro = getSetting($conn, 'short_intro') ?: 'Welcome to my portfolio.';
$email = getSetting($conn, 'email') ?: 'email@example.com';
$wa = getSetting($conn, 'wa_number') ?: '62800000000';

// Fetch Orgs
$orgs = $conn->query("SELECT * FROM organizations ORDER BY order_num ASC");
// Fetch Exps
$exps = $conn->query("SELECT * FROM experiences ORDER BY order_num ASC");
$expList = ['work'=>[], 'speaking'=>[], 'writing'=>[]];
while($row = $exps->fetch_assoc()) $expList[$row['category']][] = $row;

// Fetch Skills
$skills = $conn->query("SELECT * FROM skills ORDER BY order_num ASC");
$skillList = ['digital_marketing'=>[], 'business_mentor'=>[], 'website_development'=>[]];
while($row = $skills->fetch_assoc()) $skillList[$row['category']][] = $row;

// Fetch Certs
$certs = $conn->query("SELECT * FROM certifications ORDER BY order_num ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Osman Nur Chaidir - Portfolio</title>
    
    <!-- Plus Jakarta Sans Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #5d0001; /* Maroon Utama */
            --primary-hover: #7a0002;
            --primary-light: #fdf2f2;
            --bg: #fafafa;
            --surface: #ffffff;
            --text-main: #0f172a;
            --text-body: #475569;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --radius-sm: 12px;
            --radius-md: 20px;
            --radius-lg: 32px;
            --transition: all 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            background-color: var(--bg);
            color: var(--text-body);
            line-height: 1.6;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* --- Animations --- */
        .fade-in-up {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }
        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* --- Hero Section --- */
        .hero {
            position: relative;
            padding: 120px 0 80px;
            background-color: var(--surface);
            overflow: hidden;
        }
        
        /* Subtle background accent */
        .hero::after {
            content: "";
            position: absolute;
            top: -10%;
            right: -5%;
            width: 40%;
            height: 100%;
            background: radial-gradient(circle, var(--primary-light) 0%, rgba(255,255,255,0) 70%);
            z-index: 0;
            border-radius: 50%;
        }

        .hero-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 60px;
            position: relative;
            z-index: 1;
        }

        .hero-content {
            flex: 1.2;
            max-width: 600px;
        }

        .tagline {
            display: inline-block;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
            padding: 8px 16px;
            background: var(--primary-light);
            border-radius: 50px;
        }

        .hero h1 {
            font-size: 3.8rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1.1;
            margin-bottom: 24px;
            letter-spacing: -0.02em;
        }

        .hero p {
            font-size: 1.15rem;
            color: var(--text-body);
            margin-bottom: 40px;
            line-height: 1.8;
        }

        .hero-image {
            flex: 0.8;
            display: flex;
            justify-content: flex-end;
            position: relative;
        }

        /* Styling The Hero Image Without Background */
        .hero-image img {
            width: 100%;
            max-width: 450px;
            height: auto;
            object-fit: contain;
            /* In a real scenario, this would be a transparent PNG from CMS */
            /* Using a generic cutout placeholder for demonstration */
            filter: drop-shadow(0 20px 30px rgba(93, 0, 1, 0.15));
            transition: transform 0.5s ease;
        }

        .hero-image img:hover {
            transform: translateY(-10px);
        }

        /* --- Shared Section Styles --- */
        section {
            padding: 120px 0;
            position: relative;
        }
        
        .bg-white {
            background-color: var(--surface);
        }

        .section-header {
            margin-bottom: 60px;
        }

        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 16px;
            letter-spacing: -0.01em;
        }

        .section-header p {
            font-size: 1.1rem;
            max-width: 600px;
        }

        /* --- Buttons --- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 32px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: var(--transition);
            text-decoration: none;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(93, 0, 1, 0.2);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(93, 0, 1, 0.3);
            color: #ffffff;
        }

        .btn-outline {
            background-color: transparent;
            color: var(--text-main);
            border: 2px solid var(--border);
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            background-color: var(--primary-light);
        }

        /* --- Organizations Grid --- */
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }

        .clean-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 32px;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .clean-card:hover {
            border-color: rgba(93, 0, 1, 0.3);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.05);
            transform: translateY(-5px);
        }
        
        .clean-card .badge {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
            display: inline-block;
        }

        .clean-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .clean-card p {
            font-size: 1rem;
            color: var(--text-muted);
        }

        /* --- Sleek Tab System --- */
        .tabs-header {
            display: flex;
            gap: 12px;
            margin-bottom: 40px;
            border-bottom: 2px solid var(--border);
            padding-bottom: 12px;
            overflow-x: auto;
            scrollbar-width: none;
        }
        .tabs-header::-webkit-scrollbar { display: none; }

        .tab-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-size: 1.05rem;
            font-weight: 600;
            padding: 8px 16px;
            cursor: pointer;
            position: relative;
            transition: color 0.3s ease;
            white-space: nowrap;
        }

        .tab-btn:hover {
            color: var(--text-main);
        }

        .tab-btn.active {
            color: var(--primary);
        }

        .tab-btn::after {
            content: '';
            position: absolute;
            bottom: -14px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .tab-btn.active::after {
            transform: scaleX(1);
        }

        .tab-content {
            display: none;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.4s ease;
        }

        .tab-content.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        /* --- Experience/Journey Modern List --- */
        .timeline {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .timeline-item {
            display: flex;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 32px;
            transition: var(--transition);
        }

        .timeline-item:hover {
            border-color: var(--primary);
            box-shadow: 0 10px 30px rgba(93,0,1,0.05);
        }

        .timeline-date {
            flex: 0 0 180px;
            font-weight: 600;
            color: var(--primary);
            font-size: 0.95rem;
            padding-right: 24px;
        }

        .timeline-content {
            flex: 1;
            border-left: 1px solid var(--border);
            padding-left: 32px;
        }

        .timeline-content h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 12px;
        }

        .timeline-content p {
            color: var(--text-body);
            font-size: 1rem;
        }

        /* --- Certifications Grid --- */
        .cert-card {
            background: var(--surface);
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 1px solid var(--border);
            transition: var(--transition);
        }

        .cert-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
            border-color: rgba(93,0,1,0.2);
        }

        .cert-image {
            width: 100%;
            height: 220px;
            overflow: hidden;
            background: #f1f5f9;
        }

        .cert-image img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Don't crop heavily, show the cert */
            padding: 10px;
            transition: transform 0.5s ease;
        }

        .cert-card:hover .cert-image img {
            transform: scale(1.05);
        }

        .cert-info {
            padding: 24px;
        }

        .cert-info h4 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        /* --- CTA / Footer --- */
        .cta-section {
            background-color: var(--primary);
            border-radius: var(--radius-lg);
            padding: 80px 40px;
            text-align: center;
            margin: 0 24px 120px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: url('data:image/svg+xml;utf8,<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" stroke="rgba(255,255,255,0.05)" stroke-width="2" fill="none"/></svg>') repeat;
            opacity: 0.5;
            z-index: 0;
        }

        .cta-content {
            position: relative;
            z-index: 1;
        }

        .cta-content h2 {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: #ffffff;
        }

        .cta-content p {
            font-size: 1.15rem;
            opacity: 0.9;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-btns {
            display: flex;
            justify-content: center;
            gap: 16px;
        }

        .btn-white {
            background: #ffffff;
            color: var(--primary);
        }
        .btn-white:hover {
            background: #f8f9fa;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .btn-glass {
            background: rgba(255,255,255,0.1);
            color: #ffffff;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-glass:hover {
            background: rgba(255,255,255,0.2);
            color: #ffffff;
        }

        footer {
            text-align: center;
            padding: 40px 0;
            border-top: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* --- Link with Arrow --- */
        .link-arrow {
            display: inline-flex;
            align-items: center;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            margin-top: auto;
            padding-top: 16px;
            font-size: 0.95rem;
        }
        .link-arrow svg {
            margin-left: 6px;
            transition: transform 0.3s ease;
        }
        .clean-card:hover .link-arrow svg {
            transform: translateX(4px);
        }

        /* --- Responsive --- */
        @media (max-width: 992px) {
            .hero-container {
                flex-direction: column-reverse;
                text-align: center;
            }
            .hero-content {
                max-width: 100%;
            }
            .hero-image {
                justify-content: center;
            }
            .hero h1 { font-size: 3rem; }
            .timeline-item {
                flex-direction: column;
                padding: 24px;
            }
            .timeline-date {
                flex: none;
                margin-bottom: 16px;
                padding-right: 0;
            }
            .timeline-content {
                border-left: none;
                padding-left: 0;
                border-top: 1px solid var(--border);
                padding-top: 16px;
            }
        }

        @media (max-width: 768px) {
            section { padding: 80px 0; }
            .hero { padding: 80px 0 40px; }
            .hero h1 { font-size: 2.2rem; }
            .section-header h2 { font-size: 2rem; }
            .cta-section { padding: 60px 24px; margin: 0 16px 80px; }
            .cta-content h2 { font-size: 2rem; }
            .cta-btns { flex-direction: column; }
        }
    </style>
</head>
<body>

<!-- HERO SECTION -->
<section class="hero">
    <div class="container hero-container">
        <div class="hero-content fade-in-up">
            <span class="tagline">Welcome to my profile</span>
            <h1><?= htmlspecialchars($slogan) ?></h1>
            <p><?= nl2br(htmlspecialchars($intro)) ?></p>
            <div style="display: flex; gap: 16px; flex-wrap: wrap; justify-content: flex-start;">
                <button class="btn btn-primary" onclick="document.getElementById('contact').scrollIntoView();">Let's Connect</button>
                <button class="btn btn-outline" onclick="document.getElementById('experience').scrollIntoView();">View Experience</button>
            </div>
        </div>
        
        <!-- Large Image Without Background -->
        <div class="hero-image fade-in-up" style="transition-delay: 0.2s;">
            <!-- Placeholder for cut-out image -->
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/businessman-posing-with-crossed-arms-illustration-download-in-svg-png-gif-file-formats--business-man-male-employee-manager-pack-professionals-illustrations-4001927.png" 
                 alt="Osman Nur Chaidir" 
                 style="opacity: 0.9;">
        </div>
    </div>
</section>

<!-- ORGANIZATIONS SECTION -->
<section id="organizations" class="bg-white">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>Business & Organizations</h2>
            <p class="text-muted">The ventures and organizations I actively lead and participate in.</p>
        </div>
        
        <div class="grid-3 fade-in-up" style="transition-delay: 0.2s;">
            <?php if($orgs->num_rows > 0): while($org = $orgs->fetch_assoc()): ?>
            <div class="clean-card">
                <span class="badge"><?= htmlspecialchars($org['type']) ?></span>
                <h3><?= htmlspecialchars($org['name']) ?></h3>
                <p><?= htmlspecialchars($org['role']) ?></p>
            </div>
            <?php endwhile; else: ?>
                <p style="color:var(--text-muted);">No data added yet via CMS.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- EXPERIENCES SECTION (Modern List) -->
<section id="experience">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>Professional Journey</h2>
            <p class="text-muted">My track record across various roles, speaking engagements, and publications.</p>
        </div>
        
        <div class="fade-in-up" style="transition-delay: 0.2s;">
            <!-- Sleek Tabs -->
            <div class="tabs-header">
                <button class="tab-btn active" onclick="switchTab(this, 'exp', 'work')">Work Experience</button>
                <button class="tab-btn" onclick="switchTab(this, 'exp', 'speaking')">Speaking / Seminars</button>
                <button class="tab-btn" onclick="switchTab(this, 'exp', 'writing')">Written Works</button>
            </div>
            
            <div class="exp-contents">
                <?php foreach(['work', 'speaking', 'writing'] as $idx => $cat): ?>
                    <div id="exp-<?= $cat ?>" class="tab-content <?= $idx === 0 ? 'active' : '' ?>">
                        <div class="timeline">
                            <?php if(empty($expList[$cat])): ?>
                                <p style="color:var(--text-muted); padding: 20px;">No entries available.</p>
                            <?php else: foreach($expList[$cat] as $e): ?>
                                <div class="timeline-item">
                                    <div class="timeline-date"><?= htmlspecialchars($e['date_range']) ?></div>
                                    <div class="timeline-content">
                                        <h4><?= htmlspecialchars($e['title']) ?></h4>
                                        <p><?= nl2br(htmlspecialchars($e['description'])) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- SKILLS SECTION (Clean Cards) -->
<section id="skills" class="bg-white">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>Technical Skills</h2>
            <p class="text-muted">Core competencies and technical capabilities I bring to the table.</p>
        </div>

        <div class="fade-in-up" style="transition-delay: 0.2s;">
            <!-- Sleek Tabs -->
            <div class="tabs-header">
                <button class="tab-btn active" onclick="switchTab(this, 'skill', 'digital_marketing')">Digital Marketing</button>
                <button class="tab-btn" onclick="switchTab(this, 'skill', 'business_mentor')">Business Mentor</button>
                <button class="tab-btn" onclick="switchTab(this, 'skill', 'website_development')">Web Development</button>
            </div>

            <div class="skill-contents">
                <?php foreach(['digital_marketing', 'business_mentor', 'website_development'] as $idx => $cat): ?>
                    <div id="skill-<?= $cat ?>" class="tab-content <?= $idx === 0 ? 'active' : '' ?>">
                        <div class="grid-3">
                        <?php if(empty($skillList[$cat])): ?>
                            <p style="color:var(--text-muted); padding: 20px;">No entries available.</p>
                        <?php else: foreach($skillList[$cat] as $s): ?>
                            <div class="clean-card">
                                <h3><?= htmlspecialchars($s['name']) ?></h3>
                                <?php if(!empty($s['portfolio_link'])): ?>
                                    <a href="<?= htmlspecialchars($s['portfolio_link']) ?>" target="_blank" class="link-arrow">
                                        View Project 
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- CERTIFICATIONS (Modern Grid) -->
<section id="certifications">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>Certifications & Achievements</h2>
            <p class="text-muted">Recognitions and formal qualifications acquired over the years.</p>
        </div>
        
        <div class="grid-3 fade-in-up" style="transition-delay: 0.2s;">
            <?php if($certs->num_rows > 0): while($crt = $certs->fetch_assoc()): ?>
            <div class="cert-card">
                <div class="cert-image">
                    <!-- Default to a placeholder if empty -->
                    <img src="<?= htmlspecialchars($crt['image_path']) ?: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80&w=800' ?>" alt="<?= htmlspecialchars($crt['title']) ?>" loading="lazy">
                </div>
                <div class="cert-info">
                    <h4><?= htmlspecialchars($crt['title']) ?></h4>
                    <p style="color:var(--text-muted); font-size:0.95rem; margin-top:8px; line-height: 1.5;">
                        <?= nl2br(htmlspecialchars($crt['description'])) ?>
                    </p>
                </div>
            </div>
            <?php endwhile; else: ?>
                 <p style="color:var(--text-muted); width: 100%;">No data added yet via CMS.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA / REACH OUT SECTION -->
<section id="contact" style="padding: 0;">
    <div class="cta-section fade-in-up">
        <div class="cta-content">
            <h2>Let's Do Great Work Together</h2>
            <p>Ready to discuss your next big project or looking for a seasoned mentor? Reach out to me directly through email or WhatsApp.</p>
            <div class="cta-btns">
                <a href="mailto:<?= htmlspecialchars($email) ?>" class="btn btn-white">
                    <svg style="margin-right:8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    Email Me
                </a>
                <a href="https://wa.me/<?= htmlspecialchars($wa) ?>" target="_blank" class="btn btn-glass">
                    <svg style="margin-right:8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <p>&copy; <?= date('Y') ?> Osman Nur Chaidir. Crafted with excellence.</p>
    </div>
</footer>

<script>
    // Modern Tab Switcher Algorithm
    function switchTab(btnElement, group, targetId) {
        // Reset buttons in this specific tab group
        const header = btnElement.parentElement;
        const buttons = header.querySelectorAll('.tab-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        
        // Set current true
        btnElement.classList.add('active');

        // Find the container of these tabs
        const contentContainer = header.nextElementSibling;
        const contents = contentContainer.querySelectorAll('.tab-content');
        
        // Hide all
        contents.forEach(content => {
            content.style.opacity = '0';
            content.style.transform = 'translateY(10px)';
            setTimeout(() => content.classList.remove('active'), 300);
        });

        // Show target
        const targetElement = document.getElementById(`${group}-${targetId}`);
        setTimeout(() => {
            targetElement.classList.add('active');
            // Trigger reflow
            void targetElement.offsetWidth;
            targetElement.style.opacity = '1';
            targetElement.style.transform = 'translateY(0)';
        }, 350);
    }

    // Modern Intersection Observer for smooth reveal on scroll
    document.addEventListener("DOMContentLoaded", () => {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.15
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const elementsToReveal = document.querySelectorAll('.fade-in-up');
        elementsToReveal.forEach(el => observer.observe(el));
    });
</script>
</body>
</html>
