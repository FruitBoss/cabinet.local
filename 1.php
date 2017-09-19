<!doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        <script src="js/jquery-3.2.1.min.js"></script>
    </head>
    <body>
        <?php
        $array = array(
            'name' => "Математическая олимпиада",
            'file' => "C:\DIR\FILES"
        );

        $json = json_encode($array);
        echo $json;
        ?>
    </body>
</html>