<?php
use igk\js\Vue3\Compiler\VueSFCCompiler;
use igk\js\Vue3\Compiler\VueSFCCompilerOptions; 

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