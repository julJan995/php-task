<?php

function applySepiaFilter($filePath) {
    $image = imagecreatefromjpeg($filePath);
    imagefilter($image, IMG_FILTER_GRAYSCALE);
    imagefilter($image, IMG_FILTER_COLORIZE, 100, 50, 0);

    ob_start();
    imagejpeg($image);
    $imageData = ob_get_contents();
    ob_end_clean();

    imagedestroy($image);
    return $imageData;
  }

?>