CREATE DATABASE IF NOT EXISTS project_library;
USE project_library;

CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    image_url VARCHAR(500),
    project_url VARCHAR(500),
    github_url VARCHAR(500),
    technologies VARCHAR(500),
    completion_date DATE,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO projects (title, description, category, image_url, project_url, github_url, technologies, completion_date, featured) VALUES
('E-Commerce System', 'Full-stack e-commerce platform dengan PHP Native dan MySQL', 'Web Application', 'https://images.unsplash.com/photo-1557821552-17105176677c', '#', '#', 'PHP, MySQL, JavaScript, Bootstrap', '2024-01-15', TRUE),
('Inventory Management', 'Sistem manajemen inventory untuk gudang', 'Desktop Application', 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d', '#', '#', 'PHP, MySQL, jQuery, Bootstrap', '2024-02-20', TRUE),
('POS System', 'Point of Sale system dengan laporan keuangan', 'Web Application', 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d', '#', '#', 'PHP, MySQL, Chart.js, Bootstrap', '2024-03-10', FALSE),
('Hospital Management', 'Sistem manajemen rumah sakit', 'Web Application', 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d', '#', '#', 'PHP, MySQL, AJAX, Bootstrap', '2024-01-05', TRUE),
('School Library System', 'Sistem perpustakaan sekolah dengan fitur peminjaman', 'Web Application', 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f', '#', '#', 'PHP, MySQL, Tailwind CSS', '2024-02-28', FALSE),
('Hotel Booking', 'Sistem booking hotel online', 'Web Application', 'https://images.unsplash.com/photo-1566073771259-6a8506099945', '#', '#', 'PHP, MySQL, JavaScript, Bootstrap', '2024-03-15', TRUE);