<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Test Code for TMS</title>
</head>

<body>

    <h1>Test Code for TMS</h1>

    <div>
        <?php
        echo ('attempt 05: ');
        $dummyString = getenv('MYSQLCONNSTR_dummy-string');
        echo ($dummyString);
        ?>
    </div>


</body>

</html>