<?php
use IGK\Database\SchemaBuilder\DiagramEntityAssociation;
use igk\drawio\SchemaBuilder\DiagramDrawIoSchemaVisitor;
igk_require_module(\igk\drawio::class);
$ctrl = TtreController::ctrl();
$ctrl->register_autoload();
$file = $ctrl::getDataSchemaFile();
$schema = igk_db_load_data_schemas($file, $ctrl, true);
$m = DiagramEntityAssociation::LoadFromXMLSchema($schema);
$r = $m->render(new DiagramDrawIoSchemaVisitor); 
echo  $r . PHP_EOL;
exit;