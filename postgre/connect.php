<?php
// @command: balafon --run .test/postgre/connect.php

$con = pg_connect('host=0.0.0.0 port=5432 user=postgres');
igk_wln_e("the connection: ", $con);