<?php

// Prepare response
$response = array(
	'status' => 'error',
	'errors' => array(),
	'message' => ''
);


// Expected POST variables
$post_defaults = array(
	'name' => '',
	'email' => '',
	'phone' => '',
	'message' => ''
);


// Merge default variables with POST data
$post = array_merge($post_defaults, $_POST);


// Clean up POST data 
// and remove the not expected
foreach($post_defaults as $key => $value)
{
	if(isset($post[$key]))
	{
		// Remove white space from beginning and end of string
		// and strip HTML tags (if necessary)
		$post[$key] = trim(strip_tags($post[$key]));
	}
	else
	{
		// Remove variables not found in defaults
		unset($post[$key]);
	}
}


// Validate POST data
foreach($post as $key => $value)
{
	switch($key)
	{
		case 'name':
		case 'message':
			if(strlen($value) < 2)
			{
				$response['errors'][] = array(
					'field' => $key
				);
			}
		break;
		
		case 'email':
			if(strlen($value) < 5 OR (strlen($value) >= 5 AND ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $value)))
			{
				$response['errors'][] = array(
					'field' => $key
				);
			}
		break;
	}
}

// Check for errors found
if(count($response['errors']) > 0)
{
	$response['message'] = 'Alla fält behöver fyllas i.';
}
else
{
	// Include MySQL settings
	require_once('settings.php');
	
	// Connect to database
	$mysqli = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
	
	// Test MySQL connection
	if($mysqli->connect_errno)
	{
		$response['message'] = 'Något gick fel på servern. Kontakta oss genom att ringa +46(0)8 121 382 55 eller maila hej@stunning.se.'; 
		
		mail('johan@stunning.se', "MySQL error", sprintf("MySQL connect failed: %s\n", $mysqli->connect_error));
	}
	else
	{
		// Set connection charset
		$mysqli->set_charset('utf8');
		
		// Check if participant email exists
		if($mysqli->query("INSERT INTO happypeople (id, date, name, email, phone, message) VALUES (NULL, NOW(), '{$post['name']}', '{$post['email']}', '{$post['phone']}', '{$post['message']}')"))
		{
			$response['message'] = 'Tack för ditt meddelande!';
			$response['status'] = 'success';
		}	
	}
}

// Check if request was made with AJAX
$xhr = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? TRUE : FALSE;

if($xhr === TRUE)
{
	header('Content-type: application/json');
	
	// Print response
	die(json_encode($response));
}

// Remove response if not a form request
if( ! isset($_POST['submit']))
{
	unset($response);
}
else
{
	$errors = array();
	
	foreach($response['errors'] as $error)
	{
		$errors[$error['field']] = TRUE;
	}
}

require_once('index.php');
