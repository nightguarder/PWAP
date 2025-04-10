<?php
session_start();//TADY
include "connect.php"; //DB connection
$poctyFrom = [];
$poctyTo = [];
$query = "SELECT * FROM `zpravy` WHERE id != 0";
$result = $db->query($query);
while($row = $result->fetch_assoc()){
    if(!isset($poctyFrom[$row["id_from"]])){
        $poctyFrom[$row["id_from"]] = 0;
    }
    if(!isset($poctyTo[$row["id_uzivatel"]])){
        $poctyTo[$row["id_uzivatel"]] = 0;
    }
    $poctyTo[$row["id_uzivatel"]]++;
    $poctyFrom[$row["id_from"]]++;
}
//print_r($poctyTo);


$sql_dotaz = "SELECT * FROM `uzivatel` WHERE 1 LIMIT 0,100"; //string
$result = mysqli_query($db, $sql_dotaz);
//print_r($result);
echo "<p>DB obsahuje ".$result->num_rows." zaznamu. </p>";
?>
<table border="1">
    <caption>Zpravy od uzivatelu: </caption>
    <thead>
        <tr>
            <th>id</th><th>nick</th><th>prichozi</th><th>odchozi</th><th>moznosti</th>
        </tr>
    </thead>
    <tbody>
       <?php
       while($row = $result->fetch_assoc()){
           ?>
        <tr>
            <td><?=$row["id"]?></td><td><?=$row["nick"]?></td>
            <td><?php
            if(isset($poctyTo[$row["id"]])){
                echo $poctyTo[$row["id"]];
            }
            ?></td>
            <td><?php
            if(isset($poctyFrom[$row["id"]])){
                echo $poctyFrom[$row["id"]];
            }
            ?></td>
            <td><?php //TADY
            if(isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $row["id"]){
                
                echo "<b><a href='zpravy.php'>precist zpravy pro mne</a></b>";
            }elseif(isset($_SESSION["user_id"])){
                echo "<b><a href='zpravy.php?id_uzivatel=".$row["id"]."'>napsat zpravu pro ".$row["nick"]."</a></b>";
            }
            ?></td>
        </tr>
               <?php
       }
       ?> 
    </tbody>
</table>
<?php
include "menu.php";