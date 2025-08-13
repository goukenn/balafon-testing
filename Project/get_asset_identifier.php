<?php
/**
 * @var \IGK\Controllers\BaseController $ctrl environment controller 
 */
use IGK\System\IO\Path;
use IGK\System\IO\ResIdentifierConstants;
$r = 'src/'.$ctrl->asset('/js/main.js', false);
function project_asset_identifier(string $path, ?string & $new_path=null){
    $g = Path::Combine(IGK_RES_FOLDER, ResIdentifierConstants::PROJECT)."/";
    if (strstr($path,  $g)){
        $ln =  strlen(IGK_RES_FOLDER);
        $pos = strpos($path, $g) + strlen($g);
        $a_pos = strpos($path, IGK_RES_FOLDER, $pos);
        $v_n = substr($path, $pos, $a_pos-$pos + $ln);
        $id = sha1($v_n);
        $new_path =  substr($path,0, $pos).$id.substr($path, $a_pos+$ln);
        return $id;
    }
}
$v_project_asset_identifier = project_asset_identifier($r , $newpath);
// igk_wln_e($r, $v_project_asset_identifier, $newpath);
igk_wln_e("resolv Projection asset ", $ctrl->resolvProductionAssetPath());