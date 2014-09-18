<?php

## Database
## Database connection string

//Include config.php
include_once("ini.config.php");

$db = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
$db -> set_charset("utf8");