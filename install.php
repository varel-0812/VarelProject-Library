<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install Project Library</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .install-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .install-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .install-header i {
            font-size: 64px;
            color: #6c5ce7;
            margin-bottom: 20px;
        }
        .install-header h1 {
            color: #2d3436;
            margin-bottom: 10px;
        }
        .install-header p {
            color: #636e72;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2d3436;
            font-weight: 600;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #dfe6e9;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .form-group input:focus {
            outline: none;
            border-color: #6c5ce7;
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1);
        }
        .btn-install {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        .btn-install:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(108, 92, 231, 0.3);
        }
        .info-box {
            background: #f0f9ff;
            border-left: 4px solid #3498db;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .info-box i {
            color: #3498db;
            margin-right: 10px;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn {
            flex: 1;
            padding: 12px;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-success {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            color: white;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="install-container">
        <div class="install-header">
            <i class="fas fa-crown"></i>
            <h1>Project Library Installer</h1>
            <p>Setup database dan konfigurasi awal website</p>
        </div>

        <?php
        if(isset($_POST['install'])) {
            try {
                // Koneksi ke MySQL
                $host = $_POST['host'];
                $user = $_POST['username'];
                $pass = $_POST['password'];
                $dbname = $_POST['dbname'];

                $pdo = new PDO("mysql:host=$host", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Buat database
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
                $pdo->exec("USE `$dbname`");

                // Buat tabel projects
                $sql = "CREATE TABLE IF NOT EXISTS projects (
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
                )";
                $pdo->exec($sql);

                // Cek apakah tabel kosong
                $check = $pdo->query("SELECT COUNT(*) FROM projects");
                if($check->fetchColumn() == 0) {
                    // Insert sample data
                    $sample_sql = "INSERT INTO projects (title, description, category, image_url, technologies, completion_date, featured) VALUES
                        ('E-Commerce System', 'Full-stack e-commerce platform dengan PHP Native dan MySQL. Fitur termasuk manajemen produk, keranjang belanja, dan sistem pembayaran.', 'Web Application', 'https://images.unsplash.com/photo-1557821552-17105176677c?w=500', 'PHP, MySQL, JavaScript, Bootstrap', '2024-01-15', 1),
                        ('Inventory Management', 'Sistem manajemen inventory untuk gudang dengan fitur tracking stok real-time dan laporan otomatis.', 'Desktop Application', 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=500', 'PHP, MySQL, jQuery, Bootstrap', '2024-02-20', 1),
                        ('POS System', 'Point of Sale system dengan laporan keuangan, manajemen karyawan, dan database terintegrasi.', 'Web Application', 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=500', 'PHP, MySQL, Chart.js, Bootstrap', '2024-03-10', 0),
                        ('Hospital Management', 'Sistem manajemen rumah sakit lengkap dengan fitur pendaftaran pasien, rekam medis, dan penjadwalan dokter.', 'Web Application', 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=500', 'PHP, MySQL, AJAX, Bootstrap', '2024-01-05', 1),
                        ('School Library System', 'Sistem perpustakaan sekolah dengan fitur peminjaman, pengembalian, dan katalog buku online.', 'Web Application', 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=500', 'PHP, MySQL, Tailwind CSS', '2024-02-28', 0),
                        ('Hotel Booking', 'Sistem booking hotel online dengan fitur reservasi kamar, pembayaran, dan manajemen tamu.', 'Web Application', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=500', 'PHP, MySQL, JavaScript, Bootstrap', '2024-03-15', 1)";
                    
                    $pdo->exec($sample_sql);
                }

                // Buat file konfigurasi database
                $config_content = "<?php
class Database {
    private \$host = \"$host\";
    private \$db_name = \"$dbname\";
    private \$username = \"$user\";
    private \$password = \"$pass\";
    public \$conn;

    public function getConnection() {
        \$this->conn = null;
        try {
            \$this->conn = new PDO(
                \"mysql:host=\" . \$this->host . \";dbname=\" . \$this->db_name,
                \$this->username,
                \$this->password
            );
            \$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            \$this->conn->exec(\"set names utf8\");
        } catch(PDOException \$exception) {
            echo \"Connection error: \" . \$exception->getMessage();
        }
        return \$this->conn;
    }
}
?>";

                // Pastikan folder config ada
                if (!is_dir('config')) {
                    mkdir('config', 0777, true);
                }
                file_put_contents('config/database.php', $config_content);

                echo '<div class="success-message">';
                echo '<i class="fas fa-check-circle"></i> ';
                echo '<strong>Installasi Berhasil!</strong><br>';
                echo 'Database dan tabel telah dibuat.';
                echo '</div>';
                
                echo '<div class="info-box">';
                echo '<i class="fas fa-info-circle"></i> ';
                echo '<strong>Informasi Login Admin:</strong><br>';
                echo 'Username: <code>admin</code><br>';
                echo 'Password: <code>admin123</code>';
                echo '</div>';
                
                echo '<div class="btn-group">';
                echo '<a href="index.php" class="btn btn-primary"><i class="fas fa-globe"></i> Buka Website</a>';
                echo '<a href="admin/" class="btn btn-success"><i class="fas fa-crown"></i> Buka Admin Panel</a>';
                echo '</div>';
                
            } catch(PDOException $e) {
                echo '<div class="error-message">';
                echo '<i class="fas fa-exclamation-triangle"></i> ';
                echo '<strong>Error:</strong> ' . $e->getMessage();
                echo '</div>';
            }
        } else {
        ?>

        <form method="POST">
            <div class="form-group">
                <label><i class="fas fa-server"></i> Host Database</label>
                <input type="text" name="host" value="localhost" required placeholder="Contoh: localhost">
            </div>

            <div class="form-group">
                <label><i class="fas fa-user"></i> Username Database</label>
                <input type="text" name="username" value="root" required placeholder="Username MySQL">
            </div>

            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password Database</label>
                <input type="password" name="password" value="" placeholder="Kosongkan jika tidak ada password">
            </div>

            <div class="form-group">
                <label><i class="fas fa-database"></i> Nama Database</label>
                <input type="text" name="dbname" value="project_library" required placeholder="Nama database yang akan dibuat">
            </div>

            <div class="info-box">
                <i class="fas fa-lightbulb"></i>
                <strong>Info:</strong> Untuk XAMPP/Laragon default:<br>
                - Host: localhost<br>
                - Username: root<br>
                - Password: (kosongkan)
            </div>

            <button type="submit" name="install" class="btn-install">
                <i class="fas fa-cogs"></i> Install Sekarang
            </button>
        </form>

        <?php } ?>
    </div>
</body>
</html>