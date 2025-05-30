<?php
/* Authenticate / Login */
require_once('db.php'); //DB connection file

function getConfigValue($key)
{
    if (get_cfg_var('/config/config.conf'))
        return get_cfg_var($key);
    
    return ini_get($key);
}

function redirect() {
    header("refresh:3; url=logout.php");
    print '<p>Redirecting you to the Login page... If not, click <a href="../index.html">here</a>.</p>';
}

session_start();

//Connect to database
$conn = getDbConnection();

//save post form values to variables
$username = $_POST['username'];
$pwd = $_POST['password'];

// isset() will check if the data exists.
if (!isset($_POST['username'], $_POST['password']))
    exit('Please fill both the username and password fields!');

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if($stmt = $conn->prepare('SELECT id, password FROM Accounts WHERE username = ?')) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    //store the result for comparison
    $stmt->store_result();
    
    //account exists, verify password
    if(mysqli_stmt_num_rows($stmt) > 0) {
        //pepper password
        $pepper = getConfigValue("pepper");
        $password_pepper = hash_hmac("sha256", $pwd, $pepper);
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        $password_hashed = $password;
        
        //password verification ENCRYPTED and peppered, bind results to name, id, redirect user to home
        if(password_verify($password_pepper, $password_hashed)) {
            session_regenerate_id(true); // user is logged in save as cookie
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $username;
            $_SESSION['id'] = $id;
            
            // Set session timeout for security (10 minutes)
            $_SESSION['destroy'] = time() + 600;
            
            header('Location:/lib/notes.php'); 
            exit();
        } else {
            print_r("Incorrect Username or Password!\n");
            header("refresh:3; url=logout.php");
            print '<p>Redirecting you to the Login page... If not, click <a href="../index.html">here</a>.</p>';
        }
    } else {
        print_r("Incorrect Username or Password!\n");
        header("refresh:3; url=logout.php");
        print '<p>Redirecting you to the Login page... If not, click <a href="../index.html">here</a>.</p>';
    }
	$stmt->close();
}

$conn->close();
?>
