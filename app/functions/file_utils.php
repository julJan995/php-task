<?php

function createTemporaryDirectory($temporary_directory) {
    if (!is_dir($temporary_directory)) {
        if (mkdir($temporary_directory, 0755)) {
            //echo "Folder '$temporary_directory' was created.";
        } else {
            //echo "Failed to create '$temporary_directory' folder.";
        }
    } else {
        //echo "Folder '$temporary_directory' already exists.";
    }
}

function cleanExpiredFiles($response_data, $temporary_folder, $expire_time) {
    if (isset($response_data['data']) && is_array($response_data['data'])) {
        $files = scandir($temporary_folder);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $temporary_folder . $file;
                if (file_exists($filePath) && time() - filemtime($filePath) > $expire_time) {
                    unlink($filePath);
                    //echo "File $file expired and has been deleted.<br>";
                }
            }
        }
    }
}
function downloadAndSaveImgs($response_data, $temporary_folder, $expire_time) {
    foreach ($response_data['data'] as $artwork) {
        if (isset($artwork['image_id'])) {
            $image_id = $artwork['image_id'];
            $imageUrl = "https://www.artic.edu/iiif/2/$image_id/full/843,/0/default.jpg";
            $temporaryFilename = $temporary_folder . $image_id . '.jpg';
            if (!file_exists($temporaryFilename) || filemtime($temporaryFilename) < (time() - $expire_time)) {
                $imageData = file_get_contents($imageUrl);
                if ($imageData !== false) {
                    file_put_contents($temporaryFilename, $imageData);
                    //echo "Image ID $image_id saved as $temporaryFilename<br>";
                } else {
                    //echo "Failed to download image for ID $image_id<br>";
                }
            } else {
                //echo "Using cached image for ID $image_id<br>";
            }
        }
    }
}
?>