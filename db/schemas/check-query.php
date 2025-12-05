<?php

// @author: C.A.D. BONDJE DOUE
// @filename: check-query.php
// @date: 20251203 08:50:53
// @desc: check loading schema table query creation 
// @command: balafon --run .test/db/schemas/check-query.php

use IGK\Database\DbSchemas;
use IGK\System\Html\XML\XmlNode;
// $def = <<<XML
// <data-schemas author="C.A.D. BONDJE DOUE" createAt="2025-12-03" version="1.0"> 
// <DataDefinition TableName="%prefix%grades" Prefix="grd_">
//     <Column clAutoIncrement="true" clIsUnique="true" clName="id" clNotNull="true" />
//     <Column clName="student_id" clType="Int" clLinkType="%prefix%students" clLinkColumn="id" clNotNull="true" clDescription="Référence à l'élève" />
//     <Column clName="teacher_subject_id" clType="Int" clLinkType="%prefix%teacher_subjects" clLinkColumn="id" clNotNull="true" clDescription="Référence à l'affectation professeur-matière-classe" />
//     <Column clName="evaluation_period_id" clType="Int" clLinkType="%prefix%evaluation_periods" clLinkColumn="id" clNotNull="true" clDescription="Référence à la période d'évaluation" />
//     <Column clName="evaluation_type_id" clType="Int" clLinkType="%prefix%evaluation_types" clLinkColumn="id" clNotNull="true" clDescription="Type d'évaluation" />
//     <Column clName="score" clType="decimal(5,2)" clNotNull="true" clDescription="Note obtenue" />
//     <Column clName="max_score" clType="decimal(5,2)" clDefaultValue="20.00" clNotNull="true" clDescription="Note maximale possible" />
//     <Column clName="coefficient" clType="decimal(3,2)" clDefaultValue="1.00" clNotNull="true" clDescription="Coefficient de cette note spécifique" />
//     <Column clName="evaluation_date" clType="Date" clNotNull="true" clDescription="Date de l'évaluation" />
//     <Column clName="comments" clType="text" clDescription="Commentaires du professeur" />
//     <GenColumn name="updatetime" />
// </DataDefinition>
// </data-schemas>
// XML;

$def = <<<XML
<data-schemas author="C.A.D. BONDJE DOUE" createAt="2025-12-03" version="1.0"> 
<DataDefinition TableName="%prefix%grades" Prefix="grd_">
    <Column clAutoIncrement="true" clIsUnique="true" clName="id" clNotNull="true" clType="Int(3)"/>   
    <Column clName="max_score" clType="decimal(5,2)" clDefaultValue="20.00" clNotNull="true" clDescription="Note maximale possible" /> 
</DataDefinition>
</data-schemas>
XML;

$xml = new XmlNode();
$xml->load($def);

$g = DbSchemas::GetDefinition($xml, $ctrl);
$table = igk_conf_get($g, 'tables/tbigk_grades');
$column = igk_conf_get($table, 'columnInfo/grd_max_score');
$ad = igk_get_data_adapter('MYSQL');
$grammar = $ad->getGrammar();
$query = $grammar->createTableQuery('tbigk_grades', igk_getv($table, 'columnInfo')); //$ctrl::createTableQuery()

igk_wln_e($column, $query);

