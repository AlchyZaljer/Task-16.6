<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP</title>
</head>

<body>
    <?php include 'array.inc.php'; ?>
    <?php include 'operations.inc.php'; ?>
    <?php
        for ($i = 0; $i < count($persons_array); $i++) {
            $fullname_str = $persons_array[$i]['fullname'];
            $divided_fullnames[$i] = getPartsFromFullname($fullname_str);

            $surname_str = $divided_fullnames[$i]['surname'];
            $name_str = $divided_fullnames[$i]['name'];
            $patronymic_str = $divided_fullnames[$i]['patronymic'];
            $connected_fullnames[$i] = getFullnameFromParts($surname_str, $name_str, $patronymic_str);

            $short_names[$i] = getShortName($connected_fullnames[$i]);
            $gender[$i] = getGenderFromName($connected_fullnames[$i]);
        }

        print_r($persons_array);
        echo "<br><br>";
        print_r($divided_fullnames);
        echo "<br><br>";
        print_r($connected_fullnames);
        echo "<br><br>";
        print_r($short_names);
        echo "<br><br>";
        print_r($gender);
        echo "<br><br>";
        echo getGenderDescription($connected_fullnames);
        echo "<br><br>";

        echo getPerfectPartner($divided_fullnames[0]['surname'], $divided_fullnames[0]['name'], $divided_fullnames[0]['patronymic'], $connected_fullnames);
    ?>
    
</body>

</html>