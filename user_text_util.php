<?php

use \ilein\Member;
include_once 'item.php';

if ($argc!=3) { 
    echo "Получено неверное количество аргументов\n";
    return;
};

// проверка аргументов
$arr_fnc = array("countAverageLineCount", "replaceDates");
$arr_separator = array("comma"=>",", "semicolon"=>";");

if (!in_array($argv[1], $arr_fnc)) {
    echo "Функция не найдена\n";
    return;
}
 
if (!array_key_exists($argv[2], $arr_separator)) {
    echo "Разделитель не найден\n";
    return;
}

// разбор csv

$membersArray = array();

$row = 1;
ini_set('auto_detect_line_endings',true);
$handle = fopen('people.csv','r');

while (($data = fgetcsv($handle, "", $arr_separator[$argv[2]]) ) !== false ) {
    $num = count($data);
    $row++;
    if ($num === 2) {
        $item = new Member($data[0], $data[1]);
        array_push($membersArray, $item);
    }
    else {
        echo "Найдена ошибка в файле .csv";
        return;
    }
}
ini_set('auto_detect_line_endings', false);

// вызов функций
if ($argv[1]==="countAverageLineCount") {
    foreach ($membersArray as $item) {
        $item->printAvg();
    };
};

if ($argv[1]==="replaceDates") {
    $dir = 'output_texts';
    // наличие директории
    if (!file_exists($dir)) {
        mkdir($dir);
    }
    else {
        //чистка
        $files = glob($dir."/*");
        foreach($files as $file){
            if(is_file($file)) {
                unlink($file);
            }
        }
    };

    foreach ($membersArray as $item) {
        $item->printReplaceCnt();
    };
};

?>