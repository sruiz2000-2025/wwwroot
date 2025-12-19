-- Ongoingteam - booking + contact system
-- MySQL 8.0

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE TABLE IF NOT EXISTS service_categories (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_cat_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS services (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  category_id INT UNSIGNED NOT NULL,
  name VARCHAR(160) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_service_name (name),
  KEY idx_services_category (category_id),
  CONSTRAINT fk_services_category
    FOREIGN KEY (category_id) REFERENCES service_categories(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS calendar_blackouts (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  date DATE NOT NULL,
  country_code CHAR(2) NULL,
  reason VARCHAR(255) NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_blackout_date_country (date, country_code),
  KEY idx_blackout_date (date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS calendar_special_dates (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  slot_minutes SMALLINT UNSIGNED NOT NULL DEFAULT 30,
  note VARCHAR(255) NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_special_date (date),
  KEY idx_special_date (date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS appointments (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  start_datetime DATETIME NOT NULL, -- stored in UTC
  end_datetime DATETIME NOT NULL,   -- stored in UTC
  timezone VARCHAR(64) NOT NULL,
  status ENUM('booked','pending','canceled') NOT NULL DEFAULT 'booked',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_appointment_start (start_datetime),
  KEY idx_appointment_range (start_datetime, end_datetime),
  KEY idx_appointment_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS contact_requests (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name VARCHAR(160) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(40) NULL,
  language_preference VARCHAR(30) NOT NULL DEFAULT 'English',
  contact_type ENUM('zoom','phone','email','pricing_request','zoom_special_request') NOT NULL,
  details TEXT NULL,
  preferred_datetime DATETIME NULL, -- stored in UTC if provided
  timezone VARCHAR(64) NOT NULL,
  is_special_request TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_contact_email (email),
  KEY idx_contact_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS contact_request_services (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  contact_request_id BIGINT UNSIGNED NOT NULL,
  service_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_req_service (contact_request_id, service_id),
  KEY idx_req_services_req (contact_request_id),
  KEY idx_req_services_service (service_id),
  CONSTRAINT fk_req_services_req
    FOREIGN KEY (contact_request_id) REFERENCES contact_requests(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_req_services_service
    FOREIGN KEY (service_id) REFERENCES services(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed categories
INSERT IGNORE INTO service_categories (id, name, sort_order, is_active) VALUES
  (1, 'Virtual Assistant', 10, 1),
  (2, 'Other Services', 20, 1);

-- Seed services (Virtual Assistant)

INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Real Estate VA", 10, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Small Business VA", 20, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Sales VA", 30, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Legal / Lawyers VA", 40, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Social Media VA", 50, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Bookkeeping VA", 60, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "VA for Executives", 70, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Amazon VA", 80, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "AirBNB VA", 90, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Personal VA", 100, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Marketing VA", 110, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Data Entry VA", 120, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Customer Service VA", 130, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "eCommerce VA", 140, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "eMail Management VA", 150, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Insurance VA", 160, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Property Management VA", 170, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Dental VA", 180, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "VA for SEO", 190, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "eBay VA", 200, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Shopify VA", 210, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (1, "Call Center VA", 220, 1);

-- Seed services (Other Services)

INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (2, "Social Media Management", 10, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (2, "Call Center", 20, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (2, "Customer Support", 30, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (2, "Live Chat", 40, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (2, "Contact Centre", 50, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (2, "Digital Marketing", 60, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (2, "Telesales", 70, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (2, "Web Dev", 80, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (2, "Data Entry Services", 90, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (2, "Appointment Setters", 100, 1);
INSERT IGNORE INTO services (category_id, name, sort_order, is_active) VALUES (2, "Bookkeeping", 110, 1);

-- Seed basic holiday blackouts (starter set; you can add more)

INSERT IGNORE INTO calendar_blackouts (date, country_code, reason) VALUES ('2025-01-01', 'US', 'Holiday');
INSERT IGNORE INTO calendar_blackouts (date, country_code, reason) VALUES ('2025-07-04', 'US', 'Holiday');
INSERT IGNORE INTO calendar_blackouts (date, country_code, reason) VALUES ('2025-11-11', 'US', 'Holiday');
INSERT IGNORE INTO calendar_blackouts (date, country_code, reason) VALUES ('2025-12-25', 'US', 'Holiday');
INSERT IGNORE INTO calendar_blackouts (date, country_code, reason) VALUES ('2025-01-01', 'PE', 'Holiday');
INSERT IGNORE INTO calendar_blackouts (date, country_code, reason) VALUES ('2025-05-01', 'PE', 'Holiday');
INSERT IGNORE INTO calendar_blackouts (date, country_code, reason) VALUES ('2025-07-28', 'PE', 'Holiday');
INSERT IGNORE INTO calendar_blackouts (date, country_code, reason) VALUES ('2025-07-29', 'PE', 'Holiday');
INSERT IGNORE INTO calendar_blackouts (date, country_code, reason) VALUES ('2025-12-25', 'PE', 'Holiday');
