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

// Delete project
if($project->delete()) {
    $_SESSION['message'] = "Project berhasil dihapus!";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Gagal menghapus project!";
    $_SESSION['message_type'] = "error";
}

// Redirect back to projects page
header("Location: projects.php");
exit();
?>