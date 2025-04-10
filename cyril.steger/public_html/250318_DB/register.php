<?php
session_start();
include "connect.php";
if(!isset($_POST["nick"])){
    ?>
<form action="register.php" method="POST">
    <h3>Register</h3>
    <p>
        <label for="nick">Nick:</label>
        <input type="text" name="nick" id="nick" placeholder="nick" required>
    </p>
    <p>
        <label for="heslo">Heslo:</label>
        <input type="password" name="heslo" placeholder="heslo" required><br>
    </p>
    <input type="submit">
</form>

        <?php
}else{
    $nick = filter_input(INPUT_POST, "nick", FILTER_SANITIZE_STRING);
    $heslo = filter_input(INPUT_POST, "heslo", FILTER_SANITIZE_STRING);
    
    // Check if user exists using prepared statement
    $check_stmt = $db->prepare("SELECT * FROM `uzivatel` WHERE `nick` = ?");
    $check_stmt->bind_param("s", $nick);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    // > 0 since any matching record means user exists
    if($result->num_rows > 0){ 
        echo "<p>Uzivatel toho jmena jiz existuje, zkus jiny nick, prosim. </p>";
    } else {
        // Insert new user using prepared statement
        $insert_stmt = $db->prepare("INSERT INTO `uzivatel`(`nick`, `heslo`) VALUES (?, PASSWORD(?))");
        $insert_stmt->bind_param("ss", $nick, $heslo);
        $insert_stmt->execute();
        
        $_SESSION["user_id"] = $db->insert_id;
        header("Location: index.php");
    }
    
    // Close all statements
    if(isset($check_stmt)) $check_stmt->close();
    if(isset($insert_stmt)) $insert_stmt->close();
    

    header("Location: login.php"); // Redirect to login page
    exit();
}