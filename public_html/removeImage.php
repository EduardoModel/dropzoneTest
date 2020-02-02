<?php
    require_once("../config.php");
    $conn = mysqli_connect($config['db']['images-db']['host'], $config['db']['images-db']['dbname'], $config['db']['images-db']['password'], $config['db']['images-db']['username']);
    if(mysqli_connect_error()){
        echo "Was not able to connect with the server!";
        die("Was not able to connect with the database!");
    }
    //Fetch the images following the order of the sorting
    $imgName = $_POST['imageName'];
    $sqlQuery = "SELECT * FROM images WHERE image_name = \"".$imgName."\"";
    $result = $conn->query($sqlQuery);
    //echo "Results ".$result->num_rows;
    if($result->num_rows == 1){
        unlink($uploadDir.$imgName);
        $sqlQuery = "DELETE FROM images WHERE image_name = \"".$imgName."\" LIMIT 1";
        //to prevent SQL Injections with this function
        //$sqlQuery = $conn->real_escape_string($sqlQuery);
        $result = $conn->query($sqlQuery);
    }
    $conn->close();
?>