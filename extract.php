<?php

$files = scandir("./json/");
$files = array_slice($files, 2);
$content = file_get_contents("./json/" . $files[0]);
$json = json_decode($content, true);
$bills = $json["bills"];
