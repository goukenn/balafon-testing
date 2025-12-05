<?php
use igk\js\Vue3\Compiler\VueSFCCompiler;
use igk\js\Vue3\Compiler\VueSFCCompilerOptions; 
// if (preg_match("/import\s*(?P<ref>.+)\s*from\s+(?P<lib>(.+))(|$)/im", " import {createApp}from 'vue'", $tab, 0 , 1)){
//     // convert to js litteral inclusions
//     $o = "const ".$tab['ref'] . " ";
//     if ($tab['lib'] == "'vue'"){
//         $o.= '= Vue;';
//     }
//         igk_wln_e("found.....", $tab, $o) ;
// }
$src = <<<'JS'
import {defineComponent, ref, reactive } from 'vue';
const i = ref('0');
const myComponent = defineComponent({
    template:"information ... "
}), sample = defineComponent({
    template: 'job'
})
const x = ref(10);
onBeforeMount(() => {
    console.log ("before mounting....")
}), 
JS;
$options = new VueSFCCompilerOptions;
$options->export = true;
$g = VueSFCCompiler::GetLitteralSetupScript($src, null, $options);
igk_wln_e("conversion : ", $g);