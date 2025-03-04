<html>
    <head>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(document).on("change", "#pocet", function () {
                    var pocet = $(this).val();
                    var text = "";
                    console.log("pocet cisel je " + pocet);
                    for (let i = 0; i < pocet; i++) {
                        text += "<input type='number' name='cisla[]' placeholder='cislo " + i + "'><br>";
                    }
                    $("#dynamic").html(text);
                });
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
            });

        </script>
    </head>
    <body>
        <h3>Kolik čísel budeme sčítat?</h3>
        <input type="number" name="pocet" min="0" step="0" value="0" id="pocet">
        <h3>Cisla</h3>
        <form method="POST" id="form">
            <div id="dynamic">
            </div>
            <input type="submit" id="scitej">
        </form>
        <table id="vysledek">
            <tr>
                <td>validni pozice</td><td id="validni"></td>
            </tr><tr>
                <td>invalidni pozice</td><td id="invalidni"></td>
            </tr>
            <tr>
                <td>suma</td><td id="suma"></td>
            </tr>
        </table>
    </body>
</html>
