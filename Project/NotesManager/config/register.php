<?php
/* Register */
function get_configValue($key)
{
    if (get_cfg_var('/config/config.conf'))
        return get_cfg_var($key);
	
    return ini_get($key);
}
function redirect() {
	header("refresh:3; url=logout.php");
    print '<p>Redirecting you to the Register page... If not, click <a href="../lib/register.html">here</a>.</p>';
}

session_start();
// Setup database connection
$DATABASE_HOST = 'sql107.epizy.com';
$DATABASE_USER = 'epiz_31121495';
$DATABASE_PASS = 'zV5I0lWGioAExLi';
$DATABASE_NAME = 'epiz_31121495_PwdManager';

$conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

//save post form values to variable
$username = $_POST['username'];
$password = $_POST['password'];
//email sanitize
$email = $_POST['email'];
$email_sanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
$pattern = '/^[a-zA-Z0-9]+$/';
//password match
$number = preg_match('@[0-9]@', $password);
$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$specialchar = preg_match('@[^\w]@', $password);

if (mysqli_connect_errno()) 
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());

// Check if all required fields exist
if (!isset($username, $password, $email)) {
	redirect();
	exit('Please complete the registration form!');
}

// Make sure the submitted registration values are not empty.
if (empty($username) || empty($password) || empty($email)) {
	redirect();
	exit('Please complete the registration form!');
}

//email validation
if (!filter_var($email_sanitized, FILTER_VALIDATE_EMAIL)) {
	redirect();
	exit('Email is not valid!');
}

//username validation
if (preg_match($pattern, $username) == 0) {
	redirect();
    exit('Username is not valid! Use only letters and numbers.');
}

//Password validation
if (strlen($password) < 8 || strlen($password) > 64 || !$number || !$uppercase || !$lowercase) {
	redirect();
	exit('Password must be between 8-64 characters and MUST contain at least ONE number, ONE uppercase letter, and ONE lowercase letter!');  
}

//Check if username exists
if($stmt = $conn->prepare('SELECT id, password FROM Accounts WHERE username = ?')) {
	$stmt->bind_param('s', $username);
	$stmt->execute();
	$stmt->store_result();

	if(mysqli_stmt_num_rows($stmt) > 0) {
		print '<p>Username already exists, please choose a different one!</p>';
		redirect();
	} else {
		if ($stmt = $conn->prepare('INSERT INTO Accounts (username, password, email) VALUES (?, ?, ?)')) {
			//hash and pepper password before inserting into database
			$pepper = get_configValue("pepper");
			$password_pepper = hash_hmac("sha256", $password, $pepper);
			//hashing with Bcrypt by PHP Default
			$password_hashed = password_hash($password_pepper, PASSWORD_DEFAULT);
			
			//Insert values INTO Accounts
			$stmt->bind_param('sss', $username, $password_hashed, $email);
			$stmt->execute();
			
			print '<p>You have successfully registered, you can now login!</p>';
			header("refresh:3; url=logout.php");
            print '<p>Redirecting you to the Login page... If not, click <a href="../lib/login.php">here</a>.</p>';
		} else {
			// Something is wrong with the sql statement
			echo 'Could not prepare statement!';
			redirect();
		}
	}
	//close prepare SQL query username exists
	$stmt->close();
} else {
	print '<p>Could not prepare statement!</p>';
	redirect();
}

//close the connection to database
$conn->close();
?>
