<?php
require_once 'config/database.php';

// Safe Migration Script
$queries = [
    "INSERT IGNORE INTO settings (key_name, value) VALUES ('cv_link', '#')",
    "INSERT IGNORE INTO settings (key_name, value) VALUES ('portfolio_link', '#')",
    "INSERT IGNORE INTO settings (key_name, value) VALUES ('social_instagram', '')",
    "INSERT IGNORE INTO settings (key_name, value) VALUES ('social_linkedin', '')",
    "INSERT IGNORE INTO settings (key_name, value) VALUES ('social_facebook', '')",
    "INSERT IGNORE INTO settings (key_name, value) VALUES ('social_tiktok', '')",
    "INSERT IGNORE INTO settings (key_name, value) VALUES ('social_youtube', '')"
];

foreach ($queries as $q) {
    if ($conn->query($q)) {
        echo "Executed: $q <br>";
    } else {
        echo "Skipped/Error: " . $conn->error . " <br>";
    }
}

// Add columns if not exists
$columns = [
    "description" => "TEXT DEFAULT NULL",
    "screenshots" => "TEXT DEFAULT NULL"
];

// Update category enum
$conn->query("ALTER TABLE skills MODIFY COLUMN category ENUM('digital_marketing','business_mentor','website_development','sociology','others') NOT NULL");

foreach ($columns as $col => $type) {
    $check = $conn->query("SHOW COLUMNS FROM skills LIKE '$col'");
    if ($check->num_rows == 0) {
        if ($conn->query("ALTER TABLE skills ADD COLUMN $col $type")) {
            echo "Added column $col to skills table. <br>";
        } else {
            echo "Error adding $col: " . $conn->error . " <br>";
        }
    } else {
        echo "Column $col already exists in skills table. <br>";
    }
}

echo "<br><b>Migration complete.</b> Silakan hapus file ini demi keamanan.";
?>
