<?php
// @command: balafon --run .test/ai/transform-non-encoding-files.php [input] [output]
use IGK\Helper\IO;
use IGK\System\Console\App;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
use IGK\System\IO\StringBuilder;
// + | --------------------------------------------------------------------
// + | retrouver des code qui ont produit des symbole invalide: 
// + | copier le code -> fichier.txt enregister au format window-1252
// + | reouvrir en utilisant le code utf-8

(php_sapi_name() != 'cli') && igk_die('missing context');
if (property_exists($command->options, '--help')) {
    Logger::info('Transform IA .md files encoding files - invalid character');
    Logger::info(implode("\n", [
        App::Gets(App::BLUE_B, 'Usage:'),
        'input_file [output_file] [options]'
    ]));
    igk_exit();
}
$file = igk_getv($params, 0) ?? igk_die('missing require file');
$output = igk_getv($params, 1) ?? $file;
 $transform = [
    'âœ“'=>'✓',
'NÃ…â€œud' => 'Noeud',
'nÃ…â€œud' => 'noeud',
'oÃ¹' => 'où',
'Ã ' => 'à',
'Ã ' => 'à',
'Ã¢' => 'â',
'Ã§' => 'ç',
'Ã¨' => 'è',
'Ã©' => 'é',
'Ãª' => 'ê',
'Ã®' => 'î',
'Ã´' => 'ô',
'Ã¹' => 'ù',
'Ã»' => 'û',
'Ã¿' => 'ÿ',
'ÃŠ' => 'Ê',
'ÃƒÂ ' => 'à',
'ÃƒÂ¢' => 'â',
'ÃƒÂ¨' => 'è',
'ÃƒÂ©' => 'é',
'ÃƒÂª' => 'ê',
'ÃƒÂ®' => 'î',
'ÃƒÂ´' => 'ô',
'ÃƒÂ¹' => 'ù',
'Ãƒâ€°' => 'É',
'Ãˆ' => 'È',
'Ã—' => '×',
'Ã‰' => 'É',
'Ã€' => 'À',
'Å“' => 'oe',
'â""' => '└',
'â"' => '┐',
'â"¬' => '┬',
'â"Œ' => '┌',
'â"˜' => '┘',
'â"‚' => '│',
'â"€' => '─',
'âŒ' => '❌',
'âŒ' => '❌',
'âœ¨' => '✨',
'âœ—' => '❌',
'âœ…' => '✅',
'âš ï¸' => '⚠️',
'âš ï¸�' => '⚠️',
'âš™ï¸�' => '⚙️',
'â–¼' => '▼',
'â”' => '┐',
'â”¤' => '┤',
'â”¬' => '┬',
'â”Œ' => '┌',
'â”œ' => '├',
'â”˜' => '┘',
'â”‚' => '│',
'â””' => '└',
'â”€' => '─',
'â†' => '←',
'â†’' => '→',
'â†“' => '↓',
'â€¢' => '•',
'â�Œ' => '❌',
'ðŸ§ª' => '🧪',
'ðŸ§‘â€�ðŸ’»' => '🧑‍💻',
'ðŸš€' => '🚀',
'ðŸŽ¨' => '🎨',
'ðŸŽ¯' => '🎯',
'ðŸŽ‰' => '🎉',
'ðŸ˜„' => '😄',
'ðŸ‘¥' => '👥',
'ðŸ‘¨â€�ðŸŽ“' => '👨‍🎓',
'ðŸ‘¶' => '👶',
'ðŸ‘‰' => '👉',
'ðŸ’¡' => '💡',
'ðŸ’°' => '💰',
'ðŸ“¦' => '📦',
'ðŸ“Š' => '📊',
'ðŸ“š' => '📚',
'ðŸ“ˆ' => '📈',
'ðŸ“–' => '📖',
'ðŸ“„' => '📄',
'ðŸ“�' => '📝',
'ðŸ”§' => '🔧',
'ðŸ”—' => '🔗',
'ðŸ”�' => '🔍',
'ðŸ› ï¸�' => '🛠️',
'ðŸ�—ï¸�' => '🏗️',
];
/**
* auto generate doc.
* @param mixed $transform
* @param string $file
* @return mixed
*/
function saveToFile($transform, string $file='/tmp/data.php'){
ksort($transform);
$sb = new StringBuilder;
$sb->appendLine('[');
$c = 0;
foreach($transform as $k=>$v){
    $sb->appendLine("'".$k."' => '".$v."',");
    echo $c, PHP_EOL;
    $c++;
}
$sb->appendLine(']');
$sc = ''.$sb;
igk_io_w2file('/tmp/data.php', '<?php '."\n". $sc.";");
echo $sc; 
igk_exit();
} 
$dir = false;
if (is_dir($file)) {
    $ext = igk_getv($command->options, '--ext', 'md');
    if (is_array($ext)) {
        $ext = implode('|', array_filter($ext));
    }
    $files = IO::GetFiles($file, "/\.(" . $ext . ")/");
    $dir = true;
    if ($file != $output){
        if(file_exists($output) && !is_dir($output)){
            $output = dirname($output);
        }
    }
} else
    $files = [$file];
while (count($files) > 0) {
    $file = array_shift($files);
    Logger::info('treat: ' . $file);
    $src = file_get_contents($file);
    $r = strtr($src, $transform);
    if (!$dir)
        igk_io_w2file($output, $r);
    else {
        $s = basename($file);
        $o = Path::Combine($output, $s);
        igk_io_w2file($o, $r);
        Logger::success('store: ' . $o);
    }
}
igk_exit();