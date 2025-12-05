<?php
use IGK\Helper\StringUtility;
use IGK\System\Html\Dom\HtmlDocumentNode;
use IGK\System\Html\Metadatas\AppleTouchIconMetadataDefinition;
$doc = IGKHtmlDoc::CreateDocument('dummy-document');
// igk_wln_e( StringUtility::GetSnakeKebab('dummyDocument_Aample', true));
$doc->metadatas->bind([
    // "title"=>"No Ways",
    // "description"=>"Binding Metas",
    // "keywords"=>"Demos",
    "ogTitle"=>"Basic title content ..... ---------------------",
    "ogImage"=>"Sangoku",
    "applicationName"=>"Demo APP.",
    "icons"=>"Balsicsfs", // (object)['apple'=>'http//apple', 'icon'=>'the cions'],
    'twitterCard'=>'large_image',
    'archives'=>'my_archive',
    'assets'=>'https://example.com/assets',
    'alIOSAppId'=>'33343344',
    'appleItunesApp'=>"app-id=123AZE123",
    'formatDetection'=>[
        'telephone'=>false,
        'date'=>true,
        'url'=>true,
        'email'=>false,
        'url'=>false
    ],
    'appleWebAppCapable'=>'yes',
    'appleWebAppTitle'=>'*** apple web app appplication ****',
    'appleWebAppStartupImage'=>[
        ['sizes'=>'255x155','href'=>'@startimage', 'media'=>AppleTouchIconMetadataDefinition::MEDIA_IPAD_LANSCAPE],
        ['sizes'=>'255x155','href'=>'@startimage', 'media'=>'(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)'],
    ]
]);
$doc->renderAJX();
igk_wln_e('');