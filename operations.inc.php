<?php
function getPartsFromFullname($str) {
    $array = [];

    $array['surname'] = strstr($str, ' ', true);
    $surname_len = mb_strlen($array['surname']);
    $str_wo_surname = mb_substr($str, $surname_len + 1);

    $array['name'] = strstr($str_wo_surname, ' ', true);
    $name_len = mb_strlen($array['name']);

    $array['patronymic'] = mb_substr($str, $name_len + $surname_len + 1);
    return $array;
}

function getFullnameFromParts($surname_str, $name_str, $patronymic_str) {
    $string = $surname_str . ' ' . $name_str . ' ' . $patronymic_str;
    return $string;
}

function getShortName($str) {
    $array = getPartsFromFullname($str);
    $surname_letter = mb_substr($array['surname'], 0, 1);
    $string = $array['name'] . ' ' . $surname_letter . '.';
    return $string;
}

function getGenderFromName($str) {
    $genderFlag = 0;
    $array = getPartsFromFullname($str);
    $name = $array['name'];
    $surname = $array['surname'];
    $patronymic = $array['patronymic'];

    if (mb_substr($name, -1) === 'а') {
        $genderFlag -= 1;
    } elseif (mb_substr($name, -1) === 'й' || mb_substr($name, -1) === 'н') {
        $genderFlag += 1;
    }

    if (mb_substr($surname, -2) === 'ва') {
        $genderFlag -= 1;
    } elseif (mb_substr($surname, -1) === 'в') {
        $genderFlag += 1;
    }

    if (mb_substr($patronymic, -3) === 'вна') {
        $genderFlag -= 1;
    } elseif (mb_substr($patronymic, -2) === 'ич') {
        $genderFlag += 1;
    }

    if ($genderFlag > 0) {
        return '1';
    } elseif ($genderFlag < 0){
        return '-1';
    } elseif ($genderFlag === 0){
        return '0';
    }
}

function getGenderDescription($array) {
    $men = 0;
    $women = 0;
    $undefined = 0;

    for ($i = 0; $i < count($array); $i++) {
        $gender[$i] = getGenderFromName($array[$i]);
        if ($gender[$i] === '1') {
            $men += 1;
        } elseif ($gender[$i] === '-1') {
            $women += 1;
        } elseif ($gender[$i] === '0') {
            $undefined += 1;
        }
    }

    $men_number = round($men/count($array)*100, 2);
    $women_number = round($women/count($array)*100, 2);
    $undefined_number = round($undefined/count($array)*100, 2);

    $description = <<<EOD
Гендерный состав аудитории: <br>
--------------------------- <br>
Мужчины - $men_number% <br>
Женщины - $women_number% <br>
Не удалось определить - $undefined_number%
EOD;
    return $description;
}

function getPerfectPartner($surname_str, $name_str, $patronymic_str, $persons_array) {
    $surname = mb_convert_case($surname_str, MB_CASE_TITLE, "UTF-8");
    $name = mb_convert_case($name_str, MB_CASE_TITLE, "UTF-8");
    $patronymic = mb_convert_case($patronymic_str, MB_CASE_TITLE, "UTF-8");

    $person_1 = getFullnameFromParts($surname, $name, $patronymic);
    $gender_1 = getGenderFromName($person_1);

    function partner_generator($persons_array, $gender_1) {
        $partner = $persons_array[rand(0, count($persons_array) - 1)];
        $gender_2 = getGenderFromName($partner);
        if ((($gender_1 === '-1') && ($gender_2 === '1')) || (($gender_1 === '1') && ($gender_2 === '-1'))) {
            return $partner;
        } else {
            return partner_generator($persons_array, $gender_1);
        }
    }

    $person_2 = partner_generator($persons_array, $gender_1);

    $short_person_1 = getShortName($person_1);
    $short_person_2 = getShortName($person_2);
    
    $random_int = mt_rand(50*100, 100*100)/100;

    $connection = <<<EOD
$short_person_1 + $short_person_2 = <br>
♡ Идеально на $random_int% ♡ <br>
EOD;

    return $connection;
}
?>