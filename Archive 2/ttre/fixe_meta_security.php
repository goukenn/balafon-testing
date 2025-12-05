<?php
// fixe meta script 
$query = "select meta_id, meta_value from wp_2023_postmeta WHERE meta_value LIKE '%http://fixtech.themetechmount.com%';";
$ad = igk_get_data_adapter(IGK_MYSQL_DATAADAPTER);
if (!$ad->connect()){
    echo "failed to connect";
    exit;
}
$result = $ad->sendQuery($query);
$c = 0;
foreach($result->getRows() as $row){
    $row->meta_value = str_replace('http://fixtech.themetechmount.com', 'https://fixtech.themetechmount.com', $row->meta_value );
    $ad->sendQuery(sprintf("UPDATE wp_2023_postmeta SET meta_value='%s' WHERE meta_id=%s", $row->meta_value, $row->meta_id));
    $c++;
}
$ad->close();
echo "update : ".$c;