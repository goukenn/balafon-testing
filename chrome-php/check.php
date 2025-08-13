<?php
 // @command: balafon --run .test/chrome-php/check.php
 // @author: C.A.D. BONDJE DOUE
 // @filename: check.php
 // @date: 20250630 07:03:12
 // @desc: test usage of chrome-php/chrome HeadlessChromium to capture screenshot/print a page
use HeadlessChromium\BrowserFactory;
use HeadlessChromium\Page;
use IGK\System\Console\Logger;
$browserFactory = new BrowserFactory;
$browser = $browserFactory->createBrowser([
     // for self signing security check 
    'ignoreCertificateErrors'=>true
]);
if ($page = $browser->createPage()){
    $navigate = $page->navigate('https://local.com:7800');
    $navigate->waitForNavigation(Page::DOM_CONTENT_LOADED,30000);
    $page->screenshot()->saveToFile('/tmp/screenshot.png');
}
$browser->close();
Logger::danger('done');