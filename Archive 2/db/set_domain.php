<?php
use \IGK\Models\Subdomains as sub;
list($domain) = $params;
sub::insertIfNotExists([
    sub::FD_CL_NAME=>$domain,
    sub::FD_CL_CTRL=>$ctrl->getName()
]);