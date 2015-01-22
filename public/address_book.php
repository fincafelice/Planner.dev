<?php 
require_once('filestore.php');

$error = false; // works with js below
// create a new class named AddressDataStore to handle reading and writing to the CSV file. 1/8/15
class AddressDataStore extends Filestore
{
    public function __construct ($filename)
    {
        parent::__construct($filename);
        // $this->filename = strtolower($filename);
    }

    public function sanitize ($array) 
    {
        foreach ($array as $key => $value) {
            $array[$key] = htmlspecialchars(strip_tags($value));  // Overwrite each value.
        }
        return $array;
    }
    
} // end of class AddressDataStore 1/8/15
$filename = 'address_book.csv';
$addressObject = new AddressDataStore ($filename); // object name 1/8/15
// $addressObject->filename = 'address_book.csv'; // designate which file to use
$addressBook = $addressObject->read(); // array

// var_dump($addressObject);

// Create a function to store a new entry.
if(!empty($_POST)) {        
    if (!empty($_POST['contact']) && !empty($_POST['address']) && 
        !empty($_POST['city']) && !empty($_POST['state']) && !empty($_POST['zipcode']) && 
        !empty($_POST['phone']) && !empty($_POST['email'])) {
        // Strip tags / etc from $value
        $cleanPost = $addressObject->sanitize($_POST); // call the object 1/8/15
        array_push($addressBook, $cleanPost);
        $addressObject->write($addressBook); // call object and save array to address_book.csv
    } else {
        $error = true;
    }
}

// Add a delete link with a query string to delete the record. 
// When the page reloads, the record should be gone, and the 
// save csv should no longer have the entry.

if(isset($_GET['remove'])) {
        $id = $_GET['remove'];
        unset($addressBook[$id]);
        $addressObject->write($addressBook); // call object and save to address_book.csv
}

    // Verify there were uploaded files and no errors
    if (count($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
        // Set the destination directory for uploads
        $uploadDir = 'uploads/';

        // Grab the filename from the uploaded file by using basename
        $uploaded_file = basename($_FILES['file1']['name']);

        if (substr($uploaded_file, -3) != "csv") {
            echo "Please upload only '.csv' files.  " . PHP_EOL;
            echo "Hit your browser's back button to continue.";
            exit();
            } else {
            // Create the saved filename using the file's original name and our upload directory
                $savedFilename = $uploadDir . $uploaded_file;
        }

        // Move the file from the temp location to our uploads directory
        move_uploaded_file($_FILES['file1']['tmp_name'], $savedFilename);
        

        // 2nd object for new array from uploaded file
        $addressObjectFromFile = new AddressDataStore($savedFilename); // pass file due to construct
        $todo_array2 = $addressObjectFromFile->read();
        $addressBook = array_merge($addressBook, $todo_array2);
        $addressObject->write($addressBook);
    } 
?>


<html>
<head>
    <title>Address Book</title>
    <link rel="stylesheet" href="/extstyle.css">
</head>

<body class = "body page-font">
    <h1 class = "header-color-and-underline">Address Book</h1>


    <table>
        <tr>
            <th>Contact</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>Zipcode</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Remove</th>
        </tr>
        <!--PHP and echo data -->
        <? foreach ($addressBook as $key => $contact): ?>
            <tr>
                <? foreach($contact as $value): ?> 
                    <td><?= $value ?></td>
                <? endforeach; ?>
                <td> <a href="?remove=<?=$key;?>">Remove</a> </td>
            </tr>
        <? endforeach; ?>

    </table>

        <!-- create a form that contains the necessary inputs to add an item.  -->
    <h2 class = "header-color-and-underline">Add New Contact</h2>
    <form method="POST" action="/address_book.php">  
    
        <input id="contact" name="contact" type="text" placeholder = "Enter Name.">
        <label for="contact">New Contact</label>
        <br>
        <input id="address" name="address" type="text" placeholder = "Enter address.">
        <label for="address">New Address</label>
        <br>
        <input id="city" name="city" type="text" placeholder = "Enter city.">
        <label for="city">New City</label>
        <br>
        <input id="state" name="state" type="text" placeholder = "Enter state.">
        <label for="state">New State</label>
        <br>
        <input id="zipcode" name="zipcode" type="text" placeholder = "Enter zipcode.">
        <label for="zipcode">New Zipcode</label>
        <br>
        <input id="phone" name="phone" type="text" placeholder = "Enter phone.">
        <label for="phone">New Phone</label>
        <br>
        <input id="email" name="email" type="text" placeholder = "Enter email.">
        <label for="email">New Email</label>
        <br>
    <!-- <input type="submit"> -->
        <button type="submit">Add</button>
    </form>

    </form>
    </ul>

    <h2 class = "header-color-and-underline">Upload File</h2>
    <form method="POST" enctype="multipart/form-data">
        <p>
            <class = "header-color-and-underline"><label for="file1">File to upload: </label>
            <input type="file" id="file1" name="file1">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
    </form>

    <script>
        <?php
            if ($error) {
                echo "alert('Please enter all fields.')";
            }
        ?>
    </script>

</body>
</html>