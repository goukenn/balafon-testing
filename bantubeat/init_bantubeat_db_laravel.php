<?php
// balafon --run .test/bantubeat/init_bantubeat_db_laravel.php > src/application/Projects/bantubeat/Laravel/src/database/migrations/2023_02_28_091309_init_bantubeatcontroller_db.php
use IGK\Database\DbColumnInfo;
use IGK\Helper\Database;
use IGK\Helper\StringUtility;
use IGK\System\IO\File\PHPScriptBuilder;
use IGK\System\IO\StringBuilder;
$ctrl = bantubeatController::ctrl();
$ctrl->register_autoload();
ob_start();
$schema = $ctrl->loadDataFromSchemas();
$builder = new PHPScriptBuilder;
$builder
->name(StringUtility::CamelClassName("Init_".$ctrl->getName()."Db"))
->extends(\Illuminate\Database\Migrations\Migration::class)
->uses([
    \Illuminate\Database\Schema\Blueprint::class,
    \Illuminate\Support\Facades\Schema::class
])
->type("class");
$def = new StringBuilder();
/**
 * 
 * @param mixed $def 
 * @param DbColumnInfo $cinfo 
 * @return void 
 */
function laravel_blue_print_bind($def, $cinfo){
    if ($cinfo->clAutoIncrement){
        $def->append("->autoIncrement(true)");
    }
    if ($cinfo->clNotNull){
        $def->append("->nullable(false)");
    }
    if ($cinfo->clIsIndex){
        $def->append("->index()");
    }
}
$rollback = new StringBuilder;
// laravel core database 
$exclude_table = ["personal_access_tokens", "activities_log", "migrations", 'password_resets'];
$def->appendLine("public function up(){");
    foreach ($schema->tables as $table => $info) {
        if ($info->controller != $ctrl){
            echo "skip : ".$info->defTableName . PHP_EOL;
            continue;
        }
        $ktable = Database::GetCleanTableName($info->defTableName);
        if (in_array($ktable, $exclude_table)){
            continue;
        }
        $table = $ktable;
        $rollback->appendLine("Schema::dropIfExists('{$table}');");
        $def->appendLine("Schema::create('{$table}', function (Blueprint \$table) {");
        foreach($info->columnInfo as $cinfo){
            if ($cinfo->clIsPrimary && $cinfo->clAutoIncrement){
                $def->append("\$table->id('{$cinfo->clName}')"); 
                laravel_blue_print_bind($def, $cinfo);
                $def->appendLine(";");
                continue;
            }
            switch(strtolower($cinfo->clType)){
                case 'int':
                    $def->append("\$table->integer('{$cinfo->clName}')");
                    break;
                case 'float': 
                    $def->append("\$table->float('{$cinfo->clName}')");
                    break;
                case 'datetime':
                    $def->append("\$table->dateTime('{$cinfo->clName}')");
                    break;
                case 'timestamp':
                    $def->append("\$table->timestamp('{$cinfo->clName}')");
                break;
                case 'varchar': 
                     $def->append("\$table->string('{$cinfo->clName}', {$cinfo->clTypeLength})");
                    break;
                default:
                $def->append("\$table->string('{$cinfo->clName}')");
                break;
            }
            laravel_blue_print_bind($def, $cinfo);
            $def->appendLine(";");
        }
            // $table->id();
            // $table->string('name');
            // $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            // $table->string('password');
            // $table->rememberToken();
            // $table->timestamps();
        $def->appendLine("});");
    }
$def->appendLine("}"); 
$def->appendLine("public function down(){");
$def->appendLine($rollback."");
$def->appendLine("}"); 
$builder->defs($def."");
ob_end_clean(); 
echo $builder->render();
exit;