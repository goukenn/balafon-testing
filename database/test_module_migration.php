<?php
// balafon --run .test/database/test_module_migration.php
use IGK\Models\Users;
use IGK\System\Console\Logger;
use IGK\System\Database\MySQL\DataAdapter;
$driver = Users::model()->getDataAdapter();
if ($driver instanceof DataAdapter){
    if ($driver->connect()){
        $table = Users::table();
        $driver->drop_column($table, 'fb_user_id');
        $driver->drop_column($table, 'google_user_id');
        $driver->drop_column($table, 'provider');
        $driver->close();
    }
}
// Logger::info(  "load modules .... ");
// // $modlist = IGKModuleListMigration::InitModuleList();
// Logger::info( "inject modules ");
IGKModuleListMigration::Migrate();