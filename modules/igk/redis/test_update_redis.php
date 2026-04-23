<?php
// @author: C.A.D. BONDJE DOUE
// @filename: test_update_redis.php
// @date: 20260321 17:17:24
// @desc:  
// @command: balafon --run .test/modules/igk/redis/test_update_redis.php
use IGK\Database\DbExpression;
use IGK\Helper\StringUtility;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;

igk_redis_update_from_server(igk_get_module('igk.redis'));
igk_wln_e('done');