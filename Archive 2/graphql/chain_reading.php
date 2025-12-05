<?php
// @command: balafon --run .test/graphql/chain_reading.php
use igk\io\GraphQl\GraphQlParser; 
use igk\io\GraphQl\Helper\GraphQlReaderUtils; 
use igk\io\GraphQl\GraphQlQueryOptions; 
use igk\io\GraphQl\IGraphQlInspector;
use igk\io\GraphQl\System\IO\GraphQlSectionReader;
use igk\io\GraphQl\System\IO\Traits\GraphQlSectionReaderTrait;
use IGK\System\Modules;
igk_require_module(Modules::igk_io_GraphQl());
igk_load_class(GraphQlSectionReader::class);
class NGraphQlReader extends GraphQlParser
{
    use GraphQlSectionReaderTrait;
    // protected function _n_update_last_property(&$o, ?GraphQlPropertyInfo $v_property_info, GraphQlReadSectionInfo $attach_to_section, $refData = null)
    // {
    //     if ($v_property_info) {
    //         $v_key = $v_property_info->getKey();
    //         $o[$v_key] = $refData ?? $v_property_info;
    //         $attach_to_section->properties[$v_key] = $v_property_info;
    //         $v_property_info->section = $attach_to_section;
    //     }
    // }
    // protected function _n_chain_section(
    //     ?GraphQlReadSectionInfo $v_section_info,
    //     &$o,
    //     ?GraphQlPointerObject &$v_current_pointer = null
    // ) {
    //     $v_current_pointer = new GraphQlPointerObject($o, $v_current_pointer);
    //     $n = new GraphQlReadSectionInfo($this, $v_current_pointer);
    //     $n->parent = $v_section_info; 
    //     return $n;
    // }
}
function to_null_prop($p)
{
    return GraphQlReaderUtils::InitDefaultProperties($p, 8);
}
$file = '/Volumes/Data/wwwroot/core/Packages/Modules/igk/io/GraphQl/Lib/Tests/Data/test_global_query_list.gql';
$data = [
    [
        'o' => 'sample1',
        'firstname' => 'charles',
        'lastname' => 'bondje', 
        'x'=>'basic',
        'y'=>'ok',
        'presentation' => [
            'fullname' => [
                [
                    'g' => 'charles',
                    'h' => 'bondje'
                ],
                [
                    'g' => 'charles II',
                    'h' => 'bondje IV',
                    's' => 'SSU'
                ]
            ]
        ]
    ],
    [
        'o' => 'sample2',
        'firstname' => 'sylvain',
        'lastname' => 'nzala',
        'presentation' => [
            'fullname' => [
                [
                    'g' => 'charles',
                    'h' => 'bondje'
                ],
                [
                    'g' => 'charles II',
                    'h' => 'bondje IV',
                    's' => 'SSU'
                ]
            ]
        ]
    ]
];
class Listener implements IGraphQlInspector{
    private $m_sourceData;
    public function setSourceData($data){
        $this->m_sourceData = $data;
    }
    public function getSourceTypeName(): ?string { 
        return "User";
    }
    public function query() { 
        return $this->m_sourceData;
    }
    /**
     * uppercase the field 
     * @param GraphQlQueryOptions|null $options 
     * @return string[] 
     * @throws IGKException 
     */
    public function upperCase(?GraphQlQueryOptions $options=null){ 
        $d = $options->data;  
        if (is_null($d)){
            return null;
        }
        return [
            'firstname'=>strtoupper(igk_getv($d, 'firstname')),
            'lastname'=>strtoupper(igk_getv($d, 'lastname')),
            'index'=>strtoupper(igk_getv($d, 'lastname')) 
        ];  
    }
}
$listener = new Listener;
$listener->setSourceData($data);
$ts = $o = NGraphQlReader::Parse(file_get_contents($file), $listener, $parser);
// $parser->setListener(new Listener); 
// $queries = igk_getv($parser->getDeclaredInputs(), 'query');
// $mutation = igk_getv($parser->getDeclaredInputs(), 'mutation'); 
// $ts = $queries[0]->getSection()
// ->setSourceTypeName('User')->getData($data);
echo json_encode($ts, JSON_PRETTY_PRINT);
igk_exit();
// protected function onType(){
//     $listener = $this->m_reader->getListener(); 
//     if ($listener && ($listener->getSourceType() == $this->m_on)){
//         return true;
//     }
//     return false; 
// }
// bind to data 
$vs =  $o['x']->section->getData($data);
print_r($vs);
igk_wln_e(__FILE__ . ":" . __LINE__, $vs);
print_r($o);
igk_wln_e("done .... ");