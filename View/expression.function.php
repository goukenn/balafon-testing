<?php
// @file: configuration.page.phtml
// @author: C.A.D. BONDJE DOUE
// @description:
// @copyright: igkdev © 2020
// @license: Microsoft MIT License. For more information read license.txt
// @company: IGKDEV
// @mail: bondje.doue@igkdev.com
// @url: https://www.igkdev.com
use IGK\System\Configuration\Controllers\ConfigureLayout;
use function igk_resources_gets as __;

$layout = new ConfigureLayout($ctrl);
$confframe = $ctrl->getConfigFrame(); 
$t->div()->add($confframe); 