<?php
use igk\js\Vue3\Compiler\VueSFCCompiler;
use igk\js\Vue3\Compiler\VueSFCCompilerOptions;
use igk\js\Vue3\Compiler\VueSFCRenderNodeVisitorOptions;
use igk\js\Vue3\Components\VueComponent;
use igk\js\Vue3\Components\VueNoTagNode;
use IGK\System\Html\Dom\HtmlNode;
use IGK\System\Html\HtmlNodeBuilder;
$d = new VueNoTagNode('div');
// $d->load(<<<'HTML'
// <a v-if="a">a <span>ok</span></a>
// <a v-else>else ok </a>
// <p>info </p>
// <b v-if="b">b <span>bok</span></b>
// HTML);
// $d->div()->Content = 'Home';
// $d->div()->Content = 'Info';

$builder = new HtmlNodeBuilder($d);

// $builder([
//     "d > div" => [
//         "p#response" => [
//             "_" => [
//                 "v-if" => "response"
//             ] 
//         ]
//     ] 
// ]);

// $builder([
//     "d > div" => [
//         "p#response" => [
//             "_" => [
//                 "v-if" => "response"
//             ] ,
//             '--data--'
//         ]
//     ] 
// ]); 
$d->div()->vIf('a')->Content = 'a';
$d->div()->vIf('b')->Content = 'b';
// render(){const{h}=Vue;return [this.a?h('div','a'):null,this.b?h('div','b'):null]}
// $src = VueSFCCompiler::ConvertToVueRenderMethod($d);


// $d->clearChilds();
// $d->div()->vIf('a || b')->Content = 'a';
// $d->div()->vElseIf('b')->Content = 'b';
// $d->div()->vElseIf('c')->Content = 'c';
// $d->div()->vElse()->Content = 'else';
// // render(){const{h}=Vue;return [this.a?h('div','a'):null,this.b?h('div','b'):null]}
// $src = VueSFCCompiler::ConvertToVueRenderMethod($d);


// $d->clearChilds();
// $d->load(/*html*/ "<div v-if='x > 50.5'>hello </div><div v-else>else <b>what</b></div>");
// // render(){const{h}=Vue;return [this.a?h('div','a'):null,this.b?h('div','b'):null]}
// $src = VueSFCCompiler::ConvertToVueRenderMethod($d);

$d->clearChilds();
// $d->load(/*html*/ "<p><div v-if='item'> item <span>o</span></div></p>");
// $d->load(/*html*/ '<div v-if="car.fueldId"><font color="#921534">Laca</font></div> ');
// $d->load(/*html*/ '<b>hello</b><h4 v-if="a">A</h4>
//     <h4 v-if="b">B</h4>
//     <h4 v-else>C</h4> ');
// $d->load(/*html*/ '<div v-if="rep">AB</div><Teleport to="Body">Info</Teleport>');
//render(){const{h,Teleport,Text}=Vue;return [this.rep?h('div','AB'):null,h(Teleport,{to:'Body'},[h(Text,'Info')])]}

// $d->load(/*html*/ '<div v-if="item"><span v-if="kill">sub two</span></div> ');
// render(){const{h}=Vue;return this.item?h('div',[this.kill?h('span','sub two'):null]):null}

//$d->load(/*html*/ '<a v-if="a">a</a><a v-if="b">b</a><p>p</p> ');
//$d->load(/*html*/ '<a v-if="a">a<span v-if="b">b</span></a><p>p</p> ');
// render(){const{h}=Vue;return [this.a?h('div','a'):null,this.b?h('div','b'):null]}

// $d->load(/*html*/ '<a v-if="a">a</a><p v-else>p</p> ');
// $d->load(/*html*/ '<div><div v-if="item1"> item1 <span>child1</span></div>
//     <div v-if="item2"> item2 <span>child2</span></div></div>');

// l'ordre
// $d->load('<div><div v-if="item"><span v-if="kill">sub two</span></div></div>');
// $d->load('<div v-if="a || b">
// <h4 v-if="a">one <span>m</span></h4>
// <h4 v-if="b">two <span>x</span></h4> 
// </div>');
// $d->load('<div  v-if="item">
//         <h4 v-if="!a"> S </h4>
//         <div>
//             <p to="/proposal"> Proposer une voiture </p>
//         </div>
// </div> ');
// $d->load(<<<'HTML'
//  <h2> '{{$t('Show room')}} </h2>
// HTML, []);
function igk_html_node_base_param()
{
    // igk_wln_e("init base param ...... ");
    return new VueComponent('div-b');
}

// <!-- <div class="igk-col-xxlg-12-12" :class="{'col-md-12': !car.desc }">
//     <div class="igk-row"> 
//         <div class="fitw" v-if="car.pictures && (car.pictures.length>0)">                       
//             <igk:google-icon-outlined igk:args="fullscreen" class="md-48" v-pre="v-pre" ></igk:google-icon-outlined>
//         </div>
//         <igk:base_param v-else> else ... </igk:base_param >                       
//     </div>  -->
// <!-- </div> -->
$d = new HtmlNode('div');
$d->load(<<<'HTML'
<div>
    <router-link to="/proposal" class="igk-btn btn custom-btn nav-btn"> '{{ $t('Propose a car') }} </router-link>
</div>
HTML, []);
// <template #info="presentation" v-if="a" >
// <span>OK</span>
// <div>Not OK</div>
// </template>
// <template #info="presentation" v_else>
// <span>OK</span>
// <div>Not OK</div>
// </template>
// $d->load(<<<'HTML'
// <div v-if="x">
// </div>
// <div v-if="a || b || c">   
// <div class="event_news_text">
//     <h4 v-if="a">A: </h4>
//     <h4 v-if="b">B: </h4>
//     <h4 v-if="c">C:</h4>
// </div>
// </div>

// HTML, []); 


// <div class="igk-row show-room">
// <section class="v-space">
//     <h2> '{{$t('Show room')}} </h2>
// </section>
// <div class="igk-row-container igk-row igk-col-12-12 dispflex flex-row flex-wrap">
//     <div v-for="(car, key) in carlist" :key="key" class="car-box pad-10 igk-col-12-12 igk-col-lg-12-4 igk-col-xlg-12-3 igk-col-xxlg-12-3">
//         <div class="car-block dispflex flex-column igk-box-rounded">
//             <div class="picture posr">
//                 <div class='pic-zone no-overflow'>
//                     <router-link :to="'/car/details/'+car.crcar_pc_id" >
//                         <img :src="defaultPicture(car)" title="default picture" />
//                     </router-link>
//                 </div>
//             </div>
//             <div>
//                 <h3 class="no-wrap no-overflow ellipsis"> '{{ car.prod_Name }} </h3>
//                 <p class="no-wrap no-overflow ellipsis">'{{ car.crcar_pc_title }}</p>
//             </div>
//             <div class="no-wrap no-overflow ellipsis">
//                 <template v-if="car.crcar_pc_Price">
//                     '{{ car.crcar_pc_Price + '.-' }}
//                 </template>
//                 <template v-else>
//                     &nbsp;
//                 </template>
//             </div>
//             <div>
//                 <div class="blog_link">

//                 </div>   
//             </div>
//         </div>
//     </div>
// </div>
// <div class="no-carfound igk-row " v-if="showListLoading">
//     <h4 v-if="!carlist"> show room n'est pas encore remplis. Proposer nous une voiture?</h4>
//     <div class="alignc"> 
//     </div>
// </div> 
// </div>
// HTML);
$options = new VueSFCRenderNodeVisitorOptions;
$options->components = array_fill_keys(['CustomItem'], 1); 
// $options->test = true;
$src = VueSFCCompiler::ConvertToVueRenderMethod($d, $options);

igk_wln_e($src);
