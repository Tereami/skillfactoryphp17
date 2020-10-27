<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getFullnameFromParts($surname, $name, $fathername)
{
    $fullname = "$surname $name $fathername";
    return $fullname;
}

function getPartsFromFullname($fullname)
{
    $arr = explode(' ', $fullname);
    $arrName = ['surname' => $arr[0], 'name' => $arr[1], 'fathername' => $arr[2]];
    return $arrName;
}

function getShortName($fullname)
{
    $arrName = getPartsFromFullname($fullname);
    $name = $arrName['name'];
    $surname = $arrName['surname'];
    $shortSurname = mb_substr($surname, 0, 1);
    $resultName = "$name $shortSurname.";
    return $resultName;
}

function isEndsWith($line, $symbolsToCheck)
{
    $lineLength = mb_strlen ($line);
    $symbolsCount = mb_strlen ($symbolsToCheck);
    $lastSymbols = mb_substr($line, $lineLength - $symbolsCount, $symbolsCount);
    if($lastSymbols == $symbolsToCheck)
    {
        return true;
    }
    return false;
}

function getGenderFromName($fullname)
{
    $arrName = getPartsFromFullname($fullname);
    //print_r($arrName);
    $surname = $arrName['surname'];
    $name = $arrName['name'];
    $fathername = $arrName['fathername'];

    $femaleRate = 0;
    $maleRate = 0;
    
    if(isEndsWith($fathername, 'вна'))
    {
        $femaleRate++;
    }
    if(isEndsWith($name, 'а'))
    {
        $femaleRate++;
    }
    if(isEndsWith($surname, 'ва'))
    {
        $femaleRate++;
    }
    if(isEndsWith($fathername, 'ич'))
    {
        $maleRate++;
    }
    if(isEndsWith($name, 'й') || isEndsWith($name, 'н'))
    {
        $maleRate++;
    }
    if(isEndsWith($surname, 'в'))
    {
        $maleRate++;
    }
    $result = $maleRate <=> $femaleRate;
    return $result;
}

function getGenderDescription($persons_array)
{
    $count = count($persons_array);
    $males = 0;
    $females = 0;
    $unknown = 0;
    
    foreach($persons_array as $key => $user)
    {
        $fullname = $user['fullname'];
        $gender = getGenderFromName($fullname);
        if($gender == 1)
        {
            $males++;
        }
        else if($gender == -1)
        {
            $females++;
        }
        else
        {
            $unknown++;
        }
    }
    $malesPercent = round(($males / $count) * 100, 1);
    $femalesPercent = round(($females / $count) * 100, 1);
    $unknownPercent = 100 - $malesPercent - $femalesPercent;
    echo <<<HEREDOCLETTER
Гендерный состав аудитории:
---------------------------
Мужчины - {$malesPercent}%
Женщины - {$femalesPercent}%
Не удалось определить - {$unknownPercent}%
HEREDOCLETTER;
}



function getPerfectPartner($surname, $name, $fathername, $array)
{
    $surname2 = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
    $name2 = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
    $fathername2 = mb_convert_case($fathername, MB_CASE_TITLE_SIMPLE);

    $fullname = getFullnameFromParts($surname2, $name2, $fathername2);
    $curGender = getGenderFromName($fullname);
    $attempts = 0;
    $partner = -1;

    while ($attempts < 10) {
        $attempts++;
        
        $randIndex = rand(0, count($array) - 1);
        $randUser = $array[$randIndex];
        $randUserName = $randUser['fullname'];
        $partnerGender = getGenderFromName($randUserName);
        if($partnerGender + $curGender == 0)
        {
            $partner = $randUser;
            break;
        }
    }
    if($partner == -1)
    {
        echo 'Не удалось подобрать партнера';
        return;
    }

    $shortName1 = getShortName($fullname);
    $shortName2 = getShortName($partner['fullname']);
    $percent = (rand(5000, 10000)) / 100;
    echo <<<HEREDOCLETTER
{$shortName1} + {$shortName2} = 
♡ Идеально на {$percent}% ♡
HEREDOCLETTER;

    
}

getGenderDescription($example_persons_array);
echo "\n\n";
getPerfectPartner("Махно", "Никита", "Сергеевич", $example_persons_array);