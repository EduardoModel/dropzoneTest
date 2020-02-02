<?php
    require_once("../config.php");
    $conn = mysqli_connect($config['db']['images-db']['host'], $config['db']['images-db']['dbname'], $config['db']['images-db']['password'], $config['db']['images-db']['username']);
    if(mysqli_connect_error()){
        echo "Was not able to connect with the server!";
        die("Was not able to connect with the database!");
    }
    //Fetch the images following the order of the sorting
    $sqlQuery = "SELECT * FROM images ORDER BY image_order ASC";
    $result = $conn->query($sqlQuery);
    $conn->close();

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $imageName = $row['image_name'];
            $imagePath = $row['image_path'];
            $imageDescription = $row['image_description'];
            echo '
            <li class="img-show ui-state-default" id="'.$imageName.'">
                <div class="imagePreviewDiv">
                    <div>
                        <a class="image-popup-no-margins" href="'.$imagePath.'">
                            <img src="'.$imagePath.'" alt="'.$imageName.'" class="img-tumbnail imagePreview" />
                        </a>
                    </div>
                    <div>
                        <input class="imageDescription" value="'.$imageDescription.'" id="'.$imageName.'" type="textarea" placeholder="Description">
                    </div>
                    <div>
                        <button type="button" class="btn btn-link remove-image"
                        id="'.$imageName.'">Remove</button>
                    </div>
                </div>
            </li>
            ';
        }
    }
/*
    $result = array();
    $files = scandir("upload");
    //$output = '<div class="row">';
    $output = '';
    if($files !== false){
        foreach($files as $file){
            //to ignore . and .. files
            if('.' != $file && '..' != $file){
                $output .= '
                <li class="img-show ui-state-default" id="img'.$file.'">
                    <div class="imagePreview">
                        <div>
                            <img src="upload/'.$file.'" class="img-tumbnail" 
                            width="120" height="120" style="height:120px" />
                        </div>
                        <div>
                            <button type="button" class="btn btn-link remove-image"
                            id="'.$file.'">Remove</button>
                        </div>
                    </div>
                </li>
                ';
            }
        }
    }
    echo $output;
    */
?>