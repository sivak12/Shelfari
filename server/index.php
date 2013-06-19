<?php 
require 'Slim/Slim.php';
use Slim\Slim;
\Slim\Slim::registerAutoloader();

$app = new Slim();


$dbconn = pg_connect("host=ec2-50-19-228-92.compute-1.amazonaws.com port=5432 dbname=d5kc1otcr9j94d user=mvopcsddhypxdm password=HSsKW55djPc2Zz5BT4enGxMU7P sslmode=require options='--client_encoding=UTF8'") or die('Could not connect: ' . pg_last_error());	

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


$app->post('/books', function () use ($app, $dbconn) {
	
	$request = Slim::getInstance()->request();
	$book = json_decode($request->getBody());

	$book_name = pg_escape_string($book->name);
	$book_author = pg_escape_string($book->author);
	$book_read_status = pg_escape_string($book->read_status);
	
	$query = "INSERT INTO book (name, author,read_status) VALUES('$book_name', '$book_author','$book_read_status' )";
	
	$result = pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
	pg_close($dbconn); 
});


$app->put('/books/:id', function ($id) use ($app, $dbconn) {
	
	$request = Slim::getInstance()->request();
	$book = json_decode($request->getBody());

	$book_id = pg_escape_string($book->id);
	$book_name = pg_escape_string($book->name);
	$book_author = pg_escape_string($book->author);
	$book_read_status = pg_escape_string($book->read_status);
	
	//$sql = "UPDATE ongoing SET field1='value', field2='value' ... WHERE id = 'id of project you want to edit'";
	
	$query = "UPDATE book SET name='$book_name', author='$book_author', read_status='$book_read_status' where id='$book_id'";
	//(name, author,read_status) VALUES('$book_name', '$book_author','$book_read_status' )";
	
	$result = pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
	pg_close($dbconn); 
});

$app->delete('/books/:id', function ($id) use ($app, $dbconn) {
	
	$query = "DELETE from book where id='$id'";
	$result = pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
	pg_close($dbconn); 

});


/*
function getBooks() {
	
	$dbconn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=admin") or die('Could not connect: ' . pg_last_error());
		
	$query = "select name,author,read_status from book order by id desc";
	
	$result = pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
	$books = pg_fetch_all($result);
	echo json_encode($books);
	pg_close($dbconn); 

}

function getBook($id) {

	$dbconn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=admin") or die('Could not connect: ' . pg_last_error());
		
	$query = "select name,author,read_status from book where id = '$id'";
	$result = pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
	$books = pg_fetch_all($result);
	echo json_encode($books);
	pg_close($dbconn); 
	

}

*/





/*
function addBook() {

	$request = Slim::getInstance()->request();
	$book = json_decode($request->getBody());

	$dbconn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=admin") or die('Could not connect: ' . pg_last_error());
	
	$book_name = pg_escape_string($book->name);
	$book_author = pg_escape_string($book->author);
	$book_read_status = pg_escape_string($book->read_status);
	
	$query = "INSERT INTO book (name, author,read_status) VALUES('$book_name', '$book_author','$book_read_status' )";
	
	$result = pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
	pg_close($dbconn); 
}
*/

$app->run();

?>
