<?php
session_start();
include "connect.php";
if(!isset($_POST["nick"])){
?>
<form action="login.php" method="POST">
    <input type="text" name="nick" placeholder="nick" required><br>
    <input type="password" name="heslo" placeholder="heslo" required><br>
    <input type="submit" value="Přihlásit">
</form>

<p>Uživatelé pepa a karel mají heslo veslo. </p>

<?php
} else {
    // Properly sanitize inputs
    $nick = filter_input(INPUT_POST, "nick", FILTER_SANITIZE_STRING);
    $heslo = filter_input(INPUT_POST, "heslo", FILTER_SANITIZE_STRING);
    
    // Use prepared statement to prevent SQL injection
    $stmt = $db->prepare("SELECT * FROM `uzivatel` WHERE `nick` = ? AND `heslo` = PASSWORD(?)");
    $stmt->bind_param("ss", $nick, $heslo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 1) { // existuje prave 1 zaznam
        $row = $result->fetch_assoc();
        $_SESSION["user_id"] = $row["id"]; // session nastavuju tak, jak se uzivatel predstavil
        
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        header("Location: index.php");
        exit(); // Prevent further code execution after redirect
    } else {
        echo "<p>Nesprávná kombinace jména a hesla.</p>";
        echo "<p>Pokud nemáš účet, <a href='register.php'>zaregistruj se</a>.</p>";
    }
    
    // Close the statement
    $stmt->close();
}
