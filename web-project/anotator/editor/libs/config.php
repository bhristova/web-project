<?php

$vhosts =  <<<EOT
#############################
## MP-1.10.1: w11ref.w3c.fmi.uni-sofia.bg
#############################
<VirtualHost *:80>
	ServerAdmin    bbiserova@uni-sofia.bg
	DocumentRoot   "C:\xampp\htdocs\anotator\"
	DirectoryIndex homepage.php
</VirtualHost>
EOT;

	return (object) array(
		'DB_SERVERNAME' => '127.0.0.1',
		'DB_USERNAME' => 'root', 
		'DB_PASSWORD' => '', 
		'DB_NAME' => 'anotator', 
	);
?>
