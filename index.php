<?php
include('./app/functions/file_utils.php');
include('./app/functions/sepia_utils.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PHP task</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./resources/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
<div class="container">
  <nav class="navbar fixed-top bg-body-tertiary bg-dark border-bottom border-body" data-bs-theme="dark">
    <div class="container-fluid">
      <a class="navbar-brand text-light" href="#">PHP task</a>
    </div>
  </nav>
</div>

<div id="carouselExample" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <?php
    $temporary_directory = 'temporary_images';
    $files = scandir($temporary_directory);
    $first = true;

    foreach ($files as $file) {
      if ($file !== '.' && $file !== '..') {
        $filePath = $temporary_directory . '/' . $file;
        $activeClass = $first ? 'active' : '';

        echo "<div class=\"carousel-item $activeClass\">";
        $sepiaImage = applySepiaFilter($filePath);
        echo "<img src=\"data:image/jpeg;base64," . base64_encode($sepiaImage) . "\" style=\"max-width: 600px; max-height: 600px; display: block; margin: 150px auto;\" class=\"d-block w-100\">";
        echo "</div>";

        $first = false;
      }
    }
    ?>
  </div>

  <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
</body>
</html>

<?php
$api_url="https://api.artic.edu/api/v1/artworks";
$response = file_get_contents($api_url);
$response_data = json_decode($response, true);

$temporary_directory = 'temporary_images';
$expire_time = 120 * 60;
$temporary_folder = $temporary_directory . '/';

createTemporaryDirectory($temporary_directory);
cleanExpiredFiles($response_data, $temporary_folder, $expire_time);
downloadAndSaveImgs($response_data, $temporary_folder, $expire_time);
?>
