<?php 

$addressBook = [
    ['Fred Flintstone', '1600 Pennsylvania Ave NW', 'Washington', 'DC', '20500', '8885550000', 'fred@stone.rok'],
    ['Daffy Duck', 'P.O. Box 1527', 'Long Island City', 'NY', '11101', '8884442222', 'pete@web.net'],
    ['Minnie Mouse', 'P.O. Box 29901', 'San Francisco', 'CA', '94129', '8883337777', 'cutie@tail.rat'],
    ['Someone Else', '123 Lane', 'San Diego', 'CA', '92501', '8885552222', 'me@that.net']
];

$filename = 'address_book.csv';

// snippet user defined function
// This function will accept a dirty array; and return a clean one.
// sanitize ($array);
function sanitize ($array) 
{
    foreach ($array as $key => $value) {
        $array[$key] = htmlspecialchars(strip_tags($value));  // Overwrite each value.
    }
    return $array;
}

function saveFile ($filename, $addressBook) 
{
	$handle = fopen('address_book.csv', 'w');
		foreach ($addressBook as $row) {
	    	fputcsv($handle, $row);
		}
	fclose($handle);
}


// Create a function to store a new entry.
// if(!empty($_POST)) {
	// foreach ($_POST as $key => $value) {
		
		if (!empty($_POST['contact']) && !empty($_POST['address']) && 
			!empty($_POST['city']) && !empty($_POST['state']) && !empty($_POST['zipcode']) && 
			!empty($_POST['phone']) && !empty($_POST['email'])) {
			// Strip tags / etc from $value
            $cleanPost = sanitize($_POST);
            array_push($addressBook, $cleanPost);
            saveFile($filename, $addressBook);
        } else {
			echo "Please enter all fields.";
		}
	// }
// }

// saveFile($filename, $addressBook); // address_book.csv


// if(isset($_GET['remove'])) {
//         $id = $_GET['remove'];
//         unset($array[$id]);
//         saveFile($filename, $array); // address_book.csv
// }

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
    	</tr>
    	<!--PHP and echo data -->
    	<? foreach ($addressBook as $key => $contact): ?>
    		<tr>
	    		<? foreach($contact as $value): ?> 
	    			<td><?= $value ?></td>
	    		<? endforeach; ?>
	    	</tr>
	    <? endforeach; ?>

	</table>

        <!-- create a form that contains the necessary inputs to add an item.  -->
    <h2 class = "header-color-and-underline">Add new contact</h2>
    <form method="POST" action="/address_book.php">  
    
    <label for="contact">New Contact</label>
    <input id="contact" name="contact" type="text" placeholder = "MUST ENTER NAME.">
    <br>
	<label for="address">New Address</label>
    <input id="address" name="address" type="text" placeholder = "Enter address or NA.">
    <br>
    <label for="city">New City</label>
    <input id="city" name="city" type="text" placeholder = "Enter city or NA.">
    <br>
    <label for="state">New State</label>
    <input id="state" name="state" type="text" placeholder = "Enter state or NA.">
    <br>
    <label for="zipcode">New Zipcode</label>
    <input id="zipcode" name="zipcode" type="text" placeholder = "Enter zipcode or NA.">
    <br>
    <label for="phone">New Phone</label>
    <input id="phone" name="phone" type="text" placeholder = "Enter phone or NA.">
    <br>
    <label for="email">New Email</label>
    <input id="email" name="email" type="text" placeholder = "Enter email or NA.">
    <br>
    <!-- <input type="submit"> -->
        <button type="submit">Add</button>
    </form>

</body>
</html>