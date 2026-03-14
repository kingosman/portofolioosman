<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Osman Nur Chaidir - Portfolio</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #1e40af;
            --bg: #0f172a;
            --surface: #1e293b;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #3b82f6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background-color: var(--bg); color: var(--text); line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        /* Hero Section */
        .hero { display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 100px 20px; background: linear-gradient(135deg, rgba(15,23,42,1) 0%, rgba(30,58,138,0.2) 100%); min-height: 60vh; }
        .hero img { width: 200px; height: 200px; border-radius: 50%; object-fit: cover; border: 4px solid var(--accent); margin-bottom: 20px; box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4); }
        .hero h1 { font-size: 3rem; font-weight: 800; margin-bottom: 10px; background: -webkit-linear-gradient(#60a5fa, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero h2 { font-size: 1.5rem; color: var(--text-muted); font-weight: 400; }
        
        /* Sections */
        section { padding: 80px 0; }
        section h3 { font-size: 2.2rem; text-align: center; margin-bottom: 40px; position: relative; }
        section h3::after { content: ''; display: block; width: 60px; height: 4px; background: var(--accent); margin: 15px auto 0; border-radius: 2px; }

        /* Intro */
        .intro { text-align: center; max-width: 800px; margin: 0 auto; font-size: 1.2rem; color: #cbd5e1; }

        /* Orgs */
        .orgs-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .org-card { background: var(--surface); padding: 25px; border-radius: 15px; text-align: center; transition: transform 0.3s ease; border: 1px solid rgba(255,255,255,0.05); }
        .org-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.3); }
        .org-card h4 { font-size: 1.3rem; margin-bottom: 5px; color: var(--accent); }
        .org-card p.type { font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; }

        /* Tabs */
        .tabs { display: flex; justify-content: center; gap: 15px; margin-bottom: 30px; flex-wrap: wrap; }
        .tab-btn { padding: 12px 25px; background: transparent; color: var(--text); border: 1px solid var(--text-muted); border-radius: 30px; cursor: pointer; font-size: 1rem; transition: all 0.3s ease; }
        .tab-btn.active, .tab-btn:hover { background: var(--primary); border-color: var(--primary); color: white; }
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.5s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Experience List */
        .exp-list { max-width: 800px; margin: 0 auto; }
        .exp-item { background: var(--surface); padding: 25px; border-radius: 12px; margin-bottom: 20px; border-left: 4px solid var(--accent); }
        .exp-item h4 { font-size: 1.4rem; margin-bottom: 5px; }
        .exp-item .date { color: var(--primary); font-size: 0.9rem; margin-bottom: 15px; display: block; font-weight: 600; }
        
        /* Skill Cards */
        .skill-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .skill-card { background: var(--surface); padding: 25px; border-radius: 15px; transition: transform 0.3s; }
        .skill-card h4 { font-size: 1.3rem; margin-bottom: 10px; color: var(--accent); }
        .skill-card a { display: inline-block; margin-top: 15px; color: #fff; background: var(--primary); padding: 8px 15px; text-decoration: none; border-radius: 6px; font-size: 0.9rem; transition: background 0.3s; }
        .skill-card a:hover { background: var(--secondary); }

        /* Certs */
        .certs-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; }
        .cert-card { background: var(--surface); border-radius: 15px; overflow: hidden; }
        .cert-card img { width: 100%; height: 200px; object-fit: cover; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .cert-card .content { padding: 20px; }
        .cert-card h4 { margin-bottom: 10px; }

        /* Contact */
        .contact { text-align: center; }
        .contact-btns { display: flex; justify-content: center; gap: 20px; margin-top: 30px; }
        .contact-btn { padding: 15px 35px; border-radius: 30px; text-decoration: none; font-weight: 600; font-size: 1.1rem; transition: transform 0.3s, box-shadow 0.3s; display: inline-flex; align-items: center; gap: 10px; }
        .btn-email { background: white; color: var(--bg); }
        .btn-wa { background: #25D366; color: white; }
        .contact-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }

        footer { text-align: center; padding: 30px; border-top: 1px solid rgba(255,255,255,0.05); color: var(--text-muted); margin-top: 50px; }
    </style>
</head>
<body>

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

<header class="hero">
    <div class="container">
        <!-- Placeholder Image, Replace with real path when CMS upload is ready -->
        <img src="https://ui-avatars.com/api/?name=Osman+Nur+Chaidir&background=3b82f6&color=fff&size=200" alt="Osman Nur Chaidir">
        <h1>Osman Nur Chaidir</h1>
        <h2><?= htmlspecialchars($slogan) ?></h2>
    </div>
</header>

<section class="container">
    <div class="intro">
        <p><?= nl2br(htmlspecialchars($intro)) ?></p>
    </div>
</section>

<!-- Business & Orgs -->
<section class="container">
    <h3>Business & Organizations</h3>
    <div class="orgs-grid">
        <?php if($orgs->num_rows > 0): while($org = $orgs->fetch_assoc()): ?>
        <div class="org-card">
            <p class="type"><?= htmlspecialchars($org['type']) ?></p>
            <h4><?= htmlspecialchars($org['name']) ?></h4>
            <p><?= htmlspecialchars($org['role']) ?></p>
        </div>
        <?php endwhile; else: ?>
            <p style="text-align:center;width:100%;">No data added yet via CMS.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Experiences -->
<section class="container">
    <h3>Professional Journey</h3>
    <div class="tabs">
        <button class="tab-btn active" onclick="openTab('exp', 'work')">Work Experience</button>
        <button class="tab-btn" onclick="openTab('exp', 'speaking')">Speaking / Seminars</button>
        <button class="tab-btn" onclick="openTab('exp', 'writing')">Written Works</button>
    </div>
    
    <div class="exp-list">
        <?php foreach(['work', 'speaking', 'writing'] as $idx => $cat): ?>
            <div id="exp-<?= $cat ?>" class="tab-content exp-content <?= $idx === 0 ? 'active' : '' ?>">
                <?php if(empty($expList[$cat])): ?>
                    <p style="text-align:center;">No entries yet.</p>
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
<section class="container">
    <h3>Technical Skills</h3>
    <div class="tabs">
        <button class="tab-btn active" onclick="openSkillTab('digital_marketing')">Digital Marketing</button>
        <button class="tab-btn" onclick="openSkillTab('business_mentor')">Business Mentor</button>
        <button class="tab-btn" onclick="openSkillTab('website_development')">Web Development</button>
    </div>

    <div>
        <?php foreach(['digital_marketing', 'business_mentor', 'website_development'] as $idx => $cat): ?>
            <div id="skill-<?= $cat ?>" class="tab-content skill-content <?= $idx === 0 ? 'active' : '' ?>">
                <div class="skill-grid">
                <?php if(empty($skillList[$cat])): ?>
                    <p style="text-align:center;width:100%;">No entries yet.</p>
                <?php else: foreach($skillList[$cat] as $s): ?>
                    <div class="skill-card">
                        <h4><?= htmlspecialchars($s['name']) ?></h4>
                        <?php if(!empty($s['portfolio_link'])): ?>
                            <a href="<?= htmlspecialchars($s['portfolio_link']) ?>" target="_blank">View Portfolio &rarr;</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Certifications -->
<section class="container">
    <h3>Certifications & Achievements</h3>
    <div class="certs-grid">
        <?php if($certs->num_rows > 0): while($crt = $certs->fetch_assoc()): ?>
        <div class="cert-card">
            <!-- Render actual image if path is set, else generic placeholder -->
            <img src="<?= htmlspecialchars($crt['image_path']) ?: 'https://via.placeholder.com/300x200/1e293b/3b82f6?text=Image' ?>" alt="<?= htmlspecialchars($crt['title']) ?>">
            <div class="content">
                <h4><?= htmlspecialchars($crt['title']) ?></h4>
                <p><?= nl2br(htmlspecialchars($crt['description'])) ?></p>
            </div>
        </div>
        <?php endwhile; else: ?>
             <p style="text-align:center;width:100%;">No data added yet via CMS.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Reach Out -->
<section class="container contact">
    <h3>Let's Connect</h3>
    <p>Ready to collaborate or need a mentor? Reach out through the following channels.</p>
    <div class="contact-btns">
        <a href="mailto:<?= htmlspecialchars($email) ?>" class="contact-btn btn-email">
            &#9993; Email Me
        </a>
        <a href="https://wa.me/<?= htmlspecialchars($wa) ?>" target="_blank" class="contact-btn btn-wa">
            &#128172; WhatsApp
        </a>
    </div>
</section>

<footer>
    <p>&copy; <?= date('Y') ?> Osman Nur Chaidir. All Rights Reserved.</p>
</footer>

<script>
    function openTab(group, tabId) {
        // Handle buttons
        const btns = event.target.parentElement.children;
        for(let b of btns) b.classList.remove('active');
        event.target.classList.add('active');

        // Handle content
        const contents = document.querySelectorAll('.' + group + '-content');
        for(let c of contents) c.classList.remove('active');
        document.getElementById(group + '-' + tabId).classList.add('active');
    }

    // Wrap specific function to map it cleanly
    function openSkillTab(tabId) {
        openTab('skill', tabId);
    }
</script>
</body>
</html>
