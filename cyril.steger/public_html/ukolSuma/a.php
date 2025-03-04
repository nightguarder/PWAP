<?php
$pocet = filter_input(INPUT_GET, "pocet");
$post = filter_input_array(INPUT_POST);
if (isset($post["cisla"]) && sizeof($post["cisla"]) > 0) {
    $suma = 0;
    foreach ($post["cisla"] AS $index => $cislo) {
        if (is_numeric($cislo)) {
            $suma += $cislo;
            ?><p>Na pozici <?= $index ?> bylo zadano cislo <?= $cislo ?></p>
            <?php
        }else{
            ?><p>Na pozici <?= $index ?> bylo zadano neco divneho. </p>
            <?php
        }
    }
    ?>
    <p>Soucet zadanych cisel je <?= $suma ?></p>
    <?php
} elseif ($pocet > 0) {
    ?>
    <form method="POST">
        <?php
        for ($i = 0; $i < $pocet; $i++) {
            ?>
            <input type="number" name="cisla[]" placeholder="cislo <?= $i ?>"><br>
            <?php
        }
        ?>
        <input type="submit">
    </form>
    <?php
} else {
    ?>
    <p>Kolik cisel budeme scitat?</p>
    <form method="GET">
        <input type="number" name="pocet" placeholder="zadej cislo vetsi nez 0" min="0" step="1">
        <input type="submit">
    </form>
    <?php
}
?>
<a href="?">znovu zadat pocet cisel</a>
<?php /*
<hr>
<h4>Obsah superglobalni promenne GET</h4>
<pre><?php
print_r($_GET);
?></pre>
<h4>Obsah superglobalni promenne POST</h4>
<pre><?php
    print_r($_POST);
?></pre>
<h4>Obsah superglobalni promenne REQUEST</h4>
<pre><?php
    print_r($_REQUEST);
?></pre>
<h4>Obsah superglobalni promenne SERVER</h4>
<pre><?php print_r($_SERVER); ?></pre>
 */
