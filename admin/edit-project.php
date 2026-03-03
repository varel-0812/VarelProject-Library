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

// Get project ID from URL
$id = isset($_GET['id']) ? $_GET['id'] : die('Project ID tidak ditemukan!');
$project->id = $id;

// Read project details
if(!$project->readOne()) {
    header("Location: projects.php");
    exit();
}

$success = '';
$error = '';

if(isset($_POST['edit_project'])) {
    // Set project properties
    $project->title = $_POST['title'];
    $project->description = $_POST['description'];
    $project->category = $_POST['category'];
    $project->image_url = $_POST['image_url'];
    $project->project_url = $_POST['project_url'];
    $project->github_url = $_POST['github_url'];
    $project->technologies = $_POST['technologies'];
    $project->completion_date = $_POST['completion_date'];
    $project->featured = isset($_POST['featured']) ? 1 : 0;

    // Validate required fields
    if(empty($project->title) || empty($project->description) || empty($project->category) || empty($project->image_url) || empty($project->technologies) || empty($project->completion_date)) {
        $error = "Semua field wajib diisi!";
    } else {
        // Update project
        if($project->update()) {
            $success = "Project berhasil diupdate!";
        } else {
            $error = "Gagal mengupdate project!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project - Project Library</title>
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
                    <li><a href="projects.php"><i class="fas fa-project-diagram"></i> Semua Projects</a></li>
                    <li><a href="add-project.php"><i class="fas fa-plus-circle"></i> Tambah Project</a></li>
                    <li><a href="logout.php" onclick="return confirm('Yakin ingin logout?')"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <h1>Edit Project</h1>
                <div class="header-user">
                    <span>Halo, <?php echo $_SESSION['admin_username']; ?></span>
                    <i class="fas fa-user-circle"></i>
                </div>
            </header>

            <div class="form-container">
                <?php if($success): ?>
                    <div class="alert success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if($error): ?>
                    <div class="alert error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="project-form">
                    <div class="form-group">
                        <label for="title">Judul Project <span class="required">*</span></label>
                        <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($project->title); ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi <span class="required">*</span></label>
                        <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($project->description); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Kategori <span class="required">*</span></label>
                            <select id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Web Application" <?php echo ($project->category == 'Web Application') ? 'selected' : ''; ?>>Web Application</option>
                                <option value="Desktop Application" <?php echo ($project->category == 'Desktop Application') ? 'selected' : ''; ?>>Desktop Application</option>
                                <option value="Mobile Application" <?php echo ($project->category == 'Mobile Application') ? 'selected' : ''; ?>>Mobile Application</option>
                                <option value="API Development" <?php echo ($project->category == 'API Development') ? 'selected' : ''; ?>>API Development</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="completion_date">Tanggal Selesai <span class="required">*</span></label>
                            <input type="date" id="completion_date" name="completion_date" required value="<?php echo $project->completion_date; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image_url">URL Gambar <span class="required">*</span></label>
                        <input type="url" id="image_url" name="image_url" required value="<?php echo htmlspecialchars($project->image_url); ?>" placeholder="https://images.unsplash.com/...">
                        <small>Gunakan URL gambar dari Unsplash atau source lain</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="project_url">URL Project (Live Demo)</label>
                            <input type="url" id="project_url" name="project_url" value="<?php echo htmlspecialchars($project->project_url); ?>">
                        </div>

                        <div class="form-group">
                            <label for="github_url">URL GitHub</label>
                            <input type="url" id="github_url" name="github_url" value="<?php echo htmlspecialchars($project->github_url); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="technologies">Teknologi <span class="required">*</span></label>
                        <input type="text" id="technologies" name="technologies" required value="<?php echo htmlspecialchars($project->technologies); ?>" placeholder="PHP, MySQL, JavaScript, Bootstrap">
                        <small>Pisahkan dengan koma</small>
                    </div>

                    <div class="form-group checkbox">
                        <label>
                            <input type="checkbox" name="featured" <?php echo ($project->featured == 1) ? 'checked' : ''; ?>>
                            <span class="checkmark"></span>
                            Jadikan Featured Project
                        </label>
                    </div>

                    <!-- Image Preview -->
                    <div class="image-preview" id="imagePreview">
                        <h4>Preview Gambar:</h4>
                        <img src="<?php echo htmlspecialchars($project->image_url); ?>" alt="Preview" style="max-width: 300px; border-radius: 8px; margin-top: 10px;">
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="edit_project" class="btn-submit">
                            <i class="fas fa-save"></i>
                            Update Project
                        </button>
                        <a href="projects.php" class="btn-cancel">
                            <i class="fas fa-times"></i>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
    // Image preview update
    document.getElementById('image_url').addEventListener('input', function(e) {
        const preview = document.getElementById('imagePreview');
        const img = preview.querySelector('img');
        if(this.value) {
            img.src = this.value;
        }
    });
    </script>
</body>
</html>