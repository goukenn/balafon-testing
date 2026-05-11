<?php
// + | --------------------------------------------------------------------
// + | 
// + |
// @command: balafon --run .test/balafon-wiki/claude/merge-docx-document.php
use IGK\Helper\IO;
/**
* auto generate doc.
* @param array $files
* @param string $output
* @return void
*/
function mergeDocxManual(array $files, string $output) {
    $zip = new ZipArchive();
    $mainContent = '';
    foreach ($files as $index => $file) {
        $docx = new ZipArchive();
        if ($docx->open($file) === true) {
            $content = $docx->getFromName('word/document.xml');
            if ($index === 0) {
                $mainContent = $content;
            } else {
                preg_match('/<w:body>(.*)<\/w:body>/s', $content, $matches);
                if (isset($matches[1])) {
                    $mainContent = str_replace(
                        '</w:body>',
                        $matches[1] . '</w:body>',
                        $mainContent
                    );
                }
            }
            $docx->close();
        }
    }
    @unlink($output);
    copy($files[0], $output);
    $outputZip = new ZipArchive();
    if ($outputZip->open($output) === true) {
        $outputZip->addFromString('word/document.xml', $mainContent);
        $outputZip->close();
    }
}
$count = 0;
$out = igk_getv($params, 0) ?? __DIR__.'/output/merged.docx';
IO::CreateDir(dirname($out));
mergeDocxManual(
IO::GetFiles(__DIR__, '/\.docx$/', false), $out);
igk_wln_e("output: ".$out);