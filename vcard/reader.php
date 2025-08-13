<?php
// @desc: vcard reader operation 
// @command: balafon --run .test/vcard/reader.php
use IGK\Database\PhoneBooks\PhoneConverter;
use IGK\Database\PhoneBookUtility;
use IGK\Helper\Activator;
use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use IGK\Models\PhoneBookEntries;
use IGK\Models\PhoneBooks;
use IGK\Models\PhoneBookTypes;
use IGK\Models\Users;
use IGK\PhoneBook\PhoneBookEntry;
use IGK\System\Console\Logger;
use IGK\System\Constants\PhonebookTypeNames;
use IGK\System\IO\StringBuilder;
use IGK\System\IO\VCF\VCard;
// + | create entry in select row - 
// $c = new PhoneConverter;
// $r = $c->treat('07544555');
// echo $r;
// exit;
// $cards = PhoneBookUtility::ExportVCards(igk_get_user_bylogin('cbondje@igkdev.com')); 
// $ls = igk_getv(array_values($cards), 9)->getPreferred('email');
// exit;
// usort($cards, function($a, $b){
//     return $a->FN <=> $b->FN;
// });
// VCard::Save('/tmp/card.vcf', $cards);
// igk_wln_e(JSon::Encode($cards, JSonEncodeOption::IgnoreEmpty(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
// exit;
/** 
saving - phone books entry 
PhoneBookUtility::LoadEntryData([
    "firstname"=>'Charles A.D.',
    "lastname"=>'BONDJE DOUE',
    "title"=>'IT INGENIER',
    'birthday'=>'1983-08-04',
    'rrn'=>'',
    'email'=>['cbondje@igkdev.com', 'bondje.doue@gmail.com']
], $user);
**/
$user = null;
$for = igk_getv($command->options, '--u');
if ($for){
    $user = igk_get_user_bylogin($for) ?? igk_die('missing user');
}
Logger::info('drop non user book entry');
PhoneBookUtility::DeleteAllBookEntry();
if ($user){
    PhoneBookUtility::DeleteAllBookEntry($user);
}
($file = igk_getv($params, 0)) || igk_die('required file');
Logger::info('open vcard...');
$cards = VCard::OpenFile($file);
if ($to = igk_getv($command->options, '--o')) {
    VCard::Save($to, $cards);
}
Logger::info('import vcard...');
PhoneBookUtility::ImportVCards($cards, $user);
Logger::info('read card infos');
echo json_encode([
    'VERSION' => 1,
    'count' => count($cards)
], JSON_PRETTY_PRINT);
Logger::success('done');
igk_exit();