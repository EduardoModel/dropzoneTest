<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dropzone Test</title>

     <!-- Include of jQuery -->
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>

    <!-- Include of jQuery UI -->
    <script
    src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
    integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
    crossorigin="anonymous"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" 
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" 
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <!-- Dropzone Library -->
    <script src="js/dropzone.js"></script>
    <link rel="stylesheet" href="css/dropzone.css">

    <!-- Magnific Popup -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">


    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div>
        <div class="myForm">
            <div class=imgPreview>
                <ul id="imageList">
                    <?php include "showImages.php" ?>
                </ul>
            </div>
            <form action="upload.php" url="upload/" class="dropzone" id="myDrop">          
            </form>
        </div>
    
        <button type="button" class="btn btn-info" id="submitAll">Update</button>
    </div>
    
</body>
</html>

<script>

Dropzone.options.myDrop = {
        acceptedFiles: '.png,.jpg,.gif,.bmp,.jpeg',
        //Although the upload multiple is false, the user can upload
        //more than one file!
        uploadMultiple: true,
        parallelUploads: 10,
        maxFileSize: 2,
        autoProcessQueue: false,
        addRemoveLinks: true,
        init: function(){
            //To save the this object from the dropzone
            const myDrop = this
            //Event listener for the button
            $('#submitAll').on("click", function(){
                //Part responsable to send the new position of the old images
                let imagesToRearrange = []
                let imageDescription, imageName
                $("#imageList").children('li').each(function(){ 
                    imageName = this.id
                    imageDescription = $(this).find("input").val()
                    imagesToRearrange = [...imagesToRearrange, {imageName, imageDescription}];
                })
                $.ajax({
                    url: "upload.php",
                    type: "POST",
                    data: {imagesToRearrange}
                }).done(function(res){
                    $("#imageList").load("showImages.php")
                })

                //Part responsable to send the images inside the dropzone

                let files = myDrop.getQueuedFiles()
                //To send the images already sorted to the server
                files.sort((a,b) => {
                    return ($(a.previewElement).index() > $(b.previewElement).index() ? 1 : -1)
                })
                //Remove files unsorted
                myDrop.removeAllFiles()
                //Add files sorted
                myDrop.handleFiles(files)
                //Submit the images to the server after the button was pressed
                myDrop.processQueue()
            })
            myDrop.on("complete", function(){
                //To check if still some pictures loading to the server
                if(this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0){
                    //Clear image tumbmail after the submission
                    this.removeAllFiles()
                }
            })
            //Event listener to enlarge the image preview
            myDrop.on('addedfile', function(file){
                file.previewElement.addEventListener("click", function(e){
                    
                })
            })
        }
    }

   

$(document).ready(function(){
    $(".remove-image").on("click", function(){
        const imageName = $(this).attr("id")
        //To fadeOut the div that contains the image and then remove the div from the DOM
        $(this).parent().parent().parent().fadeOut(400, function(){
            $(this).remove()
        })
        $.ajax({
            url: 'removeImage.php',
            type: 'POST',
            data: {imageName}//,
            // success: (response) => {
            //     console.log(response)
            // }
        })
    })
    $("#imageList").sortable()
    $("#imageList").disableSelection()
    $("#myDrop").sortable()
    $("#myDrop").disableSelection()
    $(".image-popup-no-margins").magnificPopup({
        type: 'image',
        mainClass: 'mfp-with-zoom',
        zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out',
            opener: (openerElement) => {
                return openerElement.is('img') ? openerElement : openerElement.find('img')
            }
        }
    })
});
</script>
