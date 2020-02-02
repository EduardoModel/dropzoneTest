<?php
    require_once("../config.php");
    $conn = mysqli_connect($config['db']['images-db']['host'], $config['db']['images-db']['dbname'], $config['db']['images-db']['password'], $config['db']['images-db']['username']);
    if(mysqli_connect_error()){
        echo "Was not able to connect with the server!";
        die("Was not able to connect with the database!");
    }
    $count = 1;
    //Update the images there are already in the server
    if(!empty($_POST['imagesToRearrange'])){
        foreach($_POST['imagesToRearrange'] as $image){
            //This procedure is to avoid SQL Injections on the database
            $sqlQuery = "UPDATE images SET image_order=?, image_description=? WHERE image_name=? LIMIT 1";
            $stmt = $conn->prepare($sqlQuery);
            $stmt->bind_param("iss", $count, $image['imageDescription'], $image['imageName']);
            //Returns true on sucess and false on faliure
            $stmt->execute();
            //$sqlQuery = "UPDATE images SET image_order=".$count.", image_description='".$image['imageDescription']."' WHERE image_name='".$image['imageName']."' LIMIT 1";
            //Echo to debugging
            //echo $sqlQuery."\n";
            //$conn->query($sqlQuery);
            $count++;
        }
    }

    //Test if there is images to add to the 
    if(!empty($_FILES)){
        //Fetch the last order count inside the database
        $queryOrder = "SELECT image_order FROM images ORDER BY image_order DESC";
        $result = $conn->query($queryOrder);
        if($result->num_rows > 0){
            //print_r($result->fetch_assoc());
            $count += $result->fetch_assoc()['image_order'];
        }

        $images = $_FILES['file'];
        for($i=0; $i<sizeof($images['name']);$i++){
            //To generate a unique name for all images, possibiliting two or more equal images to save in the server with different names 
            $uniqImageName = uniqid('img', true).$images['name'][$i];
            //Save first the record inside the database
            $sqlQuery = "INSERT INTO images (image_name, image_path, image_order, date) VALUES(?, ?, ?, NOW())";
            $stmt = $conn->prepare($sqlQuery);
            $stmt->bind_param("iss", $count, $image['imageDescription'], $image['imageName']);
            //$sqlQuery = "INSERT INTO images (image_name, image_path, image_order, date) VALUES('".$uniqImageName."', '".$uploadDir.$uniqImageName."', '".($i+$count)."', NOW())";
            //to prevent SQL Injections with this function
            //$sqlQuery = $conn->real_escape_string($sqlQuery);
            //echo $sqlQuery;
            $conn->query($sqlQuery);
            //if everything ran ok, save the image inside the folder
            $temp_file = $images['tmp_name'][$i];
            $locationToSave = $uploadDir.$uniqImageName;
            move_uploaded_file($temp_file, $locationToSave);
        }
    }
    $conn->close();
?>