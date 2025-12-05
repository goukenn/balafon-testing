<?php
// fixe post_ script 
$query = "select ID, post_content from wp_2023_posts WHERE post_content LIKE '%http://%';";
$ad = igk_get_data_adapter(IGK_MYSQL_DATAADAPTER);
if (!$ad->connect()){
    echo "failed to connect";
    exit;
}
$result = $ad->sendQuery($query);
$c = 0;
foreach($result->getRows() as $row){
    $row->post_content = str_replace('http://', 'https://', $row->post_content );
    $ad->sendQuery(sprintf("UPDATE wp_2023_posts SET post_content='%s' WHERE ID=%s", $ad->escape_string($row->post_content), $row->ID));
    $c++;
}
$ad->close();
echo "update posts : ".$c;