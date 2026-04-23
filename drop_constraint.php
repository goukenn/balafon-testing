<?php
use IGK\System\Caches\DBCaches;

\IGK\Helper\Database::DropUniquesContraints($ctrl);
echo "done";
igk_exit();