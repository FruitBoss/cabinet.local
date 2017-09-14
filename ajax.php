<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jquery post</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
</head>
    <body>

    <input type="text" id="a">
    <input type="text" id="b">
    <button id="submit">Отправить!</button>
    <div id="block"></div>
    <script>
        $(document).ready(function(){
            $("#submit").click(function(){
                var fnumb = $("#a").val();
                var snumb = $("#b").val();
                $.post('ajax.php', {a:fnumb,b:snumb}, function(data){
                    $("#block").text(data);
                });
            });
        });
    </script>
    <?php
    $a = $_POST['a'];
    $b = $_POST['b'];
    echo $a+$b;
    ?>
    </body>
</html>