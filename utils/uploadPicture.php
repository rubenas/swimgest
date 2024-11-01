<?php

function uploadPicture($postName, $route)
{

    if (!is_uploaded_file($_FILES[$postName]['tmp_name'])) {
        return [
            'success' => false,
            'error' => 'No se ha subido ningún archivo, comprueba que cumple los requisitos'
        ];
    }

    if ($_FILES[$postName]['type'] != "image/jpeg" && $_FILES[$postName]['type'] != "image/png") {

        unlink($_FILES[$postName]['tmp_name']);

        return [
            'success' => false,
            'error' => 'El tipo de archivo no es válido'
        ];
    }

    if ($_FILES[$postName]['size'] > 1048576) {

        unlink($_FILES[$postName]['tmp_name']);

        return [
            'success' => false,
            'error' => 'El archivo es demasiado grande'
        ];
    }

    switch ($_FILES[$postName]['type']) {

        case "image/jpeg":
        case "image/jpg":
            $extension = ".webp";
            $im = imagecreatefromjpeg($_FILES[$postName]['tmp_name']);
            break;

        case "image/png":
            $extension = ".png";
            $im = imagecreatefrompng($_FILES[$postName]['tmp_name']);
            break;
    }

    if (!$im) {

        unlink($_FILES[$postName]['tmp_name']);

        return [
            'success' => false,
            'error' => 'Archivo no válido'
        ];
    }

    //Cropping square

    $size = min(imagesx($im), imagesy($im));

    $im = imagecrop($im, ['x' => (imagesx($im) - $size) / 2, 'y' => (imagesy($im) - $size) / 2, 'width' => $size, 'height' => $size]);


    //Resizing max 500px

    $newSize = min($size, 500);

    $im2 = imagecreatetruecolor($newSize, $newSize);
    imagealphablending($im2, FALSE);
    imagesavealpha($im2, TRUE);

    imagecopyresized($im2, $im, 0, 0, 0, 0, $newSize, $newSize, $size, $size);

    //Saving result

    $imageRoute = $route . $extension;

    if ($extension == '.webp') {

        imagewebp($im2, $imageRoute, 60);
    } else if ($extension == '.png') {

        imagepng($im2, $imageRoute, 6);
    }

    //Clearing 
    imagedestroy($im2);
    imagedestroy($im);
    unlink($_FILES[$postName]['tmp_name']);

    return $imageRoute;
}
