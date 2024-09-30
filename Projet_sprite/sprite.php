<?php

global $img;
global $argv;
global $options;

$img = array();


function my_scandir($dir_path)
{
    global $img;

    $files = glob($dir_path . "/*");
    $img = array_merge($img, glob($dir_path . "/*.png"));


    foreach ($files as $filename) {
        if (is_dir($filename) == true) {
            my_scandir($filename);
        }
    } return $img;
}

my_scandir(".");

function sprite()
{
    global $img;
    $ttx = 0;
    $totalx = 0;
    $i = 1;


    foreach ($img as $value) {
        $imgsrc1 = imagecreatefrompng($value);
        $imagex = imagesx($imgsrc1);
        $totalx += $imagex;
        imagedestroy($imgsrc1);
    }
    $canva = imagecreatetruecolor($totalx, 5000);

    $css = fopen("style.css", "w");
    $incss = "body {
        background-color: black;
    }";

    fwrite($css, $incss);

    foreach ($img as $value) {
        $imgsrc2 = imagecreatefrompng($value);
        $width = imagesx($imgsrc2);
        $height = imagesy($imgsrc2);
        imagecopymerge($canva, $imgsrc2, $ttx, 0, 0, 0, $width, $height, 100);
        $ttx += $width;
        $class = $i;

        fwrite($css, ".$class {
            width: $ttx px;
            height: 50px;
            background-position: -3px, -3px;
        } \n");
        imagedestroy($imgsrc2);
        $i++;
    }


    imagepng($canva, "img.png");

    imagedestroy($canva);
}

sprite();
