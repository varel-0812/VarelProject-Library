<?php
session_start();
require_once 'config/database.php';
require_once 'classes/Project.php';

$database = new Database();
$db = $database->getConnection();
$project = new Project($db);

// Get project ID from URL
$id = isset($_GET['id']) ? $_GET['id'] : die('Project ID tidak ditemukan!');
$project->id = $id;

// Read project details
if(!$project->readOne()) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $project->title; ?> - Project Library</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
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
                    <li><a href="index.php#home">Home</a></li>
                    <li><a href="index.php#projects">Projects</a></li>
                    <li><a href="index.php#featured">Featured</a></li>
                    <li><a href="index.php#about">About</a></li>
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

    <!-- Project Detail -->
    <section class="project-detail-section">
        <div class="container">
            <div class="project-detail" data-aos="fade-up">
                <div class="project-detail-image">
                    <img src="<?php echo $project->image_url; ?>" alt="<?php echo $project->title; ?>">
                    <?php if($project->featured == 1): ?>
                    <span class="featured-badge-large"><i class="fas fa-crown"></i> Featured Project</span>
                    <?php endif; ?>
                </div>
                
                <div class="project-detail-content">
                    <h1><?php echo $project->title; ?></h1>
                    
                    <div class="project-meta">
                        <span class="meta-item">
                            <i class="far fa-calendar"></i>
                            <?php echo date('F Y', strtotime($project->completion_date)); ?>
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-tag"></i>
                            <?php echo $project->category; ?>
                        </span>
                    </div>

                    <div class="project-description">
                        <h3>Deskripsi Project</h3>
                        <p><?php echo nl2br($project->description); ?></p>
                    </div>

                    <div class="project-technologies">
                        <h3>Teknologi yang Digunakan</h3>
                        <div class="tech-list">
                            <?php 
                            $techs = explode(',', $project->technologies);
                            foreach($techs as $tech) {
                                echo '<span class="tech-item">' . trim($tech) . '</span>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="project-links">
                        <?php if(!empty($project->project_url)): ?>
                        <a href="<?php echo $project->project_url; ?>" target="_blank" class="btn primary">
                            <i class="fas fa-external-link-alt"></i> Live Demo
                        </a>
                        <?php endif; ?>
                        
                        <?php if(!empty($project->github_url)): ?>
                        <a href="<?php echo $project->github_url; ?>" target="_blank" class="btn outline">
                            <i class="fab fa-github"></i> Source Code
                        </a>
                        <?php endif; ?>
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

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/main.js"></script>
</body>
</html>