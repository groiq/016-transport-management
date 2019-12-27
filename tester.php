<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Test Code for TMS</title>
</head>

<body>

    <?php

        $dummyString = getenv('MYSQLCONNSTR_dummy-string');

    ?>

    <h1>Test Code for TMS</h1>

    <div>
        <?php
        echo ('attempt 06: ');
        echo ($dummyString);
        ?>
    </div>

    <pre>

        <?php

            var_dump($dummyString);

        ?>

    </pre>


</body>

</html>