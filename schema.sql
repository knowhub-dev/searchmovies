-- Database schema for SearchMoviess v2
CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- example admin pass (admin123):
-- $2y$10$2JzZ1k5v5nJrslgqKx2sjOsg6wqCq8i5xWc5HqE1iB0y2gqLQ5J9y
-- INSERT INTO admins (email,password_hash) VALUES ('admin@example.com','$2y$10$2JzZ1k5v5nJrslgqKx2sjOsg6wqCq8i5xWc5HqE1iB0y2gqLQ5J9y');

CREATE TABLE movies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL UNIQUE,
  year INT NULL,
  poster_url VARCHAR(500) NULL,
  description TEXT NULL,
  genres VARCHAR(300) NULL,
  cast TEXT NULL,
  rating DECIMAL(3,1) NULL,
  votes INT NULL,
  telegram_channel VARCHAR(190) NULL,
  is_published TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE movie_links (
  id INT AUTO_INCREMENT PRIMARY KEY,
  movie_id INT NOT NULL,
  quality ENUM('360p','480p','720p','1080p','2160p') NOT NULL,
  tg_url VARCHAR(500) NOT NULL,
  FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
  UNIQUE KEY uq_movie_quality (movie_id, quality)
);

CREATE TABLE clicks (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  movie_id INT NOT NULL,
  quality ENUM('360p','480p','720p','1080p','2160p') NOT NULL,
  ip VARBINARY(16) NULL,
  user_agent VARCHAR(255) NULL,
  referrer VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
);
