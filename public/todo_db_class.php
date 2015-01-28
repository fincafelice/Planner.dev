<?php

define ('DB_HOST', '127.0.0.1');
define ('DB_NAME', 'Todo_List');
define ('DB_USER', 'codeup');
define ('DB_PASS', 'codeup');

require_once('../db_connect.php');

if ($_POST) {
	$stmt = $query = "INSERT INTO todo_items (list_item) VALUES (:list_item)";
	$stmt = $dbc->prepare($query);
	$stmt->bindValue(':list_item', 		$_POST['add_item'], PDO::PARAM_STR);
	$stmt->execute();
}

if(isset($_GET['remove'])) {
    $id = $_GET['remove'];
    $input = 'DELETE FROM todo_items WHERE id = :id';
    $stmt = $dbc->prepare($input);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

$filename = 'todo_db_class.php';
$count = $dbc->query('SELECT count(*) FROM todo_items')->fetchColumn();
$limit = 3;
$offset = 0;
$pageCount = ceil($count/$limit);
$max = 1;

if(!isset($_GET['page'])) {
	$page = 1;
    $previous = max(abs($page - 1), $max);
    $next = abs($page + 1);
    $offset = abs($page -1) * $limit;
} else {
    $page = min($pageCount, $_GET['page']);
    $previous = max(abs($page - 1), $max);
	$next = min((abs($page) + 1), $pageCount);
	$offset = abs($page - 1) * $limit;
}
$query = 'SELECT * 
          FROM todo_items 
          LIMIT :limit OFFSET :offset';
$stmt = $dbc->prepare($query);
$stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$todo_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<html>
<head>
    <title>TODO List</title>
    <link rel="stylesheet" href="/extstyle.css">
    <style>
    	.label {
			font-size: 12px;
		}
    </style>
</head>

<body class = "body page-font">
    <h1 class = "header-color-and-underline">TODO List</h1>
    <ul class = "list-style">     
        <? foreach ($todo_array as $key => $value): ?>
            <li>
            	<?= $value['list_item'] ?> 	
            	<a href="/todo_db_class.php?remove=<?= $value['id'] ?>">Completed</a>
            </li>            
        <? endforeach ?>        
    </ul>
    <button><a href = "?page=<?=$previous?>">Previous</a></button>
	<button><a href = "?page=<?=$next?>">Next</a></button>
	<label class = "label">Any additional items are on next page.</label>

    <h2 class = "header-color-and-underline">Add to the list</h2>
    <form method="POST" action="/todo_db_class.php">  
    
    <label for="add_item">New Item</label>
    <input id="add_item" name="add_item" type="text" placeholder = "Add this to the list!">
           
    <button type="submit">Add</button>

    </form>
    </ul>
</body>
</html>