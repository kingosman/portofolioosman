-- Update for Certifications Type
ALTER TABLE certifications ADD COLUMN category ENUM('certification', 'achievement') NOT NULL DEFAULT 'certification';
