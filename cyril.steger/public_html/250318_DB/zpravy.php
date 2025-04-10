<?php
session_start(); //TADY
////ALTER TABLE `zpravy` ADD `id_from` INT NOT NULL AFTER `id`;

include "connect.php";

if (isset($_POST["akce"])) {
    $query = "INSERT INTO `zpravy` (`id_from`,`id_uzivatel`,`zprava`) "
            . "VALUES ('" . $_SESSION["user_id"] . "','" . filter_input(INPUT_POST, "id_uzivatel") . "','" . filter_input(INPUT_POST, "zprava") . "');";
    $result = $db->query($query) or die($query);
    echo "Zprava ulozena";
}

if (!isset($_GET["id_uzivatel"])) {


    $query2 = "SELECT * FROM `zpravy`,`uzivatel` "
            . "WHERE `zpravy`.`id_uzivatel` = '" . $_SESSION["user_id"] . "' "
            . "AND `zpravy`.`id_from` = `uzivatel`.`id`";
    $result2 = $db->query($query2) or die($query2);
    ?>
    <table>
        <thead>
            <tr>
                <th>od koho</th><th>obsah</th>
            </tr>
        </thead>
        <?php
        while ($row = $result2->fetch_assoc()) {
            ?><tr>
                <td><?= $row["nick"] ?></td><td><?= $row["zprava"] ?></td>
            </tr><?php
        }
        ?></table>


    <hr><!-- comment -->
    <?php
    $selected = "";
}else{
    $selected = "selected=\"true\"";
            
}
?>
<h3>Napis zpravu</h3>
<form method="POST">
    <label for="id_uzivatel">komu</label>

    <select name="id_uzivatel" id="id_uzivatel">
        <option value="0" disabled="" <?=$selected?>>vyber</option>
        <?php
        $query0 = "SELECT * FROM `uzivatel` WHERE `id` != '" . $_SESSION["user_id"] . "' ORDER BY `nick`";
        $result0 = $db->query($query0) or die($query0);
        while ($row = $result0->fetch_assoc()) {
            $selected = "";
            if(isset($_GET["id_uzivatel"]) && $_GET["id_uzivatel"] == $row["id"]){
                $selected = "selected=\"true\"";
            }
            ?>
            <option value="<?= $row["id"] ?>" <?=$selected?>><?= $row["nick"] ?></option>
            <?php
        }
        ?></select>

    <input type="text" name="zprava" placeholder="kratka zprava" size="50"><!-- comment -->
    <input type="submit" name="akce" value="odeslat">
</form>
<?php
include "menu.php";

