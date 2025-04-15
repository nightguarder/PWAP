<?php
// Include authentication check
require_once('../config/authCheck.php');
// Include database connection
require_once('../config/db.php');
// Include timeout check
require_once('../config/timeout.php');

// Get user information from the session
$userName = $_SESSION['name'];
$userId = $_SESSION['id'];

// Initialize variables
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$notes = [];
$message = ''; // For status messages
$status = 'active'; // Default status for notes


$conn = getDbConnection(); // Fixed function name (was getDBConnection)
if (!$conn) {
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Properly use the sessionTimeout function
sessionTimeout(1200); //< 20 minutes

// Check if the notes table has the status column
$tableCheck = $conn->query("SHOW COLUMNS FROM Notes LIKE 'status'");
if ($tableCheck->num_rows === 0) {
    // Add status column if it doesn't exist
    $conn->query("ALTER TABLE Notes ADD COLUMN status VARCHAR(20) DEFAULT 'active'");
}

// Handle note archiving (soft delete)
if (isset($_POST['archive_note']) && isset($_POST['note_id'])) {
    $noteId = intval($_POST['note_id']);
    $stmt = $conn->prepare('UPDATE Notes SET status = ? WHERE note_id = ? AND user_id = ?');
    $status = 'archived';
    $stmt->bind_param('sii', $status, $noteId, $userId);
    
    if ($stmt->execute()) {
        $message = "Note archived successfully.";
    } else {
        $message = "Error archiving note.";
    }
}

// Handle note restoration
if (isset($_POST['restore_note']) && isset($_POST['note_id'])) {
    $noteId = intval($_POST['note_id']);
    $stmt = $conn->prepare('UPDATE Notes SET status = ? WHERE note_id = ? AND user_id = ?');
    $status = 'active';
    // Check if the note is already active
    $stmt->bind_param('sii', $status, $noteId, $userId);
    
    if ($stmt->execute()) {
        $message = "Note restored successfully.";
    } else {
        $message = "Error restoring note.";
    }
}

// Build query for notes based on search filter and status active
$query = 'SELECT note_id, title, content, created_at, updated_at, status FROM Notes WHERE user_id = ? AND status = "active"';

// Check for if there are any records with NULL status
$checkNullQuery = $conn->query('SELECT COUNT(*) AS count FROM Notes WHERE status IS NULL');
$nullCount = $checkNullQuery->fetch_assoc()['count'];

if ($nullCount > 0) {
    // If there are records with NULL status, include them in the query
    $query = 'SELECT note_id, title, content, created_at, updated_at, status FROM Notes WHERE user_id = ? AND (status = "active" OR status IS NULL)';
}

$params = [$userId];
$types = 'i';

if (!empty($searchQuery)) {
    $query .= ' AND (title LIKE ? OR content LIKE ?)';
    $searchTerm = '%' . $searchQuery . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'ss';
}

$query .= ' ORDER BY updated_at DESC';

// Execute the query with parameters
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    // Check if the note is already active
    if ($row['status'] === 'active') {
        // Prepare a snippet of the content (first 150 characters)
        $row['snippet'] = substr(strip_tags($row['content']), 0, 150) . (strlen($row['content']) > 150 ? '...' : '');
        $notes[] = $row;
    }
}

$conn->close();

// Helper function to format date
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('M j, Y g:i A');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://kit.fontawesome.com/fb46939310.js" crossorigin="anonymous"></script>
    <title>My Notes - Notes Manager</title>
    <style>
        .card-note:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="bg-dark">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="/index.html">
                    <i class="fas fa-sticky-note fa-lg" class="d-inline-block align-top" alt="logo"></i>
                    Notes Manager
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/index.html">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/lib/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/lib/notes.php">Notes</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="/lib/vault.php">Vault <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item"> 
                            <a href="/lib/archive.php" class="nav-link">Archive</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($userName); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="/lib/notes.php">New Note</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/config/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <div class="container mt-5 pt-5">
        <?php if (!empty($message)): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="text-white">Your Notes</h1>
                <p class="text-light">Welcome to your secure notes vault, <?php echo htmlspecialchars($userName); ?>.</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="notes.php" class="btn btn-primary me-2">
                    <i class="fas fa-plus"></i> Create New Note
                </a>
                <a href="archive.php" class="btn btn-secondary">
                    <i class="fas fa-archive"></i> View Archived Notes
                </a>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card bg-dark text-white border-primary">
                    <div class="card-body">
                        <form method="get" action="vault.php" class="row g-3">
                            <div class="col-md-10">
                                <input type="text" class="form-control bg-dark text-white" id="search" name="search" placeholder="Search notes..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if (empty($notes)): ?>
                <div class="col-12">
                    <div class="card bg-dark text-white border-secondary">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-folder-open fa-4x mb-3 text-secondary"></i>
                            <h3>No Notes Found</h3>
                            <p class="text-muted mb-0">
                                Create your first note to get started!
                            </p>
                            <a href="notes.php" class="btn btn-primary mt-3">Create Note</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($notes as $note): ?>
                    <div class="col">
                        <div class="card h-100 bg-dark text-white border-primary card-note">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($note['title']); ?></h5>
                                <p class="card-text text-muted small">
                                    Updated: <?php echo formatDate($note['updated_at']); ?>
                                </p>
                                <p class="card-text"><?php echo htmlspecialchars($note['snippet']); ?></p>
                            </div>
                            <div class="card-footer bg-transparent border-top border-primary d-flex justify-content-between">
                                <a href="view.php?id=<?php echo $note['note_id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <div>
                                    <a href="notes.php?id=<?php echo $note['note_id']; ?>" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="post" action="vault.php" class="d-inline">
                                        <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                                        <button type="submit" name="archive_note" class="btn btn-sm btn-outline-warning" title="Archive Note"
                                            onclick="return confirm('Are you sure you want to archive this note?');">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>
