<?php
///<summary></summary>
use IGK\Models\ModelBase;
/**
* 
* @author C.A.D. BONDJE DOUE
* @method static void lastYearReport() macros function
* @method static int register() macros function
* @method static void registerLastYearReport() macros function
* */
abstract class IRReportsMacros extends ModelBase{
}
class JO extends IRReportsMacros{
}
$m = new JO;