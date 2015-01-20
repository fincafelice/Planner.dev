<?php 
require_once 'filestore.php';


    $fileStore = new Filestore('todo_list.txt');
    
    $todo_array = $fileStore->readLines(); // List to show on website

    if(isset($_POST['add_item'])) {
        $todo_array[] = htmlentities(strip_tags($_POST['add_item']));  
        $fileStore->writeLines($fileStore->filename, $todo_array); // todo_list.txt
    }

    if(isset($_GET['remove'])) {
        $id = $_GET['remove'];
        unset($todo_array[$id]);
        $fileStore->writeLines($fileStore->filename, $todo_array); // todo_list.txt
    }

    // Verify there were uploaded files and no errors
    if (count($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
        // Set the destination directory for uploads
        $uploadDir = 'uploads/';

        // Grab the filename from the uploaded file by using basename
        $uploaded_file = basename($_FILES['file1']['name']);

        if (substr($uploaded_file, -3) != "txt") {
            echo "Please upload only '.txt' files.  " . PHP_EOL;
            echo "Hit your browser's back button to continue.";
            exit();
            } else {
            // Create the saved filename using the file's original name and our upload directory
                $savedFilename = $uploadDir . $uploaded_file;
        }

        // Move the file from the temp location to our uploads directory
        move_uploaded_file($_FILES['file1']['tmp_name'], $savedFilename);
        // create a new Filestore instance for your uploaded file
        $var = new fileStore($savedFilename);
        // use that NEW instance to read in your lines
        $todo_array2 = $var->readLines($savedFilename);
        $todo_array = array_merge($todo_array, $todo_array2);
        $fileStore->writeLines($fileStore->filename, $todo_array);
    }    

?>

<html>
<head>
    <title>TODO List</title>
    <link rel="stylesheet" href="/extstyle.css">
</head>

<body class = "body page-font">
    <h1 class = "header-color-and-underline">TODO List</h1>
    <ul class = "list-style">
        <?php foreach ($todo_array as $key => $value){
             echo "<li>{$value} | <a href='/todo_list.php?remove={$key}'>Completed</a></li>";
            }
        ?>        
    </ul>
        <!--create a form that contains the necessary inputs to add a TODO item to the list.-->
    <h2 class = "header-color-and-underline">Add to the list</h2>
    <form method="POST" action="/todo_list.php">  
    
    <label for="add_item">New Item</label>
    <input id="add_item" name="add_item" type="text" placeholder = "Add this to the list!">
           
        <button type="submit">Add</button>
    <!-- <input type="submit"> -->

    </form>
    </ul>

    <h1>Upload File</h1>


    <form method="POST" enctype="multipart/form-data">
        <p>
            <label for="file1">File to upload: </label>
            <input type="file" id="file1" name="file1">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
    </form>
    <?= // alternative php syntax 12/22/2014
         "<p> You can grab a copy of your updated file <a href='/{$fileStore->filename}'>here</a>. Just refresh the page.</p>";
    ?>
</body>
</html>