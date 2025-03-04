<?php
$post = filter_input_array(INPUT_POST);
$validni = [];
$invalidni = [];
$suma = "empty";
if (isset($post["cisla"]) && sizeof($post["cisla"]) > 0) {
    $suma = 0;
    foreach ($post["cisla"] AS $index => $cislo) {
        if (is_numeric($cislo)) {
            $suma += $cislo;
            $validni[] = $index;
        }else{
            $invalidni[] = $index;
        }
    }
}
$result["validni"] = $validni;
$result["invalidni"] = $invalidni;
$result["suma"] = $suma;
header('Content-Type: application/json');
echo json_encode($result);
