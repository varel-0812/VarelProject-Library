<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';
require_once '../classes/Project.php';

$database = new Database();
$db = $database->getConnection();
$project = new Project($db);

// Get statistics
$total_projects = $project->getCount();
$featured_count = $project->getFeaturedCount();

// Get all projects for table
$stmt = $project->read();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Project Library</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-crown"></i>
                <h2>Admin Panel</h2>
                <p>Welcome, <?php echo $_SESSION['admin_username']; ?></p>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="index.php" class="active"><i class="fas fa-dashboard"></i> Dashboard</a></li>
                    <li><a href="projects.php"><i class="fas fa-project-diagram"></i> Semua Projects</a></li>
                    <li><a href="add-project.php"><i class="fas fa-plus-circle"></i> Tambah Project</a></li>
                    <li><a href="logout.php" onclick="return confirm('Yakin ingin logout?')"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <h1>Dashboard</h1>
                <div class="header-user">
                    <span>Halo, <?php echo $_SESSION['admin_username']; ?></span>
                    <i class="fas fa-user-circle"></i>
                </div>
            </header>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="stat-details">
                        <h3>Total Projects</h3>
                        <p><?php echo $total_projects; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-details">
                        <h3>Featured</h3>
                        <p><?php echo $featured_count; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="stat-details">
                        <h3>Categories</h3>
                        <p>4</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-details">
                        <h3>Website Views</h3>
                        <p>1,234</p>
                    </div>
                </div>
            </div>

            <!-- Recent Projects Table -->
            <div class="recent-projects">
                <div class="table-header">
                    <h2><i class="fas fa-clock"></i> Recent Projects</h2>
                    <a href="add-project.php" class="btn-add">
                        <i class="fas fa-plus"></i> Tambah Project
                    </a>
                </div>
                
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Technologies</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($stmt && $stmt->rowCount() > 0) {
                            $no = 1;
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                if($no <= 5) { // Show only 5 recent projects
                        ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                            </td>
                            <td><span class="category-badge"><?php echo $row['category']; ?></span></td>
                            <td>
                                <?php 
                                $techs = explode(',', $row['technologies']);
                                $display_techs = array_slice($techs, 0, 2);
                                foreach($display_techs as $tech) {
                                    echo '<span class="tech-tag">' . trim($tech) . '</span> ';
                                }
                                if(count($techs) > 2) echo '...';
                                ?>
                            </td>
                            <td>
                                <?php if($row['featured'] == 1): ?>
                                    <span class="status-badge featured"><i class="fas fa-star"></i> Featured</span>
                                <?php else: ?>
                                    <span class="status-badge">Regular</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a href="edit-project.php?id=<?php echo $row['id']; ?>" class="btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete-project.php?id=<?php echo $row['id']; ?>" 
                                   class="btn-delete" 
                                   title="Delete"
                                   onclick="return confirm('Yakin ingin menghapus project ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="../project-detail.php?id=<?php echo $row['id']; ?>" 
                                   class="btn-view" 
                                   title="View"
                                   target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                                }
                                $no++;
                            }
                        } else {
                            echo '<tr><td colspan="6" style="text-align: center; padding: 2rem;">Belum ada project</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>

                <?php if($total_projects > 5): ?>
                <div class="table-footer">
                    <a href="projects.php" class="view-all">Lihat Semua Project <i class="fas fa-arrow-right"></i></a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                <div class="action-grid">
                    <a href="add-project.php" class="action-card">
                        <i class="fas fa-plus-circle"></i>
                        <h3>Tambah Project</h3>
                        <p>Buat project baru</p>
                    </a>
                    <a href="projects.php" class="action-card">
                        <i class="fas fa-list"></i>
                        <h3>Kelola Project</h3>
                        <p>Edit atau hapus project</p>
                    </a>
                    <a href="../index.php" class="action-card" target="_blank">
                        <i class="fas fa-globe"></i>
                        <h3>Lihat Website</h3>
                        <p>Preview hasil</p>
                    </a>
                    <a href="logout.php" class="action-card" onclick="return confirm('Yakin ingin logout?')">
                        <i class="fas fa-sign-out-alt"></i>
                        <h3>Logout</h3>
                        <p>Keluar dari admin</p>
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script src="../js/admin.js"></script>
</body>
</html>