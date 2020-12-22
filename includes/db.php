<?php

$db['db_host'] = "localhost";

$db['db_user'] = "ak2432";

$db['db_pass'] = "dTYww50HqLsVDLbE";

$db['db_data'] = "ak2432";


foreach ($db as $key => $value) {

  define(strtoupper($key), $value);
}

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DATA);