ALTER TABLE organizations ADD COLUMN image_path VARCHAR(255) DEFAULT NULL;

CREATE TABLE IF NOT EXISTS services (
  id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  price VARCHAR(100) NOT NULL,
  description TEXT,
  terms TEXT,
  order_num INT(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS activities (
  id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255) DEFAULT NULL,
  image_path VARCHAR(255) NOT NULL,
  type ENUM('photo', 'logo') NOT NULL,
  order_num INT(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO settings (key_name, value) VALUES 
('fact_wirausaha', '5+ Tahun'),
('fact_bisnis_dimentori', '50+'),
('fact_anggota_dipimpin', '1000+'),
('fact_audiens', '5000+'),
('fact_prestasi', '20+'),
('fact_pembicara', '100+');
