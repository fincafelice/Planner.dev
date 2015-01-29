<?php 

define ('DB_HOST', '127.0.0.1');
define ('DB_NAME', 'Address_Book');
define ('DB_USER', 'codeup');
define ('DB_PASS', 'codeup');

require_once('../db_connect.php');

// require_once('address_book_db_class.php');
require_once('address_book_db_class2.php'); 
    
if (!empty($_POST['street'])) {       
    echo" 
        <script language = 'javascript'>
            alert ('The address was successfully added.');
        </script>";
}
?>

<html>
<head>
    <title>Address Book2</title>
    <link rel="stylesheet" href="/extstyle.css">
    <style>
        .hide {
            display: none;
        }
    </style>
</head>

<body>
<h2 class = "header-color-and-underline">Add New Address</h2>
<form method="POST" action="/address_book_model_class2.php">      
    <input id="street" name="street" type="text" placeholder = "Enter street.">
    <label for="street">Street</label>
    <br>
    <input id="apt" name="apt" type="text" placeholder = "Apt, if applicable.">
    <label for="apt">Apt</label>
    <br>
    <input id="city" name="city" type="text" placeholder = "Enter city.">
    <label for="city">New City</label>
    <br>
    <input id="state" name="state" type="text" placeholder = "Enter state.">
    <label for="state">New State</label>
    <br>
    <input id="zip" name="zip" type="text" placeholder = "Enter zip.">
    <label for="zip">New Zip</label>
    <br>

    <!-- Variable names_id is returning empty string instead of number for foreign key (the link between the two tables) -->
    <input class = "hide" id="names_id" name="names_id" type="text" placeholder = "Not used.">
    <label for="names_id"></label>
    <br>

    <button type="submit">Add</button>
    <button type = "submit"><a href = "/address_book_model_class.php">Return to Main Page</a></button>

</form>
</body>
</htm;>