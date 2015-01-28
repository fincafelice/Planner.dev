<?php

define ('DB_HOST', '127.0.0.1');
define ('DB_NAME', 'Address_Book');
define ('DB_USER', 'codeup');
define ('DB_PASS', 'codeup');

require_once('../db_connect.php');

if ($_POST) {

    $stmt = $query = "INSERT INTO names (first_name, last_name, phone) VALUES (:first_name, :last_name, :phone)";
    $stmt = $dbc->prepare($query);

    $stmt->bindValue(':first_name',     $_POST['first_name'],   PDO::PARAM_STR);
    $stmt->bindValue(':last_name',      $_POST['last_name'],    PDO::PARAM_STR);
    $stmt->bindValue(':phone',          $_POST['phone'],        PDO::PARAM_STR);

    $stmt->execute();    
}

if(isset($_GET['remove'])) {
    $id = $_GET['remove'];
    $input = 'DELETE FROM names WHERE id = :id';
    $stmt = $dbc->prepare($input);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

$query = 'SELECT id, first_name, last_name, phone 
          FROM names
          ORDER BY last_name ASC';

$stmt = $dbc->prepare($query);
$stmt->execute();
$addressBook = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
<head>
    <title>Address Book</title>
    <link rel="stylesheet" href="/extstyle.css">
    <style>
        .hide {
            display: none;
        }
    </style>
</head>

<body class = "body page-font">
    <h1 class = "header-color-and-underline">Address Book</h1>
    <table>
        <tr>
            <th class = "hide">ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Phone</th>
            <th>Remove</th>
        </tr>
        <? foreach ($addressBook as $row): ?>
            <tr>
                <td><?= $row['first_name'] ?></td>
                <td><?= $row['last_name'] ?></td>
                <td><?= $row['phone'] ?></td>
                <td> <a href="/address_book_db_class.php?remove=<?= $row['id']?>">Remove</a> </td>
            </tr>
        <? endforeach ?>
    </table>

    <h2 class = "header-color-and-underline">Add New Contact</h2>
    <form method="POST" action="/address_book_db_class.php">      
        <input id="first_name" name="first_name" type="text" placeholder = "Enter Name.">
        <label for="first_name">First Name</label>
        <br>
        <input id="last_name" name="last_name" type="text" placeholder = "Enter address.">
        <label for="last_name">Last Name</label>
        <br>
        <input id="phone" name="phone" type="text" placeholder = "Enter phone.">
        <label for="phone">New Phone</label>
        <br>
        <button type="submit">Add</button>
    </form>
</body>
</html>