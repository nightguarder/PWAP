<?php
// Include authentication check
require_once('../config/authCheck.php');
// Include db connection
require_once('../config/db.php');

// Get user information from the session
$userName = $_SESSION['name'];
$userId = $_SESSION['id'];

// Check if note ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: vault.php');
    exit;
}

$noteId = intval($_GET['id']);

// Database connection
$conn = getDbConnection();
if (!$conn) {
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Get note data
$stmt = $conn->prepare('SELECT note_id, title, content, created_at, updated_at FROM Notes WHERE note_id = ? AND user_id = ?');
$stmt->bind_param('ii', $noteId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    // Note not found or doesn't belong to user
    header('Location: vault.php');
    exit;
}

$conn->close();

// Format dates
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/github-markdown-css@5.1.0/github-markdown-dark.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://kit.fontawesome.com/fb46939310.js" crossorigin="anonymous"></script>
    <title><?php echo htmlspecialchars($row['title']); ?> - Notes Manager</title>
    <style>
        .markdown-body {
            box-sizing: border-box;
            min-width: 200px;
            max-width: 980px;
            margin: 0 auto;
            padding: 45px;
        }
        
        @media (max-width: 767px) {
            .markdown-body {
                padding: 15px;
            }
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
            <div class="col-md-8">
                <h1 class="text-white"><?php echo htmlspecialchars($row['title']); ?></h1>
            </div>
            <div class="col-md-4 text-end">
                <p class="text-light mb-0">Created: <?php echo formatDate($row['created_at']); ?></p>
                <p class="text-light">Updated: <?php echo formatDate($row['updated_at']); ?></p>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card bg-dark text-white border-primary">
                    <div class="card-header bg-primary bg-gradient d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Note Content</h4>
                        <div>
                            <a href="notes.php?id=<?php echo $row['note_id']; ?>" class="btn btn-sm btn-light">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="vault.php" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Notes
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="markdown-body">
                            <?php 
                            // Convert Markdown to HTML using Parsedown
                            require_once '../vendor/parsedown/Parsedown.php';
                            $Parsedown = new Parsedown();
                            echo $Parsedown->text($row['content']); 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
