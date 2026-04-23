<?php

$ctrl = bantubeatController::ctrl();
$ctrl::register_autoload();
igk_server()->ENVIRONMENT  = 'development';
igk_environment()->querydebug = 1;
igk_debug(1);
$ctrl::resetDb(false, true);
$model = $ctrl->model("Users");
$grammar = $model->getDataAdapter()->getGrammar(); 
$query = $grammar->createTablequery($model->table() , $model->getTableColumnInfo());
igk_wln_e("done", $query);