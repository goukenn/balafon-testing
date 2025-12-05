<?php
use IGK\Helper\Activator;
use IGK\Helper\JSon as HelperJSon;
use IGK\Helper\JSon;
use IGK\Helper\JSonEncodeOption;
use IGK\System\IToArrayResolver;
// class JSon extends HelperJSon {
//     /**
//      * encoding option
//      * @var JSonEncodeOption
//      */
//     protected $m_options;
//     /**
//      * 
//      * @var mixed
//      */
//     protected $m_data;
//     /**
//      * encode
//      * @param int $encode 
//      * @return string|false 
//      */
//     public function enc(int $encode){
//         $root = $this->get_root_data($this->m_data);
//         return $root ? json_encode($root, $encode) : null; 
//     }
//     public function get_root_data($data){
//         $root = null;
//         $tq = [['d'=>$data, 'keys'=>null, 'c'=>null]];
//         $path = '';
//         while(count($tq)>0){
//             $q = array_shift($tq);
//             extract($q);
//             $v = $d;
//             $keys = $keys ?? array_keys((array)$d);
//             $is_object = (isset($is_object) ? $is_object: null ) ?? is_object($v);
//             $end = false;
//             while(!$end  && (count($keys)>0)){
//                 $k = array_shift($keys);
//                 $tv = igk_getv($v, $k);
//                 if ($this->m_options->ignore_empty && empty($tv)){
//                     continue;
//                 }
//                 if (is_null($root)){
//                     $root = (object)[];
//                     $c = $root;
//                 }
//                 if ($tv instanceof IToArrayResolver){
//                     $tv = $tv->to_array(); 
//                 }
//                 if (is_array($tv)){
//                     if ($fc = $this->m_options->filter_array_listener){                        
//                         $tv = array_values(array_filter(array_map($fc, $tv)));
//                     }
//                     else if ($this->m_options->ignore_empty ){
//                         $tv = array_values(array_filter(array_map([$this, 'filter_array'], $tv)));
//                     }
//                 } else if  (is_object($tv)){
//                     array_unshift($tq, ['d'=>$d, 'keys'=>$keys, 'c'=>$c, 'is_object'=>$is_object]);
//                     $c->$k = new stdClass;
//                     array_unshift($tq, ['d'=>$tv, 'keys'=>null, 'c'=>$c->$k, 'is_object'=>true]);
//                     $end = true;
//                     break;
//                 }
//                 $c->$k = $tv;
//             }
//         }
//         return $root;
//     }
//     /**
//      * filter array 
//      * @param mixed $a 
//      * @return mixed 
//      * @throws IGKException 
//      */
//     public function filter_array($a){         
//         if (is_object($a)){
//             $c = $this->get_root_data($a); 
//             return $c;
//         }
//         return $a;
//     }
//     /**
//      * encode data
//      * @param mixed $data 
//      * @param mixed $options 
//      * @param int $encode 
//      * @return string|false 
//      */
//     public static function Encode($data, $options = null, int $encode = JSON_UNESCAPED_SLASHES){
//         if (is_null($options)){
//             $options = new JSonEncodeOption;
//         }else if (!($options instanceof JSonEncodeOption)){
//             $options = Activator::CreateNewInstance(JSonEncodeOption::class, $options);
//         }
//         $e = new static;
//         $e->m_options = $options;
//         $e->m_data = $data;
//         return $e->enc($encode);
//     }
//     protected function __construct(){
//     }
// }
// $s = JSon::Encode(['a'=>4, 'b'=>null, 'm'=>[5,9,null,9], 'c'=>'53'], [
//     'ignore_empty'=>true
// ]);
// $s = JSon::Encode(['a'=>4, 'b'=>null, 'm'=>[5,9,null,9], 'c'=>(object)['x'=>111, 'y'=>null]], [
//     'ignore_empty'=>true
// ]);
$s = JSon::Encode(['a'=>4, 'b'=>null, 'm'=>[5,9,null,9], 
    'c'=>(object)['x'=>(object)['jj'=>[111,null,(object)['x'=>444, 'y'=>null]]], 'y'=>null, 'xx'=>88],
    'x'=>554
    ], [
    'ignore_empty'=>true
]);
igk_wln_e('response : '.$s.' '.PHP_EOL);