<?php
  $photoName = $_POST['photoName'];
  $dateTaken = $_POST['dateTaken'];
  $photographer = $_POST['photographer'];
  $locationOfPhoto = preg_replace('/\t|\R/',' ',$_POST['locationOfPhoto']);
  $uploadImage = $_FILES['uploadImage']['name'];
  $date = date('H:i, jS F Y');
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Assignment 1</title>
  </head>
  <body>
    <div>
      <h1>Simple Photo Gallery</h1>
      <p>Upload your Photo</p>
    </div>

    <div class="container" style="margin-top: 25px;">
      <h3>View the Gallery</h3>
      <div class="d-flex flex-row">
        <div style="padding-right: 10px;">
          <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              Sort By
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item">Name</a></li>
              <li><a class="dropdown-item">Date action</a></li>
              <li><a class="dropdown-item">Photographer</a></li>
              <li><a class="dropdown-item">Location</a></li>
            </ul>
          </div>
        </div>
        <div style="padding-left: 10px;">
          <form action="./index.html" method="post" enctype="multipart/form-data">
            <button type="submit" class="btn btn-primary">Upload New Photo</button>
          </form>
        </div>
      </div>
    </div>

    <div class="container">
      <?php
        if(isset($_POST['submitBtn'])) { 
          UploadData($photoName, $dateTaken, $photographer, $locationOfPhoto, $uploadImage, $date);
          UploadPhoto();
        }
        
        function UploadData($photoName, $dateTaken, $photographer, $locationOfPhoto, $uploadImage, $date) {
          $uploadString = $date."\t".$photoName."\t".$dateTaken."\t".$photographer."\t".$locationOfPhoto."\t".$uploadImage."\n";
      
          @$fp = fopen("./gallery.txt", 'ab');
        
          if (!$fp) {
          echo "<p><strong> Problem: Could not move file to destination directory
          Please try again later.</strong></p>";
          exit;
          }
        
          flock($fp, LOCK_EX);
          fwrite($fp, $uploadString, strlen($uploadString));
          flock($fp, LOCK_UN);
          fclose($fp);
        }

        function UploadPhoto() {
          $target_dir = "uploads/";
          $target_file = $target_dir . basename($_FILES["uploadImage"]["name"]);
          $upload = 1;
          $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
          // Check if image file is a actual image or fake image
          if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["uploadImage"]["tmp_name"]);
            if($check !== false) {
              echo "File is an image - " . $check["mime"] . ".";
              $upload = 1;
            } else {
              echo "This file is not an image";
              $upload = 0;
            }
          }
        
          // Determines if the photo already exists
          if (photo_exists($target_file)) {
            echo "Sorry, file already exists.<br>";
            $upload = 0;
          }
                
          // Checks if the file extensions are correct
          if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg") {
            echo "Problem: file is not a PNG, JPEG, or JPG:<br>";
            $upload = 0;
          }
        
          // Check if $upload is set to 0 by an error
          if ($upload == 0) {
            echo "Your image was not uploaded correctly";
          } else {
            if (move_uploaded_file($_FILES["uploadImage"]["tmp_name"], $target_file)) {
              echo "Your image ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded correctly.";
            } else {
              echo "Error in uploading your image.<br>";
            }
          }
        }
      ?>
    </div>

    <div class="container">
      <div class="row" style="padding-top: 25px;">
        <?php
        // scan "uploads" folder and display files
        $directory = "./uploads";
        $results = scandir('./uploads');

        foreach ($results as $result) {
          if ($result === '.' or $result === '..') {
            continue;
          }
          if (is_file($directory . '/' . $result)) {
            echo '
            <div class="col-md-3" style="padding-bottom: 25px; padding-top: 25px;">
              <div class="thumbnail">
                <img src="'.$directory . '/' . $result.'" alt="..." style="width:100%">
                  <!-- <div class="caption">
                    <p>'.$photoName.'<br>'.$dateTaken.'<br>'.$photographer.'<br>'.$locationOfPhoto.'</p>
                  </div> -->
              </div>
            </div>';
          }
        }
        ?>
      </div>
    </div>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

  </body>
</html>
