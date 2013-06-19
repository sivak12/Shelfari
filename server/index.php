<?php 
require 'Slim/Slim.php';
use Slim\Slim;
\Slim\Slim::registerAutoloader();

$app = new Slim();


$dbconn = pg_connect("host=ec2-50-19-228-92.compute-1.amazonaws.com port=5432 dbname=d5kc1otcr9j94d user=mvopcsddhypxdm password=HSsKW55djPc2Zz5BT4enGxMU7P sslmode=require options='--client_encoding=UTF8'") or die('Could not connect: ' . pg_last_error());	

/* List books or Edit a book */
$app->get('/books(/:id)',function($id = null) use($app, $dbconn) {

	if (null === $id) {
		$query = "select id,name,author,read_status from book order by id desc";
		$result = pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
		$books = pg_fetch_all($result);
		echo json_encode($books);		
	}
	else {
		$query = "select id,name,author,read_status from book where id = '$id'";
		$result = pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
		$books = pg_fetch_all($result);
		$book = $books[0];
		echo json_encode($book);		
	}
	pg_close($dbconn); 
});


/* Search a book */
$app->get('/search/:name',function($name) use($app, $dbconn) {
	
	$query = "select id,name,author,read_status from book where name = '$name'";
	$result = pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
	$books = pg_fetch_all($result);
	echo json_encode($books);
	
	pg_close($dbconn); 
});


/* Add a Book*/
$app->post('/books', function () use ($app, $dbconn) {
	
	$request = Slim::getInstance()->request();
	$book = json_decode($request->getBody());

	$book_name = pg_escape_string($book->name);
	$book_author = pg_escape_string($book->author);
	$book_read_status = $book->read_status;
	
	if($book_read_status == null || $book_read_status == '') {
		$book_read_status = 'f';
	}
	else {
		$book_read_status = pg_escape_string($book->read_status);
	}
	
	$query = "INSERT INTO book (name, author,read_status) VALUES('$book_name', '$book_author','$book_read_status' )";
	
	$result = pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
	pg_close($dbconn); 
});

/* Update a Book*/
$app->put('/books/:id', function ($id) use ($app, $dbconn) {
	
	$request = Slim::getInstance()->request();
	$book = json_decode($request->getBody());

	$book_id = pg_escape_string($book->id);
	$book_name = pg_escape_string($book->name);
	$book_author = pg_escape_string($book->author);
	$book_read_status = $book->read_status;
	
	if($book_read_status == null || $book_read_status == '') {
		$book_read_status = 'f';
	}
	else {
		$book_read_status = pg_escape_string($book->read_status);
	}
	
	
	
	$query = "UPDATE book SET name='$book_name', author='$book_author', read_status='$book_read_status' where id='$book_id'";
		
	$result = pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
	pg_close($dbconn); 
});

/* Delete a Book*/
$app->delete('/books/:id', function ($id) use ($app, $dbconn) {
	
	$query = "DELETE from book where id='$id'";
	$result = pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
	pg_close($dbconn); 

});


$app->run();

?>
