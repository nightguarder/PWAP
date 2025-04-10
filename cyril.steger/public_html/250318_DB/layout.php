<ul><li>
        <a href="index.php">home</a>
    </li>
    <li>
        <a href="uzivatele.php">uzivatele</a>
    </li>
    <?php
    if(isset($_SESSION["user_id"])){
        ?>
    
    
    <li>
        <a href="zpravy.php">zpravy</a>
    </li>
    <li>
        <a href="logout.php">logout</a>
    </li>
            <?php
    }else{
        ?>
    <li>
        <a href="login.php">login</a>
    </li>
            <?php
    }
        
    ?>
</ul>