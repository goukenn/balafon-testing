<?php
// @command: balafon --run .test/db/phonebook/clean_email.php
use IGK\Database\Constants\PhoneBookTypeNames;
use IGK\Helper\JSon;
use IGK\Models\PhoneBooks;
use IGK\Models\PhoneBookTypes;
$r = PhoneBooks::select_all([
    PhoneBooks::FD_TYPE=>PhoneBookTypes::GetCache(PhoneBookTypes::FD_NAME, PhoneBookTypeNames::PHT_EMAIL)
]);
foreach($r as $t){
    if (!IGKValidator::IsEmail($t->{PhoneBooks::FD_VALUE})){
        $t->delete();
        // $t->{PhoneBooks::FD_VALUE} = '';
        // $t->save();
    }
}
echo JSon::Encode($r);
exit;