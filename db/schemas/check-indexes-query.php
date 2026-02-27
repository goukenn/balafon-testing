<?php
// @author: C.A.D. BONDJE DOUE
// @filename: check-indexes-query.php
// @date: 20251204 20:14:40
// @desc: load indexes
// @command: balafon --run .test/db/schemas/check-indexes-query.php
use IGK\Database\DbSchemas;
use IGK\System\Console\Html\HtmlColorizer;
use IGK\System\Console\Logger;
use IGK\System\Html\XML\XmlNode;
$data = <<<XML
<data-schemas author="C.A.D. BONDJE DOUE" createAt="2025-12-03" version="1.0"> 
<DataDefinition TableName="%prefix%grades" Prefix="grd_" Description="Save data">
    <Column clAutoIncrement="true" clIsUnique="true" clName="id" clNotNull="true" clType="Int(3)"/>   
    <Column clName="max_score" clType="decimal(5,2)" clDefaultValue="20.00" clNotNull="true" clDescription="Note maximale possible" /> 
    <Column clName="date_ord" clType="Datetime" />
    <Index name='index_name' columns='max_score, date_ord' />
</DataDefinition>
</data-schemas>
XML;
$xml = new XmlNode();
$xml->load($data);
$g = DbSchemas::GetDefinition($xml, $ctrl);
$table = igk_conf_get($g, 'tables/tbigk_grades');
$ad = igk_get_data_adapter('MYSQL');
$grammar = $ad->getGrammar();
$query = $grammar->createTableQuery('tbigk_grades', igk_getv($table, 'columnInfo'),[
    'description'=>$table->description,
    'indexes'=>$table->indexes,
    'prefix'=>$table->prefix
]);  
Logger::SetColorizer(new HtmlColorizer);
Logger::print(json_encode(compact('query'), JSON_PRETTY_PRINT));
Logger::success('done');
igk_exit();