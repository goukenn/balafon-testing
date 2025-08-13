<?php
// @command: balafon --run .test/swagger/load_configs.php
use igk\docs\swagger\SwaggerConfiguration;
use igk\docs\swagger\SwaggerGenerator;
use IGK\System\Console\Colorize;
use IGK\System\Console\Logger;
$ctrl = ForemJobDashboardController::ctrl(true);
$config = SwaggerConfiguration::LoadSwaggerConfigurationFromProject($ctrl);
Logger::SetColorizer(new Colorize);
print_r($config);
SwaggerGenerator::GenerateSwagger($ctrl, []);
Logger::print('done');
igk_exit();