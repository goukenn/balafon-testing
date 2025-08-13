<?php
use com\igkdev\projects\CarRental\Models\CarProposedPictures;
use IGK\System\Console\Logger;
use IGK\System\IO\Path;
$ctrl = CarRentalController::ctrl();
$ctrl->register_autoload();
CarProposedPictures::update([CarProposedPictures::FD_CRCAR_PPICTURE_THUMBNAIL => null]);
list ($w, $h) = $params ;
$rf = $ctrl->model(\CarProposedPictures::class)->select_all([
    CarProposedPictures::FD_CRCAR_PPICTURE_THUMBNAIL => null
]);
$d_dir = $ctrl->getDataDir();
$count = 0;
if ($rf) {
    foreach ($rf as $r) {
        $path = $r->{CarProposedPictures::FD_CRCAR_PPICTURE_VALUE};     
        $ext = igk_io_path_ext($path);
        $file = Path::Combine($d_dir , $path);
        if (file_exists($file)){
            $hash = '/thumbs/' . sha1($path).".".$ext;
            $src = file_get_contents($file);
            $g = igk_gd_resize_proportional($src, $w, $h, 1, 0, true);
            igk_io_w2file($d_dir .$hash, $g);
            $r->{CarProposedPictures::FD_CRCAR_PPICTURE_THUMBNAIL} = $hash;
            $r->save();
            $count++;
        }
    }
}
Logger::success('done : '.$count);
igk_exit();