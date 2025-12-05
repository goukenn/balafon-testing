<?php
use com\igkdev\bantubeat\Models\NewsFeeds;
use IGK\Helper\JSon;
bantubeatController::ctrl()->register_autoload();
igk_wln_e("data", JSon::Encode((object)["data"=>NewsFeeds::select_all(
    [
        NewsFeeds::FD_NWFPUBLIC=>1
    ]
    )]));