<?php
	/*FIRST PARTY IMPORTS*/
	/*THIRD PARTY IMPORTS*/
	/*DEVELOOPER IMPORTS*/
	if (!defined('__ROOT__'))
		define('__ROOT__', dirname(dirname(__FILE__)));
	
	require_once(__ROOT__.'/globals.php');
	require_once(__ROOT__.'/lib.php');
	
	global $TABLE_FIELD_PAIRS;
	if (isset($_GET)) {
		
		if (isset($_GET['table'])) {
			$table = $_GET['table'];
			$fields = $TABLE_FIELD_PAIRS[$table];
			$results = select_all($table, $fields);
			if ($table == 'books') {
				$authors = select_all('authors', 'author_id, name');
				$index = 0;
				foreach ($results as $book) {
					foreach ($authors as $author) {
						if ($author['author_id'] == $book['author_id'])
							$results[$index]['author'] = $author['name'];
							unset($results[$index]['author_id']);
					}
					$index++;
				}
			}
			print(json_encode($results));
		}
	}
?>