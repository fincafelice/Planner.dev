<?php
    // $todo_array = [];
    //     'Invade Poland',
    //     'Remove the government',
    //     'Establish a monarchy',
    //     'Name myself Queen',
    //     'Exploit the country\'s natural resources'
    // ];

    $filename = 'todo_list.txt';
    
    // $newItems = openFile($filename);
    // $items = array_merge($filename, $newItems);

    function saveFile($filename, $array)
    {
        $handle = fopen($filename, 'w');
            foreach ($array as $item) {
                fwrite($handle, $item . PHP_EOL);
            }
        fclose($handle);
        // echo "The save was successful.\n";
    }

    function openFile($filename) 
    {
        // $contentsArray = [];
        
        if (filesize($filename) == 0) {
            return [];
        } else {
            $handle = fopen($filename, 'r');
            $contents = fread($handle, filesize($filename));
            $contentsArray = explode("\n", trim($contents));
            fclose($handle);
            return $contentsArray;
        }
    } 
    
    $todo_array = openFile($filename); // List to show on website

    if(isset($_POST['add_item'])) {
        $todo_array[] = $_POST['add_item'];  
        saveFile('todo_list.txt', $todo_array);  
    }

    if(isset($_GET['remove'])) {
        $id = $_GET['remove'];
        unset($todo_array[$id]);
        saveFile('todo_list.txt', $todo_array);
    }

    // Verify there were uploaded files and no errors
    if (count($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
        // Set the destination directory for uploads
        $uploadDir = 'uploads/';


        // Grab the filename from the uploaded file by using basename
        $uploaded_file = basename($_FILES['file1']['name']);

        // Create the saved filename using the file's original name and our upload directory
        $savedFilename = $uploadDir . $uploaded_file;

        // Move the file from the temp location to our uploads directory
        move_uploaded_file($_FILES['file1']['tmp_name'], $savedFilename);
        $todo_array2 = openFile($savedFilename);
        $todo_array = array_merge($todo_array, $todo_array2);
        // var_dump($savedFilename);
        // var_dump($todo_array);
        // var_dump($todo_array2);
        saveFile($filename, $todo_array);
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
        <!-- <li>Invade Poland</li>
        <li>Remove the government</li>
        <li>Establish a monarchy</li>
        <li>Name myself Queen</li>
        <li>Exploit the country's natural resources</li> -->
    </ul>
        <!-- create a form that contains the necessary inputs to add a TODO item to the list.  -->
    <h2 class = "header-color-and-underline">Add to the list</h2>
    <form method="POST" action="/todo_list.php">  
    
    <label for="add_item">New Item</label>
    <input id="add_item" name="add_item" type="text" placeholder = "Add that badboy!">
           
        <button type="submit">Add</button>
    <!-- <input type="submit"> -->

    </form>
    </ul>

    <h1>Upload File</h1>

    <?php
    // Check if we saved a file
    if (isset($savedFilename)) {
        // If we did, show a link to the uploaded file
        echo "<p>You can download your file <a href='/uploads/{$filename}'>here</a>.</p>";
    }
    ?>

    <form method="POST" enctype="multipart/form-data">
        <p>
            <label for="file1">File to upload: </label>
            <input type="file" id="file1" name="file1">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
    </form>
</body>
</html>