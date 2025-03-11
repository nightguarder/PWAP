# ukolSuma
## Server Side Rendering
- Ke klientovi jde jen HTML (CSS, JavaScript)
    - rychlé, jednoduché
    - stačí jeden jazyk
    - opakovaný request je vystaven rychleji (cache)
- Má jistá omezení
    - Request/Response je vždy synchronní
    - omezení na GET a POST (odkaz, form)
**Příklad:**
- [a.php](a.php)
```php

```

## Web service
- Server poskytuje pouze data, nejčastěji ve formátu JSON nebo XML
Zpracování a zobrazení zajišťuje klientský kód
Umožňuje asynchronní komunikaci
Vhodné pro API a backend služby

### Příklad
- [b.php](b.php)
```php
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
/* frontend rendering */
$result["validni"] = $validni;
$result["invalidni"] = $invalidni;
$result["suma"] = $suma;
header('Content-Type: application/json');
echo json_encode($result);
```
## Single Page Aplikace
- Načte se jedna HTML stránka, která se následně už neobnovuje
- Komunikace se serverem probíhá asynchronně pomocí *AJAX* nebo *Fetch API*
- Stránka se dynamicky aktualizuje bez nutnosti znovu načítat celou stránku
- Poskytuje plynulejší uživatelský zážitek
### Příklad
-[c.php](c.php)
```php
$(document).on("click","#scitej",function(){
    event.preventDefault();
    var url = "b.php";
    var request = new XMLHttpRequest();
    var formData = new FormData(document.getElementById('form'));
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            $("#suma").html(myArr.suma);
            $("#validni").html(myArr.validni);
            $("#invalidni").html(myArr.invalidni);
        }
    }
    request.open('POST', url, /* async = */ true);
    request.send(formData);
});
```