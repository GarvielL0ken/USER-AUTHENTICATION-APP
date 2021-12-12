<?php
	$RGX_EMAIL = '^[a-zA-Z0-9]+@[a-zA-Z]+.[a-zA-Z]+$';
	$RGX_PASSWD = '^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#\$%\^&\*])(?=.*[0-9])(?=.{8,32})$';
	$RGX_USERNAME = '^(?=.{5,32}$)[a-zA-Z0-9._]+$';
	$RGX_PATTERNS = ['alpha' => '^[a-zA-Z ]+$',
						'numeric' => '^[\d]+$',
						'genres' => '^[a-zA-Z, ]+$'];
	$TABLE_FIELD_PAIRS = ['authors' => 'name, age, genres',
							'books' => 'title',
							'genres' => 'genre',
							'users' => 'username, role'];
?>