<?php
use igk\devtools\ImageLoader;
use IGK\Helper\IO;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
use IGK\System\Uri;
igk_require_module(igk\devtools::class);
$loader = new ImageLoader;
$loader->outdir = "/tmp/pictures";
// IO::CleanDir($loader->outdir);
$base_uri = 'https://www.autoscout24.be/fr/professional/european-bizness-cars-sa';
$base_uri = 'https://www.autoscout24.be/fr/offres/volvo-s40-2-0d-136-kinetic-diesel-noir-b93bb22e-9a36-4b5c-95e9-65ef155bce6f?source=dealerpage_stock-list';
if ($content = igk_curl_post_uri($base_uri)){
    $loader->loadContent($content);  
}
exit;