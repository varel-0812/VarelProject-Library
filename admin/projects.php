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

// Handle search
$search_keyword = isset($_GET['search']) ? $_GET['search'] : '';
if($search_keyword) {
    $stmt = $project->search($search_keyword);
} else {
    $stmt = $project->read();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Projects - Project Library</title>
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
                    <li><a href="index.php"><i class="fas fa-dashboard"></i> Dashboard</a></li>
                    <li><a href="projects.php" class="active"><i class="fas fa-project-diagram"></i> Semua Projects</a></li>
                    <li><a href="add-project.php"><i class="fas fa-plus-circle"></i> Tambah Project</a></li>
                    <li><a href="logout.php" onclick="return confirm('Yakin ingin logout?')"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <h1>Kelola Projects</h1>
                <div class="header-user">
                    <span>Halo, <?php echo $_SESSION['admin_username']; ?></span>
                    <i class="fas fa-user-circle"></i>
                </div>
            </header>

            <!-- Search and Add -->
            <div class="table-toolbar">
                <form method="GET" class="search-box">
                    <input type="text" name="search" placeholder="Cari project..." value="<?php echo htmlspecialchars($search_keyword); ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
                <a href="add-project.php" class="btn-add">
                    <i class="fas fa-plus"></i> Tambah Project Baru
                </a>
            </div>

            <!-- Projects Table -->
            <div class="recent-projects">
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Technologies</th>
                            <th>Completion Date</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($stmt && $stmt->rowCount() > 0) {
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
                                foreach($techs as $tech) {
                                    echo '<span class="tech-tag">' . trim($tech) . '</span> ';
                                }
                                ?>
                            </td>
                            <td><?php echo date('d M Y', strtotime($row['completion_date'])); ?></td>
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
                        } else {
                            echo '<tr><td colspan="7" style="text-align: center; padding: 2rem;">';
                            echo '<i class="fas fa-folder-open" style="font-size: 3rem; color: #666; margin-bottom: 1rem;"></i>';
                            echo '<p>Belum ada project</p>';
                            echo '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="../js/admin.js"></script>
</body>
</html>