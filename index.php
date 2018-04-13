<?php
/**
 * Created by PhpStorm.
 * User: wilder17
 * Date: 09/04/18
 * Time: 19:25
 */

$files = [];
if (isset($_POST['submit'])){
    if (count($_FILES['upload']['name']) > 0){
        //Loop through each file
        for ($i=0; $i<count($_FILES['upload']['name']); $i++) {
            //Get the temp file path
            $tmpFilePath = $_FILES['upload']['tmp_name'][$i];

            //Make sure we have a filepath
            if ($tmpFilePath != ""){
                $maxFileSize = 1000000;
                $fileSize = filesize($tmpFilePath);
                $errors = [];
                if ($fileSize > $maxFileSize) {
                    $errors[] = 'Le fichier ' . $_FILES['upload']['name'][$i] . ' excède la taille maximum authorisée (1Mo)' ;
                }
                $authorizedMime = array('image/png', 'image/gif', 'image/jpeg');
                $fileType = $_FILES['upload']['type'][$i];
                if (!in_array($fileType, $authorizedMime)){
                    $errors[] = 'Vous ne pouvez télécharger que des fichiers de type .png, .gif ou .jpg';
                }
                if (empty($errors)){

                //save the filename
                $shortname = $_FILES['upload']['name'][$i];

                $mimeToExtension = array (
                        'image/png' => '.png',
                        'image/gif' => '.gif',
                        'image/jpeg' => '.jpg'
                );
                //save the url and the file
                $filePath = "uploaded/" . uniqid('image'). $mimeToExtension[$_FILES['upload']['type'][$i]];

                //Upload the file into the temp dir
                if(move_uploaded_file($tmpFilePath, $filePath)) {

                    $files[] = $shortname;
                    //insert into db
                    //use $shortname for the filename
                    //use $filePath for the relative url to the file


                }
                } else {
                    foreach ($errors as $error) {
                        echo $error . '<br/>';
                    }
                }
            }
        }
    }
    //show success message
    echo "<h3>Fichiers téléchargés:</h3>";
    if(!empty($files)){
        echo "<ul>";
        foreach($files as $file){
            echo "<li>$file</li>";
        }
        echo "</ul>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Laisse pas traîner ton file</title>

        <!-- Bootstrap core CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">


    </head>

    <body>
        <h1>Mes images</h1>
        <section>
            <h2>Téléchargez vos images:</h2>
            <div class="container">
                <form action="" enctype="multipart/form-data" method="post" role="form">
                    <div class="form-group">
                        <label for="upload">Fichiers à télécharger (taille < 1Mo, format jpg, png ou gif)</label>
                        <input id='upload' name="upload[]" type="file" multiple="multiple" />
                    </div>
                    <p><input type="submit" name="submit" value="Submit"></p>
                </form>
            </div><!-- /.container -->
        </section>

        <section>
            <h2>Vos images téléchargées:</h2>
            <div class="container">
                <div class="row">
                    <?php
                    $uploadedFiles = scandir('uploaded');
                    foreach ($uploadedFiles as $uploadedFile) :
                        ?>
                         <div class="col-sm-6 col-md-4">
                            <div class="thumbnail">
                                <img class="img-responsive" src="uploaded/<?=$uploadedFile?>" alt="Image téléchargée">
                                <div class="caption">
                                    <h3><?=$uploadedFile?></h3>
                                    <form action="delete.php" method="POST">
                                        <input type="hidden" name="id" value="<?=$uploadedFile?>"/>
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                         </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </body>
</html>