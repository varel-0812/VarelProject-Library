<?php
class Project {
    private $conn;
    private $table_name = "projects";

    public $id;
    public $title;
    public $description;
    public $category;
    public $image_url;
    public $project_url;
    public $github_url;
    public $technologies;
    public $completion_date;
    public $featured;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all projects
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single project
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->category = $row['category'];
            $this->image_url = $row['image_url'];
            $this->project_url = $row['project_url'];
            $this->github_url = $row['github_url'];
            $this->technologies = $row['technologies'];
            $this->completion_date = $row['completion_date'];
            $this->featured = $row['featured'];
            return true;
        }
        return false;
    }

    // Create project
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    title = :title,
                    description = :description,
                    category = :category,
                    image_url = :image_url,
                    project_url = :project_url,
                    github_url = :github_url,
                    technologies = :technologies,
                    completion_date = :completion_date,
                    featured = :featured";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->project_url = htmlspecialchars(strip_tags($this->project_url));
        $this->github_url = htmlspecialchars(strip_tags($this->github_url));
        $this->technologies = htmlspecialchars(strip_tags($this->technologies));
        $this->completion_date = htmlspecialchars(strip_tags($this->completion_date));
        $this->featured = isset($this->featured) ? 1 : 0;

        // Bind parameters
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":project_url", $this->project_url);
        $stmt->bindParam(":github_url", $this->github_url);
        $stmt->bindParam(":technologies", $this->technologies);
        $stmt->bindParam(":completion_date", $this->completion_date);
        $stmt->bindParam(":featured", $this->featured);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update project
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    title = :title,
                    description = :description,
                    category = :category,
                    image_url = :image_url,
                    project_url = :project_url,
                    github_url = :github_url,
                    technologies = :technologies,
                    completion_date = :completion_date,
                    featured = :featured
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->project_url = htmlspecialchars(strip_tags($this->project_url));
        $this->github_url = htmlspecialchars(strip_tags($this->github_url));
        $this->technologies = htmlspecialchars(strip_tags($this->technologies));
        $this->completion_date = htmlspecialchars(strip_tags($this->completion_date));
        $this->featured = isset($this->featured) ? 1 : 0;
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameters
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":project_url", $this->project_url);
        $stmt->bindParam(":github_url", $this->github_url);
        $stmt->bindParam(":technologies", $this->technologies);
        $stmt->bindParam(":completion_date", $this->completion_date);
        $stmt->bindParam(":featured", $this->featured);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete project
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get featured projects
    public function getFeatured() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE featured = 1 ORDER BY created_at DESC LIMIT 3";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get projects by category
    public function getByCategory($category) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE category = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category);
        $stmt->execute();
        return $stmt;
    }

    // Search projects
    public function search($keyword) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE title LIKE ? OR description LIKE ? OR technologies LIKE ? 
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(1, $keyword);
        $stmt->bindParam(2, $keyword);
        $stmt->bindParam(3, $keyword);
        $stmt->execute();
        return $stmt;
    }

    // Get total count
    public function getCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Get featured count
    public function getFeaturedCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE featured = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
?>