<?php
require_once 'config/database.php';

// Fetch settings
$slogan = getSetting($conn, 'slogan') ?: 'Professional Portfolio';
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
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #5d0001;
            --primary-light: #8a0002;
            --primary-dark: #3a0000;
            --bg: #ffffff;
            --surface: #f8f9fa;
            --surface-alt: #ffffff;
            --text: #2d3436;
            --text-muted: #636e72;
            --border: #e9ecef;
            --gold: #d4af37;
            
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            --shadow-sm: 0 4px 6px rgba(0,0,0,0.05);
            --shadow-md: 0 10px 20px rgba(93, 0, 1, 0.08);
            --shadow-lg: 0 20px 40px rgba(93, 0, 1, 0.12);
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Outfit', sans-serif;
            scroll-behavior: smooth;
        }

        body { 
            background-color: var(--bg); 
            color: var(--text); 
            line-height: 1.7; 
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 0 24px; 
        }
        
        /* Hero Section */
        .hero { 
            position: relative;
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
            text-align: center; 
            padding: 160px 20px 100px; 
            background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
            min-height: 80vh; 
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(93,0,1,0.03) 0%, transparent 60%);
            animation: rotateBG 30s linear infinite;
            z-index: 0;
        }

        @keyframes rotateBG {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .hero img { 
            width: 180px; 
            height: 180px; 
            border-radius: 50%; 
            object-fit: cover; 
            border: 4px solid #fff; 
            margin-bottom: 30px; 
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
        }
        
        .hero img:hover {
            transform: scale(1.05);
            box-shadow: 0 25px 50px rgba(93,0,1,0.2);
        }

        .hero h1 { 
            font-size: 3.5rem; 
            font-weight: 800; 
            margin-bottom: 15px; 
            color: var(--text);
            letter-spacing: -1px;
        }

        .hero h2 { 
            font-size: 1.4rem; 
            color: var(--primary); 
            font-weight: 500; 
            max-width: 600px;
        }
        
        /* Typography & Layout */
        section { 
            padding: 100px 0; 
        }
        
        section.bg-alt {
            background-color: var(--surface);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        section h3 { 
            font-size: 2.5rem; 
            font-weight: 700;
            color: var(--text);
            position: relative;
            display: inline-block;
        }

        section h3::after { 
            content: ''; 
            position: absolute;
            left: 50%;
            bottom: -15px;
            transform: translateX(-50%);
            width: 60px; 
            height: 4px; 
            background: var(--primary); 
            border-radius: 4px; 
        }

        /* Intro */
        .intro { 
            text-align: center; 
            max-width: 800px; 
            margin: 0 auto; 
            font-size: 1.25rem; 
            color: var(--text-muted); 
            font-weight: 300;
        }

        /* Grid System */
        .grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 30px; 
        }

        /* Card Styles */
        .card { 
            background: var(--surface-alt); 
            padding: 40px 30px; 
            border-radius: 20px; 
            text-align: center; 
            transition: var(--transition); 
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }
        
        .card:hover { 
            transform: translateY(-10px); 
            box-shadow: var(--shadow-md); 
            border-color: rgba(93,0,1,0.1);
        }

        .card h4 { 
            font-size: 1.4rem; 
            margin-bottom: 10px; 
            color: var(--text); 
            font-weight: 700;
        }

        /* Badges */
        .badge { 
            display: inline-block;
            font-size: 0.8rem; 
            color: var(--primary); 
            background: rgba(93,0,1,0.05);
            padding: 6px 14px;
            border-radius: 20px;
            text-transform: uppercase; 
            letter-spacing: 1px; 
            margin-bottom: 20px; 
            font-weight: 600;
        }

        /* Tabs System */
        .tabs { 
            display: flex; 
            justify-content: center; 
            gap: 15px; 
            margin-bottom: 50px; 
            flex-wrap: wrap; 
        }
        
        .tab-btn { 
            padding: 14px 30px; 
            background: var(--surface-alt); 
            color: var(--text-muted); 
            border: 1px solid var(--border); 
            border-radius: 50px; 
            cursor: pointer; 
            font-size: 1rem; 
            font-weight: 500;
            transition: var(--transition); 
            box-shadow: var(--shadow-sm);
        }
        
        .tab-btn.active, .tab-btn:hover { 
            background: var(--primary); 
            border-color: var(--primary); 
            color: white; 
            box-shadow: 0 8px 15px rgba(93,0,1,0.2);
            transform: translateY(-2px);
        }

        .tab-content { 
            display: none; 
            opacity: 0;
            transform: translateY(20px);
            transition: var(--transition);
        }
        
        .tab-content.active { 
            display: block; 
            opacity: 1;
            transform: translateY(0);
        }

        /* Experience Timeline */
        .exp-list { 
            max-width: 800px; 
            margin: 0 auto; 
        }
        
        .exp-item { 
            background: var(--surface-alt); 
            padding: 30px; 
            border-radius: 16px; 
            margin-bottom: 25px; 
            border-left: 5px solid var(--primary); 
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .exp-item:hover {
            box-shadow: var(--shadow-md);
            transform: translateX(5px);
        }

        .exp-item h4 { 
            font-size: 1.4rem; 
            margin-bottom: 8px; 
            color: var(--text);
        }
        
        .exp-item .date { 
            color: var(--primary-light); 
            font-size: 0.95rem; 
            margin-bottom: 15px; 
            display: inline-block; 
            font-weight: 600; 
            background: rgba(93,0,1,0.05);
            padding: 4px 12px;
            border-radius: 4px;
        }

        /* Skills specific */
        .skill-card {
            text-align: left;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .btn-outline { 
            display: inline-block; 
            margin-top: 20px; 
            color: var(--primary); 
            background: transparent;
            border: 2px solid var(--primary);
            padding: 10px 20px; 
            text-decoration: none; 
            border-radius: 8px; 
            font-size: 0.95rem; 
            font-weight: 600;
            transition: var(--transition); 
            text-align: center;
        }
        
        .btn-outline:hover { 
            background: var(--primary); 
            color: white;
            box-shadow: 0 4px 10px rgba(93,0,1,0.2);
        }

        /* Certifications grid */
        .certs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        .cert-card { 
            background: var(--surface-alt); 
            border-radius: 20px; 
            overflow: hidden; 
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            border: 1px solid var(--border);
        }

        .cert-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .cert-card img { 
            width: 100%; 
            height: 240px; 
            object-fit: cover; 
            border-bottom: 1px solid var(--border); 
            transition: var(--transition);
        }

        .cert-card:hover img {
            transform: scale(1.03);
        }

        .cert-card .content { 
            padding: 25px; 
            position: relative;
            background: var(--surface-alt);
        }

        .cert-card h4 { 
            margin-bottom: 10px;
            font-size: 1.25rem;
        }

        /* Contact Section */
        .contact { 
            text-align: center; 
            padding: 120px 20px;
            background: var(--primary);
            color: white;
        }
        
        .contact h3 { color: white; }
        .contact h3::after { background: var(--gold); }
        .contact p { font-size: 1.2rem; margin-bottom: 40px; opacity: 0.9; }

        .contact-btns { 
            display: flex; 
            justify-content: center; 
            gap: 20px; 
            flex-wrap: wrap;
        }
        
        .contact-btn { 
            padding: 16px 40px; 
            border-radius: 50px; 
            text-decoration: none; 
            font-weight: 600; 
            font-size: 1.1rem; 
            transition: var(--transition); 
            display: inline-flex; 
            align-items: center; 
            gap: 12px; 
        }
        
        .btn-email { 
            background: white; 
            color: var(--primary); 
        }
        .btn-wa { 
            background: #25D366; 
            color: white; 
        }
        
        .contact-btn:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 15px 30px rgba(0,0,0,0.2); 
        }

        footer { 
            text-align: center; 
            padding: 40px; 
            background: var(--primary-dark);
            color: rgba(255,255,255,0.7); 
        }

        /* Scroll Animations */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .hero h2 { font-size: 1.2rem; }
            section { padding: 70px 0; }
            .tabs { gap: 10px; }
            .tab-btn { padding: 10px 20px; font-size: 0.9rem; }
            .contact-btns { flex-direction: column; align-items: stretch; }
            .contact-btn { justify-content: center; }
        }
    </style>
</head>
<body>

<header class="hero">
    <div class="hero-content reveal">
        <img src="https://ui-avatars.com/api/?name=Osman+Nur+Chaidir&background=5d0001&color=fff&size=300" alt="Osman Nur Chaidir">
        <h1>Osman Nur Chaidir</h1>
        <h2><?= htmlspecialchars($slogan) ?></h2>
    </div>
</header>

<section class="container reveal">
    <div class="intro">
        <p><?= nl2br(htmlspecialchars($intro)) ?></p>
    </div>
</section>

<!-- Business & Orgs -->
<section class="bg-alt">
    <div class="container reveal">
        <div class="section-header">
            <h3>Business & Organizations</h3>
        </div>
        <div class="grid">
            <?php if($orgs->num_rows > 0): while($org = $orgs->fetch_assoc()): ?>
            <div class="card">
                <span class="badge"><?= htmlspecialchars($org['type']) ?></span>
                <h4><?= htmlspecialchars($org['name']) ?></h4>
                <p class="text-muted"><?= htmlspecialchars($org['role']) ?></p>
            </div>
            <?php endwhile; else: ?>
                <p style="text-align:center;width:100%;color:#666;">No data added yet via CMS.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Experiences -->
<section class="container reveal">
    <div class="section-header">
        <h3>Professional Journey</h3>
    </div>
    
    <div class="tabs">
        <button class="tab-btn active" onclick="openTab('exp', 'work', event)">Work Experience</button>
        <button class="tab-btn" onclick="openTab('exp', 'speaking', event)">Speaking / Seminars</button>
        <button class="tab-btn" onclick="openTab('exp', 'writing', event)">Written Works</button>
    </div>
    
    <div class="exp-list">
        <?php foreach(['work', 'speaking', 'writing'] as $idx => $cat): ?>
            <div id="exp-<?= $cat ?>" class="tab-content exp-content <?= $idx === 0 ? 'active' : '' ?>">
                <?php if(empty($expList[$cat])): ?>
                    <p style="text-align:center; color:#666;">No entries yet.</p>
                <?php else: foreach($expList[$cat] as $e): ?>
                    <div class="exp-item">
                        <h4><?= htmlspecialchars($e['title']) ?></h4>
                        <span class="date"><?= htmlspecialchars($e['date_range']) ?></span>
                        <p><?= nl2br(htmlspecialchars($e['description'])) ?></p>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Skills -->
<section class="bg-alt">
    <div class="container reveal">
        <div class="section-header">
            <h3>Technical Skills</h3>
        </div>
        
        <div class="tabs">
            <button class="tab-btn active" onclick="openTab('skill', 'digital_marketing', event)">Digital Marketing</button>
            <button class="tab-btn" onclick="openTab('skill', 'business_mentor', event)">Business Mentor</button>
            <button class="tab-btn" onclick="openTab('skill', 'website_development', event)">Web Development</button>
        </div>

        <div>
            <?php foreach(['digital_marketing', 'business_mentor', 'website_development'] as $idx => $cat): ?>
                <div id="skill-<?= $cat ?>" class="tab-content skill-content <?= $idx === 0 ? 'active' : '' ?>">
                    <div class="grid">
                    <?php if(empty($skillList[$cat])): ?>
                        <p style="text-align:center;width:100%;color:#666;">No entries yet.</p>
                    <?php else: foreach($skillList[$cat] as $s): ?>
                        <div class="card skill-card">
                            <h4><?= htmlspecialchars($s['name']) ?></h4>
                            <?php if(!empty($s['portfolio_link'])): ?>
                                <a href="<?= htmlspecialchars($s['portfolio_link']) ?>" target="_blank" class="btn-outline">View Portfolio &rarr;</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Certifications -->
<section class="container reveal">
    <div class="section-header">
        <h3>Certifications & Achievements</h3>
    </div>
    <div class="certs-grid">
        <?php if($certs->num_rows > 0): while($crt = $certs->fetch_assoc()): ?>
        <div class="cert-card">
            <div style="overflow: hidden;">
                <img src="<?= htmlspecialchars($crt['image_path']) ?: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80&w=800' ?>" alt="<?= htmlspecialchars($crt['title']) ?>">
            </div>
            <div class="content">
                <h4><?= htmlspecialchars($crt['title']) ?></h4>
                <p style="color:var(--text-muted); font-size:0.95rem;"><?= nl2br(htmlspecialchars($crt['description'])) ?></p>
            </div>
        </div>
        <?php endwhile; else: ?>
             <p style="text-align:center;width:100%;color:#666;">No data added yet via CMS.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Reach Out -->
<section class="contact">
    <div class="container reveal">
        <div class="section-header">
            <h3>Let's Connect</h3>
        </div>
        <p>Ready to collaborate or need a mentor? Reach out through the following channels.</p>
        <div class="contact-btns">
            <a href="mailto:<?= htmlspecialchars($email) ?>" class="contact-btn btn-email">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                Email Me
            </a>
            <a href="https://wa.me/<?= htmlspecialchars($wa) ?>" target="_blank" class="contact-btn btn-wa">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                WhatsApp
            </a>
        </div>
    </div>
</section>

<footer>
    <p>&copy; <?= date('Y') ?> Osman Nur Chaidir. All Rights Reserved.</p>
</footer>

<script>
    // Tab switching logic
    function openTab(group, tabId, event) {
        // Handle buttons
        const btns = event.target.parentElement.querySelectorAll('.tab-btn');
        btns.forEach(b => b.classList.remove('active'));
        event.target.classList.add('active');

        // Handle content with smooth transition
        const contents = document.querySelectorAll(`.${group}-content`);
        contents.forEach(c => {
            c.style.opacity = '0';
            c.style.transform = 'translateY(10px)';
            setTimeout(() => c.classList.remove('active'), 200); // Wait for fade out
        });
        
        const targetContent = document.getElementById(`${group}-${tabId}`);
        setTimeout(() => {
            targetContent.classList.add('active');
            // Small delay to ensure display:block applies before opacity changes
            setTimeout(() => {
                targetContent.style.opacity = '1';
                targetContent.style.transform = 'translateY(0)';
            }, 50);
        }, 200);
    }

    // Scroll Animation Observer Setup
    document.addEventListener('DOMContentLoaded', () => {
        const reveals = document.querySelectorAll('.reveal');
        
        // Initial check in case elements are already in view
        checkReveal();

        function checkReveal() {
            const windowHeight = window.innerHeight;
            const elementVisible = 100;

            reveals.forEach(reveal => {
                const elementTop = reveal.getBoundingClientRect().top;
                if (elementTop < windowHeight - elementVisible) {
                    reveal.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', checkReveal);
        
        // Run once right away
        setTimeout(checkReveal, 100);
    });
</script>
</body>
</html>
