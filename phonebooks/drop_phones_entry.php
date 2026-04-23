<?php
// @command: balafon --run .test/phonebooks/drop_phones_entry.php
use IGK\Models\PhoneBookEntries;
use IGK\Models\PhoneBooks;
use IGK\System\Console\Logger;

if ($id = igk_getv($params, 0)){
    $s = PhoneBooks::delete([
        PhoneBooks::FD_ENTRY_GUID=>$id
    ]);
     PhoneBookEntries::delete([
               PhoneBookEntries::FD_GUID=>$id
    ]);
    Logger::info('drop phonebook entries'. $s);
} else {
    igk_die('missing params');
}