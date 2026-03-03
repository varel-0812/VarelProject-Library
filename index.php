<?php
session_start();
require_once 'config/database.php';
require_once 'classes/Project.php';

$database = new Database();
$db = $database->getConnection();
$project = new Project($db);

// Get all projects
$stmt = $project->read();
$total_projects = $project->getCount();

// Get featured projects
$featured_stmt = $project->getFeatured();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Library | Portfolio PHP Developer</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <!-- Loading Animation -->
    <div class="loading">
        <div class="loading-spinner"></div>
    </div>

    <!-- Particle Background -->
    <div id="particles-js"></div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <a href="index.php" class="logo">
                    <i class="fas fa-code"></i>
                    <span>Project<span class="highlight">Library</span></span>
                </a>
            </div>
            <div class="nav-menu" id="nav-menu">
                <ul class="nav-links">
                    <li><a href="#home" class="active">Home</a></li>
                    <li><a href="#projects">Projects</a></li>
                    <li><a href="#featured">Featured</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="admin/" class="admin-link"><i class="fas fa-crown"></i> Admin</a></li>
                </ul>
            </div>
            <div class="nav-toggle" id="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content" data-aos="fade-up">
            <h1 class="glitch" data-text="PHP Project Library">PHP Project Library</h1>
            <p class="hero-subtitle">Koleksi project PHP terbaik dengan desain elegan dan fungsionalitas premium</p>
            <div class="hero-buttons">
                <a href="#projects" class="btn primary"><i class="fas fa-project-diagram"></i> Lihat Projects</a>
                <a href="#featured" class="btn outline"><i class="fas fa-star"></i> Featured</a>
            </div>
        </div>
        <div class="hero-scroll">
            <span>Scroll Down</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="projects">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2>My <span class="highlight">Projects</span></h2>
                <p>Kumpulan project PHP yang telah saya kerjakan dengan penuh dedikasi</p>
            </div>

            <div class="filter-buttons" data-aos="fade-up">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="Web Application">Web App</button>
                <button class="filter-btn" data-filter="Desktop Application">Desktop</button>
                <button class="filter-btn" data-filter="Mobile Application">Mobile</button>
            </div>

            <div class="projects-grid" id="projects-grid">
                <?php
                if($stmt && $stmt->rowCount() > 0) {
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                ?>
                <div class="project-card" data-category="<?php echo $category; ?>" data-aos="fade-up">
                    <div class="project-image">
                        <img src="<?php echo $image_url; ?>" alt="<?php echo $title; ?>">
                        <div class="project-overlay">
                            <div class="project-actions">
                                <a href="project-detail.php?id=<?php echo $id; ?>" class="action-btn"><i class="fas fa-eye"></i></a>
                                <?php if(!empty($project_url)): ?>
                                <a href="<?php echo $project_url; ?>" target="_blank" class="action-btn"><i class="fas fa-link"></i></a>
                                <?php endif; ?>
                                <?php if(!empty($github_url)): ?>
                                <a href="<?php echo $github_url; ?>" target="_blank" class="action-btn"><i class="fab fa-github"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="project-info">
                        <h3><?php echo $title; ?></h3>
                        <p><?php echo substr($description, 0, 100) . '...'; ?></p>
                        <div class="project-tech">
                            <?php 
                            if(!empty($technologies)) {
                                $techs = explode(',', $technologies);
                                foreach($techs as $tech) {
                                    echo '<span class="tech-tag">' . trim($tech) . '</span>';
                                }
                            }
                            ?>
                        </div>
                        <?php if($featured == 1): ?>
                        <span class="featured-badge"><i class="fas fa-star"></i> Featured</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo '<div class="no-projects">';
                    echo '<i class="fas fa-folder-open"></i>';
                    echo '<h3>Belum ada project</h3>';
                    echo '<p>Silahkan tambahkan project melalui admin panel</p>';
                    echo '<a href="admin/" class="btn primary"><i class="fas fa-crown"></i> Buka Admin</a>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Featured Section -->
    <section id="featured" class="featured">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2>Featured <span class="highlight">Projects</span></h2>
                <p>Project pilihan dengan kualitas terbaik</p>
            </div>

            <div class="featured-grid">
                <?php
                if($featured_stmt && $featured_stmt->rowCount() > 0) {
                    while($featured = $featured_stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="featured-card" data-aos="flip-left">
                    <div class="featured-content">
                        <span class="featured-icon"><i class="fas fa-crown"></i></span>
                        <h3><?php echo $featured['title']; ?></h3>
                        <p><?php echo substr($featured['description'], 0, 120) . '...'; ?></p>
                        <div class="featured-meta">
                            <span><i class="far fa-calendar"></i> <?php echo date('M Y', strtotime($featured['completion_date'])); ?></span>
                            <a href="project-detail.php?id=<?php echo $featured['id']; ?>" class="learn-more">Learn More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="about-grid">
                <div class="about-content" data-aos="fade-right">
                    <h2>About The <span class="highlight">Creator</span></h2>
                    <p>Seorang PHP Developer dengan pengalaman lebih dari 5 tahun dalam membangun aplikasi web yang scalable dan maintainable. Spesialisasi dalam pengembangan aplikasi berbasis PHP Native dan Framework Laravel.</p>
                    <div class="skills">
                        <div class="skill-item">
                            <span class="skill-name">PHP</span>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: 95%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <span class="skill-name">MySQL</span>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: 90%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <span class="skill-name">JavaScript</span>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: 85%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <span class="skill-name">Laravel</span>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="about-stats" data-aos="fade-left">
                    <div class="stat-item">
                        <span class="stat-number" data-target="<?php echo $total_projects; ?>">0</span>
                        <span class="stat-label">Total Projects</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-target="<?php echo $project->getFeaturedCount(); ?>">0</span>
                        <span class="stat-label">Featured</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-target="5">0</span>
                        <span class="stat-label">Years Exp</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <i class="fas fa-code"></i>
                    <span>ProjectLibrary</span>
                </div>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-github"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
                <p class="copyright">&copy; 2024 ProjectLibrary. Created with <i class="fas fa-heart"></i> by PHP Developer</p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/main.js"></script>
</body>
</html>