<?php
use igk\pdflib\PDFUtils;
use igk\pdflib\System\IO\PDFFile;
use IGK\System\Console\Colorize;
use IGK\System\Console\Logger;
use IGK\System\Text\RegexMatcherContainer;

$pdflib = igk_require_module('igk/pdflib');
$file = igk_getv($params, 0);
$ctn = new RegexMatcherContainer;
$i = $ctn->begin("\(", "\)")->last();
$i->patterns = [
    (object)[
        'match'=>'\\\\.'
    ]
];
$src = "information (du jour ) ... "; 
$offset = 0;
$s =''; 
while($g = $ctn->detect($src, $offset)){
    if ($e = $ctn->end($g, $src, $offset)){
       $s.= trim($e->value, ')(');
    }
}
igk_wln_e("read: ", $s);
$pdf = PDFFile::Open($file);
Logger::SetColorizer(new Colorize);
echo $pdflib, PHP_EOL;
Logger::print("done 12.0f");
igk_exit();