<?php
// @command: balafon --run .test/sitenotebook/favicon.php
// @desc: get site map 
use IGK\System\Console\Commands\SitemapGeneratorCommand;
// $indexes = SitemapGeneratorCommand::GetProjectIndexes();
// $s = SitemapGeneratorCommand::GenerateSiteMapIndex($indexes, igk_io_baseuri()); 
// check that the site support favicon
// $s = igk_curl_post_uri('https://balafon.local.com:7300/favicon.ico');
// check that the site support sitemap
$s = igk_curl_post_uri('https://balafon.local.com:7300/sitemap');
echo "handling site map : \n";
echo $s."\n";
exit;