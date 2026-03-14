<?php
require_once 'config/database.php';

// Fetch settings
$slogan = getSetting($conn, 'slogan') ?: 'Empowering Businesses, Elevating Digitals';
$intro = getSetting($conn, 'short_intro') ?: 'Welcome to my portfolio.';
$email = getSetting($conn, 'email') ?: 'email@example.com';
$wa = getSetting($conn, 'wa_number') ?: '62800000000';
$hero_image = getSetting($conn, 'hero_image') ?: 'https://cdni.iconscout.com/illustration/premium/thumb/businessman-posing-with-crossed-arms-illustration-download-in-svg-png-gif-file-formats--business-man-male-employee-manager-pack-professionals-illustrations-4001927.png';

$fact_wirausaha = getSetting($conn, 'fact_wirausaha');
$fact_bisnis_dimentori = getSetting($conn, 'fact_bisnis_dimentori');
$fact_anggota_dipimpin = getSetting($conn, 'fact_anggota_dipimpin');
$fact_audiens = getSetting($conn, 'fact_audiens');
$fact_prestasi = getSetting($conn, 'fact_prestasi');
$fact_pembicara = getSetting($conn, 'fact_pembicara');

// Fetch Data
$orgs = $conn->query("SELECT * FROM organizations ORDER BY order_num ASC");

$exps = $conn->query("SELECT * FROM experiences ORDER BY order_num ASC");
$expList = ['work'=>[], 'speaking'=>[], 'writing'=>[]];
while($row = $exps->fetch_assoc()) $expList[$row['category']][] = $row;

$skills = $conn->query("SELECT * FROM skills ORDER BY order_num ASC");
$skillList = ['digital_marketing'=>[], 'business_mentor'=>[], 'website_development'=>[], 'sociology'=>[], 'others'=>[]];
while($row = $skills->fetch_assoc()) $skillList[$row['category']][] = $row;

$certs = $conn->query("SELECT * FROM certifications ORDER BY order_num ASC");
$certList = ['certification'=>[], 'achievement'=>[]];
while($row = $certs->fetch_assoc()) $certList[$row['category']][] = $row;

$services = $conn->query("SELECT * FROM services ORDER BY order_num ASC");

$activities = $conn->query("SELECT * FROM activities ORDER BY order_num ASC");
$photos = [];
$logos = [];
$photos = [];
$logos = [];
while($row = $activities->fetch_assoc()) {
    if ($row['type'] === 'photo') $photos[] = $row;
    else $logos[] = $row;
}

$news_items = $conn->query("SELECT * FROM news ORDER BY order_num ASC");
$testimonials = $conn->query("SELECT * FROM testimonials ORDER BY order_num ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Osman Nur Chaidir | Mentor Pengusaha Muda Berpengalaman 10 Tahun</title>
    <meta name="description" content="Berwirausaha sejak kelas 3 SMP, Osman Nur Chaidir kini telah membimbing lebih dari 150 pengusaha. Dapatkan insight bisnis berbasis pengalaman 10 tahun lebih.">
    <meta name="google-site-verification" content="JfPZPG5GoGD6-XfpLUBSKFT0s26R00yFTtSaJm4aGoc" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://osmannurchaidir.ct.ws/"> 
    <meta property="og:title" content="Osman Nur Chaidir | Mentor Pengusaha Muda Berpengalaman 10 Tahun">
    <meta property="og:description" content="Berwirausaha sejak kelas 3 SMP, Osman Nur Chaidir kini telah membimbing lebih dari 150 pengusaha. Dapatkan insight bisnis berbasis pengalaman 10 tahun lebih.">
    <meta property="og:image" content="<?= htmlspecialchars($hero_image) ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="Osman Nur Chaidir | Mentor Pengusaha Muda Berpengalaman 10 Tahun">
    <meta property="twitter:description" content="Berwirausaha sejak kelas 3 SMP, Osman Nur Chaidir kini telah membimbing lebih dari 150 pengusaha. Dapatkan insight bisnis berbasis pengalaman 10 tahun lebih.">
    <meta property="twitter:image" content="<?= htmlspecialchars($hero_image) ?>">
    
    <!-- Plus Jakarta Sans Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Swiper CSS for Sliders -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
    
    <style>
        :root {
            --primary: #5d0001;
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

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; -webkit-font-smoothing: antialiased; }
        body { background-color: var(--bg); color: var(--text-body); line-height: 1.6; overflow-x: hidden; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        img { max-width: 100%; height: auto; display: block; }
        
        .fade-in-up { opacity: 0; transform: translateY(40px); transition: opacity 0.8s ease-out, transform 0.8s ease-out; }
        .fade-in-up.visible { opacity: 1; transform: translateY(0); }

        /* General layout */
        section { padding: 100px 0; position: relative; }
        .bg-white { background-color: var(--surface); }
        .section-header { margin-bottom: 60px; text-align: center; }
        .section-header h2 { font-size: 2.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 16px; letter-spacing: -0.02em; }
        .section-header p { font-size: 1.15rem; max-width: 700px; margin: 0 auto; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 14px 32px; font-size: 1rem; font-weight: 600; border-radius: 50px; transition: var(--transition); text-decoration: none; cursor: pointer; border: none; }
        .btn-primary { background-color: var(--primary); color: #ffffff; box-shadow: 0 4px 15px rgba(93, 0, 1, 0.2); }
        .btn-primary:hover { background-color: var(--primary-hover); transform: translateY(-3px); box-shadow: 0 8px 25px rgba(93, 0, 1, 0.3); color: #ffffff; }
        .btn-outline { background-color: transparent; color: var(--text-main); border: 2px solid var(--border); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); background-color: var(--primary-light); }

        /* Hero */
        .hero { position: relative; padding: 120px 0 80px; background-color: var(--surface); overflow: hidden; }
        .hero::after { content: ""; position: absolute; top: -10%; right: -5%; width: 40%; height: 100%; background: radial-gradient(circle, var(--primary-light) 0%, rgba(255,255,255,0) 70%); z-index: 0; border-radius: 50%; }
        .hero-container { display: flex; align-items: center; justify-content: space-between; gap: 60px; position: relative; z-index: 1; }
        .hero-content { flex: 1.2; max-width: 600px; }
        .tagline { display: inline-block; color: var(--primary); font-weight: 700; font-size: 0.9rem; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 20px; padding: 8px 16px; background: var(--primary-light); border-radius: 50px; }
        .hero h1 { font-size: 3.8rem; font-weight: 800; color: var(--text-main); line-height: 1.1; margin-bottom: 24px; letter-spacing: -0.02em; }
        .hero p { font-size: 1.15rem; margin-bottom: 40px; line-height: 1.8; }
        .hero-image { flex: 0.8; display: flex; justify-content: flex-end; position: relative; }
        .hero-image img { width: 100%; max-width: 450px; filter: drop-shadow(0 20px 30px rgba(93, 0, 1, 0.15)); transition: transform 0.5s ease; }
        .hero-image img:hover { transform: translateY(-10px); }

        /* Stats & Facts */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; text-align: center; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 30px 20px; transition: var(--transition); }
        .stat-card:hover { transform: translateY(-5px); border-color: var(--primary); box-shadow: 0 10px 30px rgba(93,0,1,0.06); }
        .stat-number { font-size: 2.5rem; font-weight: 800; color: var(--primary); margin-bottom: 8px; line-height: 1; }
        .stat-label { font-size: 0.95rem; font-weight: 600; color: var(--text-main); text-transform: uppercase; letter-spacing: 0.5px; }

        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }

        /* Activity Sliders & Logos */
        .swiper-activities { width: 100%; padding: 20px 0 50px; }
        .swiper-activities .swiper-slide { width: 360px; height: 270px; border-radius: var(--radius-md); overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.05); position: relative; }
        .swiper-activities img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .activity-overlay { position: absolute; bottom: 0; left: 0; right: 0; padding: 20px; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: #fff; transform: translateY(100%); transition: transform 0.3s ease; }
        .swiper-activities .swiper-slide:hover .activity-overlay { transform: translateY(0); }
        .swiper-activities .swiper-slide:hover img { transform: scale(1.05); }
        .swiper-pagination-bullet-active { background: var(--primary) !important; }

        .logos-wrapper { padding: 40px 0; border-top: 1px solid var(--border); margin-top: 40px; }
        .logos-title { text-align: center; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 30px; font-weight: 600; }
        .swiper-logos { width: 100%; }
        .swiper-logos .swiper-slide { display: flex; align-items: center; justify-content: center; height: 100px; padding: 15px; filter: grayscale(100%) opacity(0.6); transition: var(--transition); }
        .swiper-logos .swiper-slide:hover { filter: grayscale(0%) opacity(1); }
        .swiper-logos img { max-height: 100%; max-width: 100%; object-fit: contain; }

        /* Cards/Grid System */
        .grid-3 { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; }
        .clean-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 32px; transition: var(--transition); display: flex; flex-direction: column; overflow: hidden; position: relative; }
        .clean-card:hover { border-color: rgba(93, 0, 1, 0.3); box-shadow: 0 20px 40px rgba(15, 23, 42, 0.05); transform: translateY(-5px); z-index: 2; }
        .clean-card .badge { font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; display: inline-block; background: var(--primary-light); padding: 4px 12px; border-radius: 20px;}
        .clean-card h3 { font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px; line-height: 1.4; }
        .org-card { padding: 0; }
        .org-image { width: 100%; height: 200px; background: #f8fafc; overflow: hidden; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid var(--border); }
        .org-image img { width: 100%; height: 100%; object-fit: cover; }
        .org-image.no-image { padding: 20px; text-align: center; color: var(--text-muted); font-size: 0.9em; background: #e2e8f0; }
        .org-content { padding: 25px; flex: 1; display: flex; flex-direction: column; }

        /* Tabs System */
        .tabs-header { display: flex; gap: 12px; margin-bottom: 40px; border-bottom: 2px solid var(--border); padding-bottom: 12px; overflow-x: auto; scrollbar-width: none; }
        .tabs-header::-webkit-scrollbar { display: none; }
        .tab-btn { background: transparent; border: none; color: var(--text-muted); font-size: 1.05rem; font-weight: 600; padding: 8px 16px; cursor: pointer; position: relative; transition: color 0.3s ease; white-space: nowrap; }
        .tab-btn:hover { color: var(--text-main); }
        .tab-btn.active { color: var(--primary); }
        .tab-btn::after { content: ''; position: absolute; bottom: -14px; left: 0; width: 100%; height: 2px; background-color: var(--primary); transform: scaleX(0); transition: transform 0.3s ease; }
        .tab-btn.active::after { transform: scaleX(1); }
        .tab-content { display: none; opacity: 0; transform: translateY(10px); transition: all 0.4s ease; }
        .tab-content.active { display: block; opacity: 1; transform: translateY(0); }

        /* Timeline */
        .timeline { display: flex; flex-direction: column; gap: 24px; }
        .timeline-item { display: flex; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 32px; transition: var(--transition); }
        .timeline-item:hover { border-color: var(--primary); box-shadow: 0 10px 30px rgba(93,0,1,0.05); }
        .timeline-date { flex: 0 0 180px; font-weight: 700; color: var(--primary); font-size: 0.95rem; padding-right: 24px; }
        .timeline-content { flex: 1; border-left: 1px solid var(--border); padding-left: 32px; }
        .timeline-content h4 { font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 12px; }

        /* Pricing Ratecard */
        .price-card { border: 2px solid var(--border); border-radius: var(--radius-lg); padding: 40px; text-align: center; display: flex; flex-direction: column; transition: var(--transition); background: var(--surface); }
        .price-card:hover { border-color: var(--primary); transform: translateY(-10px); box-shadow: 0 20px 40px rgba(93,0,1,0.08); }
        .price-title { font-size: 1.4rem; font-weight: 800; color: var(--text-main); margin-bottom: 15px; }
        .price-amount { font-size: 2.2rem; font-weight: 800; color: var(--primary); margin-bottom: 25px; letter-spacing: -1px; }
        .price-desc { font-size: 1rem; color: var(--text-body); margin-bottom: 25px; flex: 1; }
        .price-terms { font-size: 0.85rem; color: var(--text-muted); background: var(--bg); padding: 12px; border-radius: var(--radius-sm); margin-bottom: 25px; text-align: left;}

        /* News & Testimonials */
        .news-card { border-radius: var(--radius-md); overflow: hidden; background: var(--surface); border: 1px solid var(--border); transition: var(--transition); height: 100%; display: flex; flex-direction: column; text-decoration: none; color: inherit; }
        .news-card:hover { transform: translateY(-5px); border-color: var(--primary); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .news-thumb { height: 180px; width: 100%; position: relative; overflow: hidden; }
        .news-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .news-category { position: absolute; top: 12px; left: 12px; background: var(--primary); color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .news-content { padding: 20px; }
        .news-content h4 { font-size: 1.1rem; color: var(--text-main); font-weight: 700; line-height: 1.4; }

        .testi-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 30px; position: relative; transition: var(--transition); height: 100%; display: flex; flex-direction: column; }
        .testi-card::before { content: '"'; position: absolute; top: 15px; right: 20px; font-size: 5rem; color: var(--primary-light); font-family: serif; line-height: 1; z-index: 0; opacity: 0.5; }
        .testi-content { position: relative; z-index: 1; font-size: 0.95rem; font-style: italic; margin-bottom: 25px; color: var(--text-body); line-height: 1.6; flex: 1; }
        .testi-user { display: flex; align-items: center; gap: 12px; position: relative; z-index: 1; }
        .testi-user img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-light); }
        .testi-user h5 { font-weight: 700; color: var(--text-main); font-size: 0.95rem; }
        .testi-user span { font-size: 0.8rem; color: var(--text-muted); }

        .swiper-testimonials { padding: 20px 10px 60px; }

        .skill-thumb-box { width: 50px; height: 50px; background: var(--primary-light); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; }
        .skill-thumb-box img { width: 30px; height: 30px; object-fit: contain; }

        /* CTA */
        .cta-section { background-color: var(--primary); border-radius: var(--radius-lg); padding: 80px 40px; text-align: center; margin: 0 24px 120px; color: #ffffff; position: relative; overflow: hidden; }
        .cta-section::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('data:image/svg+xml;utf8,<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" stroke="rgba(255,255,255,0.05)" stroke-width="2" fill="none"/></svg>') repeat; opacity: 0.5; z-index: 0; }
        .cta-content { position: relative; z-index: 1; }
        .cta-content h2 { font-size: 2.8rem; font-weight: 800; margin-bottom: 20px; color: #ffffff; }
        .cta-btns { display: flex; justify-content: center; gap: 16px; margin-top:40px; }
        .btn-white { background: #ffffff; color: var(--primary); }
        .btn-glass { background: rgba(255,255,255,0.1); color: #ffffff; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); }

        footer { text-align: center; padding: 40px 0; border-top: 1px solid var(--border); color: var(--text-muted); font-size: 0.95rem; background: var(--surface); }

        @media (max-width: 992px) {
            .hero-container { flex-direction: column-reverse; text-align: center; }
            .hero h1 { font-size: 3rem; }
            .hero-image { justify-content: center; }
            .timeline-item { flex-direction: column; padding: 24px; }
            .timeline-date { margin-bottom: 16px; padding-right: 0; }
            .timeline-content { border-left: none; padding-left: 0; border-top: 1px solid var(--border); padding-top: 16px; }
        }
        @media (max-width: 768px) {
            .section-header h2 { font-size: 2rem; }
            .swiper-activities .swiper-slide { width: 260px; height: 320px; }
            .cta-section { padding: 60px 24px; margin: 0 16px 80px; }
            .cta-content h2 { font-size: 2rem; }
            .cta-btns { flex-direction: column; }
        }
    </style>
</head>
<body>

<!-- HERO -->
<section class="hero">
    <div class="container hero-container">
        <div class="hero-content fade-in-up">
            <span class="tagline">Welcome to my profile</span>
            <h1><?= htmlspecialchars($slogan) ?></h1>
            <p><?= nl2br(htmlspecialchars($intro)) ?></p>
            <div style="display: flex; gap: 16px; flex-wrap: wrap; justify-content: flex-start;">
                <button class="btn btn-primary" onclick="document.getElementById('services').scrollIntoView();">View Pricing</button>
                <button class="btn btn-outline" onclick="document.getElementById('facts').scrollIntoView();">Explore Profile</button>
            </div>
        </div>
        <div class="hero-image fade-in-up" style="transition-delay: 0.2s;">
            <img src="<?= htmlspecialchars($hero_image) ?>" alt="Osman Nur Chaidir">
        </div>
    </div>
</section>

<!-- DATA & FACTS -->
<?php if(!empty($fact_wirausaha) || !empty($fact_bisnis_dimentori) || !empty($fact_anggota_dipimpin) || !empty($fact_audiens) || !empty($fact_prestasi) || !empty($fact_pembicara)): ?>
<section id="facts" class="bg-white">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>Data & Facts</h2>
            <p class="text-muted">A quick glimpse into my professional milestones and impact.</p>
        </div>
        <div class="stats-grid fade-in-up" style="transition-delay: 0.2s;">
            <?php if($fact_wirausaha): ?>
                <div class="stat-card">
                    <div class="stat-number"><?= htmlspecialchars($fact_wirausaha) ?></div>
                    <div class="stat-label">Pengalaman Wirausaha</div>
                </div>
            <?php endif; ?>
            <?php if($fact_bisnis_dimentori): ?>
                <div class="stat-card">
                    <div class="stat-number"><?= htmlspecialchars($fact_bisnis_dimentori) ?></div>
                    <div class="stat-label">Bisnis Dimentori</div>
                </div>
            <?php endif; ?>
            <?php if($fact_anggota_dipimpin): ?>
                <div class="stat-card">
                    <div class="stat-number"><?= htmlspecialchars($fact_anggota_dipimpin) ?></div>
                    <div class="stat-label">Anggota Dipimpin</div>
                </div>
            <?php endif; ?>
            <?php if($fact_audiens): ?>
                <div class="stat-card">
                    <div class="stat-number"><?= htmlspecialchars($fact_audiens) ?></div>
                    <div class="stat-label">Audiens Dicapai</div>
                </div>
            <?php endif; ?>
            <?php if($fact_prestasi): ?>
                <div class="stat-card">
                    <div class="stat-number"><?= htmlspecialchars($fact_prestasi) ?></div>
                    <div class="stat-label">Penghargaan/Prestasi</div>
                </div>
            <?php endif; ?>
            <?php if($fact_pembicara): ?>
                <div class="stat-card">
                    <div class="stat-number"><?= htmlspecialchars($fact_pembicara) ?></div>
                    <div class="stat-label">Sesi Sebagai Pembicara</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- SLIDER GALERI AKTIVITAS & LOGO -->
<?php if(!empty($photos) || !empty($logos)): ?>
<section id="gallery" style="padding: 60px 0;">
    <div class="container fade-in-up">
        <?php if(!empty($photos)): ?>
        <!-- Photo Slider -->
        <h3 style="text-align: center; margin-bottom: 20px; font-weight:700; color:var(--text-main);">Activity Highlights</h3>
        <div class="swiper swiper-activities">
            <div class="swiper-wrapper">
                <?php foreach($photos as $p): ?>
                <div class="swiper-slide">
                    <img src="<?= htmlspecialchars($p['image_path']) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
                    <?php if(!empty($p['title'])): ?>
                        <div class="activity-overlay">
                            <h4 style="font-weight:700; margin:0; font-size:1.1rem;"><?= htmlspecialchars($p['title']) ?></h4>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <?php endif; ?>

        <?php if(!empty($logos)): ?>
        <!-- Logos -->
        <div class="logos-wrapper">
            <div class="logos-title">Trusted By & Collaborated With</div>
            <div class="swiper swiper-logos">
                <div class="swiper-wrapper">
                    <?php foreach($logos as $l): ?>
                    <div class="swiper-slide">
                        <img src="<?= htmlspecialchars($l['image_path']) ?>" alt="<?= htmlspecialchars($l['title']) ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- ORGANIZATIONS WITH IMAGES -->
<section id="organizations" class="bg-white">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>Business & Organizations</h2>
            <p class="text-muted">Ventures and organizations I actively lead and participate in.</p>
        </div>
        
        <div class="grid-3 fade-in-up" style="transition-delay: 0.2s;">
            <?php if($orgs->num_rows > 0): while($org = $orgs->fetch_assoc()): ?>
            <div class="clean-card org-card">
                <div class="org-image <?= empty($org['image_path']) ? 'no-image' : '' ?>">
                    <?php if(!empty($org['image_path'])): ?>
                        <img src="<?= htmlspecialchars($org['image_path']) ?>" alt="<?= htmlspecialchars($org['name']) ?>">
                    <?php else: ?>
                        <span>No Image Available</span>
                    <?php endif; ?>
                </div>
                <div class="org-content">
                    <div>
                        <span class="badge"><?= htmlspecialchars($org['type']) ?></span>
                        <h3><?= htmlspecialchars($org['name']) ?></h3>
                    </div>
                    <p style="margin-top:auto; font-weight:600; color:var(--primary);"><?= htmlspecialchars($org['role']) ?></p>
                </div>
            </div>
            <?php endwhile; else: ?>
                <p style="color:var(--text-muted); width:100%; text-align:center;">No data added yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- EXPERIENCES -->
<section id="experience">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>Professional Journey</h2>
            <p class="text-muted">Track record across roles, speaking engagements, and publications.</p>
        </div>
        <div class="fade-in-up" style="transition-delay: 0.2s;">
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
                                <p style="color:var(--text-muted);">No entries available.</p>
                            <?php else: foreach($expList[$cat] as $e): ?>
                                <div class="timeline-item">
                                    <div class="timeline-date"><?= htmlspecialchars($e['date_range']) ?></div>
                                    <div class="timeline-content">
                                        <h4><?= htmlspecialchars($e['title']) ?></h4>
                                        <p><?= nl2br(htmlspecialchars($e['description'])) ?></p>
                                        <?php if(!empty($e['link'])): ?>
                                            <a href="<?= htmlspecialchars($e['link']) ?>" target="_blank" style="color:var(--primary); font-weight:700; text-decoration:none; font-size:0.9rem; display:inline-block; margin-top:10px;">Listen / Read Work &rarr;</a>
                                        <?php endif; ?>
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

<!-- SKILLS -->
<section id="skills" class="bg-white">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>Technical Skills</h2>
        </div>
        <div class="fade-in-up">
            <div class="tabs-header">
                <button class="tab-btn active" onclick="switchTab(this, 'skill', 'digital_marketing')">Digital Marketing</button>
                <button class="tab-btn" onclick="switchTab(this, 'skill', 'business_mentor')">Business Mentor</button>
                <button class="tab-btn" onclick="switchTab(this, 'skill', 'website_development')">Web Development</button>
                <button class="tab-btn" onclick="switchTab(this, 'skill', 'sociology')">Sociology</button>
                <button class="tab-btn" onclick="switchTab(this, 'skill', 'others')">Others</button>
            </div>
            <div class="skill-contents">
                <?php foreach(['digital_marketing', 'business_mentor', 'website_development', 'sociology', 'others'] as $idx => $cat): ?>
                    <div id="skill-<?= $cat ?>" class="tab-content <?= $idx === 0 ? 'active' : '' ?>">
                        <div class="grid-3">
                        <?php if(empty($skillList[$cat])): ?>
                            <p style="color:var(--text-muted);">No entries available.</p>
                        <?php else: foreach($skillList[$cat] as $s): ?>
                            <div class="clean-card" style="padding: 24px;">
                                <?php if(!empty($s['thumbnail'])): ?>
                                    <div class="skill-thumb-box">
                                        <img src="<?= htmlspecialchars($s['thumbnail']) ?>" alt="<?= htmlspecialchars($s['name']) ?>">
                                    </div>
                                <?php endif; ?>
                                <h3 style="margin-bottom:0; font-size:1.15rem;"><?= htmlspecialchars($s['name']) ?></h3>
                                <?php if(!empty($s['portfolio_link'])): ?>
                                    <a href="<?= htmlspecialchars($s['portfolio_link']) ?>" target="_blank" style="margin-top:10px;display:inline-block;color:var(--primary);font-size:0.9em;font-weight:600;text-decoration:none;">View Project &rarr;</a>
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

<!-- CERTIFICATIONS & ACHIEVEMENTS -->
<section id="certifications">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>Certifications & Achievements</h2>
            <p class="text-muted">Academic credentials and professional recognitions.</p>
        </div>
        <div class="fade-in-up">
            <div class="tabs-header">
                <button class="tab-btn active" onclick="switchTab(this, 'cert', 'certification')">Certifications</button>
                <button class="tab-btn" onclick="switchTab(this, 'cert', 'achievement')">Achievements</button>
            </div>
            <div class="cert-contents">
                <?php foreach(['certification', 'achievement'] as $idx => $cat): ?>
                    <div id="cert-<?= $cat ?>" class="tab-content <?= $idx === 0 ? 'active' : '' ?>">
                        <div class="grid-3">
                        <?php if(empty($certList[$cat])): ?>
                            <p style="color:var(--text-muted); width: 100%; text-align:center;">No <?= $cat ?>s added yet.</p>
                        <?php else: foreach($certList[$cat] as $c): ?>
                            <div class="clean-card">
                                <span class="badge"><?= ucfirst($cat) ?></span>
                                <h3><?= htmlspecialchars($c['title']) ?></h3>
                                <p style="font-size: 0.95rem; margin-bottom: 20px;"><?= htmlspecialchars($c['description']) ?></p>
                                <a href="<?= htmlspecialchars($c['image_path']) ?>" target="_blank" class="btn btn-outline" style="border-radius:12px; font-size: 0.85rem; padding: 10px 20px;">View Credential &rarr;</a>
                            </div>
                        <?php endforeach; endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- NEWS & VIDEOS -->
<?php if($news_items->num_rows > 0): ?>
<section id="news">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>News & Videos</h2>
            <p class="text-muted">Featured press coverage and digital content.</p>
        </div>
        <div class="grid-3 fade-in-up">
            <?php while($news = $news_items->fetch_assoc()): ?>
            <a href="<?= htmlspecialchars($news['link']) ?>" target="_blank" class="news-card">
                <div class="news-thumb">
                    <span class="news-category"><?= $news['category'] ?></span>
                    <img src="<?= htmlspecialchars($news['thumbnail']) ?>" alt="<?= htmlspecialchars($news['title']) ?>">
                </div>
                <div class="news-content">
                    <h4><?= htmlspecialchars($news['title']) ?></h4>
                    <div style="margin-top:15px; color:var(--primary); font-weight:700; font-size:0.9rem;">View Content &rarr;</div>
                </div>
            </a>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- TESTIMONIALS -->
<?php if($testimonials->num_rows > 0): ?>
<section id="testimonials" class="bg-white">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>Kind Words</h2>
            <p class="text-muted">What partners and clients say about our collaboration.</p>
        </div>
        <div class="fade-in-up">
            <div class="swiper swiper-testimonials">
                <div class="swiper-wrapper">
                    <?php while($testi = $testimonials->fetch_assoc()): ?>
                    <div class="swiper-slide">
                        <div class="testi-card">
                            <div class="testi-content">"<?= htmlspecialchars($testi['content']) ?>"</div>
                            <div class="testi-user">
                                <?php if($testi['image_path']): ?><img src="<?= htmlspecialchars($testi['image_path']) ?>" alt="<?= htmlspecialchars($testi['name']) ?>"><?php endif; ?>
                                <div>
                                    <h5><?= htmlspecialchars($testi['name']) ?></h5>
                                    <span><?= htmlspecialchars($testi['position']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- SERVICES & RATECARD SECTION -->
<section id="services" class="bg-white" style="border-top:1px solid var(--border);">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2>Services & Ratecard</h2>
            <p class="text-muted">Professional services I offer to help your business grow.</p>
        </div>
        
        <div class="grid-3 fade-in-up" style="transition-delay: 0.2s;">
            <?php if($services->num_rows > 0): while($srv = $services->fetch_assoc()): ?>
            <div class="price-card">
                <div class="price-title"><?= htmlspecialchars($srv['name']) ?></div>
                <div class="price-amount"><?= htmlspecialchars($srv['price']) ?></div>
                <div class="price-desc" style="text-align: left;">
                    <?= $srv['description'] ?>
                </div>
                <div style="display:flex; flex-direction:column; gap:10px; margin-top: auto;">
                    <a href="https://wa.me/<?= htmlspecialchars($wa) ?>?text=Hello Osman, I want to discuss a potential collaboration for the <?= urlencode($srv['name']) ?> service." target="_blank" class="btn btn-primary" style="width:100%; border-radius:12px; background-color: #5d0001;">Collaborate Now</a>
                </div>
            </div>
            <?php endwhile; else: ?>
                 <p style="color:var(--text-muted); width: 100%; text-align:center;">No services added yet via CMS.</p>
            <?php endif; ?>
        </div>
    </div>
</section>


<!-- FREE BUSINESS DIAGNOSTIC BANNER -->
<section id="banner-diagnostic" style="padding: 0 0 100px 0;">
    <div class="container">
        <div style="background: linear-gradient(135deg, #5d0001 0%, #a50002 100%); border-radius: var(--radius-lg); padding: 60px 40px; color: #fff; text-align: center; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(93, 0, 1, 0.2);">
            <div style="position: absolute; top: -20px; right: -20px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0;"></div>
            <div style="position: relative; z-index: 1;">
                <h2 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 15px; letter-spacing: -0.01em;">FREE BUSINESS DIAGNOSTIC & PLANNING CANVAS</h2>
                <p style="opacity: 0.9; font-size: 1.15rem; max-width: 700px; margin: 0 auto 30px;">Get a comprehensive overview of your business health and a clear roadmap for growth. Limited sessions available.</p>
                <a href="https://kamalektur.myr.id/" target="_blank" class="btn btn-white" style="background:#fff; color:#5d0001; font-weight:800; padding:18px 40px; border-radius: 12px; font-size: 1.1rem;">Claim Your Free Session &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- CTA / CONTACT -->
<section id="contact" style="padding: 0;">
    <div class="cta-section fade-in-up">
        <div class="cta-content">
            <h2>Let's Do Great Work Together</h2>
            <p style="opacity: 0.9; font-size: 1.15rem; max-width: 600px; margin: 0 auto;">Ready to discuss your next big project or looking for a seasoned mentor? Reach out to me directly.</p>
            <div class="cta-btns">
                <a href="mailto:<?= htmlspecialchars($email) ?>" class="btn btn-white">Email Me</a>
                <a href="https://wa.me/<?= htmlspecialchars($wa) ?>" target="_blank" class="btn btn-glass">WhatsApp</a>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <p>&copy; <?= date('Y') ?> Osman Nur Chaidir. Crafted with excellence.</p>
    </div>
</footer>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<script>
    // Tab Switcher
    function switchTab(btnElement, group, targetId) {
        const header = btnElement.parentElement;
        const buttons = header.querySelectorAll('.tab-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        btnElement.classList.add('active');

        const contentContainer = header.nextElementSibling;
        const contents = contentContainer.querySelectorAll('.tab-content');
        contents.forEach(content => {
            content.style.opacity = '0';
            content.style.transform = 'translateY(10px)';
            setTimeout(() => content.classList.remove('active'), 300);
        });

        const targetElement = document.getElementById(`${group}-${targetId}`);
        setTimeout(() => {
            targetElement.classList.add('active');
            void targetElement.offsetWidth;
            targetElement.style.opacity = '1';
            targetElement.style.transform = 'translateY(0)';
        }, 350);
    }

    // Swiper Initializations
    document.addEventListener("DOMContentLoaded", () => {
        // Activity Photos
        if(document.querySelector('.swiper-activities')) {
            new Swiper('.swiper-activities', {
                effect: "coverflow",
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: "auto",
                coverflowEffect: {
                    rotate: 15,
                    stretch: 0,
                    depth: 100,
                    modifier: 1,
                    slideShadows: false,
                },
                pagination: { el: ".swiper-pagination", clickable: true },
                loop: true,
                autoplay: { delay: 3000, disableOnInteraction: false }
            });
        }
        
        // Logos carousel
        if(document.querySelector('.swiper-logos')) {
            new Swiper('.swiper-logos', {
                slidesPerView: 3,
                spaceBetween: 30,
                loop: true,
                autoplay: { delay: 2000, disableOnInteraction: false },
                breakpoints: {
                    640: { slidesPerView: 4, spaceBetween: 40 },
                    1024: { slidesPerView: 5, spaceBetween: 50 },
                }
            });
        }

        // Testimonials
        if(document.querySelector('.swiper-testimonials')) {
            new Swiper('.swiper-testimonials', {
                slidesPerView: 1,
                spaceBetween: 30,
                pagination: { el: ".swiper-pagination", clickable: true },
                loop: true,
                autoplay: { delay: 4000, disableOnInteraction: false },
                breakpoints: {
                    768: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 },
                }
            });
        }

        // Intersection Observer
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        document.querySelectorAll('.fade-in-up').forEach(el => observer.observe(el));
    });
</script>
</body>
</html>
