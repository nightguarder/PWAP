<?php
// Include authentication check
require_once('../config/authCheck.php');

// Get user information from the session
$userName = $_SESSION['name'];
$userId = $_SESSION['id'];

// Initialize variables
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$notes = [];

// Database connection
$DATABASE_HOST = 'sql107.epizy.com';
$DATABASE_USER = 'epiz_31121495';
$DATABASE_PASS = 'zV5I0lWGioAExLi';
$DATABASE_NAME = 'epiz_31121495_PwdManager';

$conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (!$conn) {
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Handle note deletion
if (isset($_POST['delete_note']) && isset($_POST['note_id'])) {
    $noteIdToDelete = intval($_POST['note_id']);
    $stmt = $conn->prepare('DELETE FROM Notes WHERE note_id = ? AND user_id = ?');
    $stmt->bind_param('ii', $noteIdToDelete, $userId);
    $stmt->execute();
    
    // Redirect to prevent form resubmission
    header('Location: vault.php');
    exit;
}

// Build query for notes based on search filter
$query = 'SELECT note_id, title, content, created_at, updated_at FROM Notes WHERE user_id = ?';
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

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    // Prepare a snippet of the content (first 150 characters)
    $row['snippet'] = substr(strip_tags($row['content']), 0, 150) . (strlen($row['content']) > 150 ? '...' : '');
    $notes[] = $row;
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
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="text-white">Your Notes</h1>
                <p class="text-light">Welcome to your secure notes vault, <?php echo htmlspecialchars($userName); ?>.</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="notes.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create New Note
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
                            <p class="text-muted mb-0">Create your first note to get started!</p>
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
                                        <button type="submit" name="delete_note" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this note?');">
                                            <i class="fas fa-trash"></i>
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
</body>
</html>
