<?php
// @author: C.A.D. BONDJE DOUE
// @filename: create_document.php
// @date: 20260127 10:29:05
// @desc: create a simple rtf document
// @command: balafon --run .test/modules/igk_windows_rtf/create_document.php
use IGK\System\Console\Logger;
use igk\Windows\Rtf\Converter\MarkdownToRtf;
use igk\Windows\Rtf\RtfDocument;
use igk\Windows\Rtf\RtfConstants;
use igk\Windows\Rtf\RtfFonts;
use igk\Windows\Rtf\RftViewKinds;
use igk\Windows\Rtf\RtfBulletNFCTypes;
use igk\Windows\Rtf\RtfTable;

/*
## Table des matiÃ¨res
1. [ Introduction aux formulaires](#91---introduction-aux-formulaires)
*/
$src = <<<'MD'
# Chapitre 1: Introduction 
## I. Architecture gÃ©nÃ©rale
### a. Core
### b. Projects
## II. Avec
### a. Modules
### e. Sample
# Chapitre 2: Corps 
## I. basic
### I.a. La vie
### I.c. est Belle
MD;
echo MarkdownToRtf::convert($src);
igk_exit(); 
$doc = new RtfDocument;
$doc->fonts = [
    RtfFonts::CourierNew,
    '\\fswiss\\fcharset0 Helvetica',
    '\\fcharset0 Consolas',
    '\\fcharset0 Menlo-Regular',
];
$doc->colors =[
    '#000',
    '#ff0',
    '#f0f',
    '#080'
    ];
    $doc->setMarginMm(15, 2, 15, 5);
$doc->setFont(0);
$doc->setFontSize(24);
$doc->setTextColor(3);
$doc->setBackgroundColor(-1);
$doc->setViewKind(40, 15, RftViewKinds::Draft);
$doc->listOverride('\\ls1', "\\listid1", "\\'00.", RtfBulletNFCTypes::Decimal, 152, 2, 1);
$doc->listOverride('\\ls2', "\\listid2", "\\uc0\\u8225");
$doc->setHeader("Balafon 2026");
$doc->setFirstLineIndent(2);
$doc->setLineIndent(3); 
$doc->list('information 1', '1.');
$doc->list('information 2', RtfConstants::PUCE_SQARE, null, "\\ls1");
$tb = new RtfTable;
$tb->setCell(0,[
    [
        40, [['solid', 30], ['dash', 15], ['dashdot', 15, 2], ['dot', 20]]
    ],
    [
        60, [['solid', 15, 4]]
    ],
]); 
$tb->rows[] = ["one","two"];
$doc->table($tb);
$doc->line('Present link ');
$doc->linkto('https://igkdev.com', "\\ul IGKDEV\\ul0  camp");
$doc->linktoBookmark('ch_3', " Allez au chapitre 3");
$doc->line("\n");
$doc->clearPar();
$doc->setAlign('c');
$doc->image(__DIR__.'/pics.png');
$doc->clearPar();
$doc->page();
$doc->page();
$doc->page();
$doc->page();
$doc->bookmark('ch_3', "");
$doc->setFont(2);
$doc->line("ICI commence le chapitre 3");
echo $doc->render();
Logger::success('done');
igk_exit();