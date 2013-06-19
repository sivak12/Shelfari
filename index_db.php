<?php 



$dbconn = pg_connect("host=ec2-50-19-228-92.compute-1.amazonaws.com port=5432 dbname=d5kc1otcr9j94d user=mvopcsddhypxdm password=HSsKW55djPc2Zz5BT4enGxMU7P sslmode=require options='--client_encoding=UTF8'") or die('Could not connect: ' . pg_last_error());
	
	
	$query = "CREATE TABLE book(
	id SERIAL PRIMARY KEY, 
	name TEXT, 
	author TEXT,
	read_status BOOLEAN)";
	
	pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
	
	$query = "INSERT INTO book (name,author,read_status)
	VALUES('Harry Potter','J K Rowling',FALSE)"; 
	pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
	$query = "INSERT INTO book (name,author,read_status)
	VALUES('LOTR','J R R Tolkien',FALSE)"; 
	pg_query($dbconn, $query) or die("Cannot execute query: $query\n"); 
	
	pg_close($dbconn); 


?>
