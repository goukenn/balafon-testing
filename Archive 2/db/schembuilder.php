<?php
use IGK\System\Database\SchemaMigrationBuilder;
$node = igk_create_xmlnode('data-schemas');
$builder = SchemaMigrationBuilder::Create($node->add('Migrations'), []);
$builder->addTable('locations', 'store file migration', ['prefix'=>'loc_'])
->column('id')
->id()->autoincrement()
->column('text')->text()
->column('desc')->text()
->updateTime();
$builder->addTable('posts')
->ref('id')
->column('title')->varchar(255)->notnull()
->updateTime(); 
$builder->addColumn('posts', ['clName'=>'after', 'clNotNull'=>true]);
$builder->addIndex('posts', 'after');
$builder->removeColumn('posts', 'after');
$node->renderAJX((object)['Indent'=>true]);
igk_exit();