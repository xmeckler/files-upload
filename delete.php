<?php
/**
 * Created by PhpStorm.
 * User: wilder17
 * Date: 13/04/18
 * Time: 23:32
 */

if (file_exists("uploaded/" . $_POST['id'])) {

    unlink("uploaded/" . $_POST['id']);

    header('Location: index.php');
    exit();
}
