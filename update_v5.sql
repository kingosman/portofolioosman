-- Update for CV, Portfolio, Social Media and Skill Enhancements
INSERT IGNORE INTO settings (key_name, value) VALUES 
('cv_link', '#'),
('portfolio_link', '#'),
('social_instagram', ''),
('social_linkedin', ''),
('social_facebook', ''),
('social_tiktok', ''),
('social_youtube', '');

ALTER TABLE skills ADD COLUMN description TEXT DEFAULT NULL;
ALTER TABLE skills ADD COLUMN screenshots TEXT DEFAULT NULL;
ALTER TABLE skills MODIFY COLUMN category ENUM('digital_marketing','business_mentor','website_development','sociology','others') NOT NULL;
