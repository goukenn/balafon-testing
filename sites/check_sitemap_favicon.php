<?php
// @command: balafon --run .test/sitenotebook/favicon.php
// @desc: get site map 
use IGK\System\Console\Commands\SitemapGeneratorCommand;

$s = igk_curl_post_uri('https://balafon.local.com:7300/sitemap');
echo "handling site map : \n";
echo $s."\n";
exit;