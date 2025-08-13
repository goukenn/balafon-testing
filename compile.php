<?php
//     public function test_read_identifier(){
//         $offset = 1;
//         $this->assertEquals("bonjour", 
//             StringUtility::ReadIdentifier("@bonjour", $offset),
//             "not correct"
//         );
//     }
//     public function test_compilation_remove_single_line_comment()
//     {
//         $src = <<<'PHP'
// <?php
// // remove this
// #{{% @MainLayout }} 
// echo 'bonjour';
// PHP
 
// $g = BalafonViewCompiler::CompileSource($src, (object)[
//     "layout"=>new \IGK\System\WinUI\PageLayout
// ]); 
//         $this->assertEquals(
//             <<<'PHP'
// <?php
// echo 'bonjour';
// PHP,
//             $g->source,
//             "failed to compile expression"
//         );
//     }
//     private static function _test_code(){
//         return [
// <<<PHP_CODE
// <?php
// use \Reflexion;
// foreach(\$data as \$k){
//     echo \$k? >Presentation<?php ;
// }
// igk_exit();
// PHP_CODE,
// <<<PHP_CODE
// <?php
//     echo 'test code 2';
// ? >
// PHP_CODE,

// <<<PHP_CODE
// <?php
// //# {{% @MainLayout }}
// //# {{% @Import ".header.pinc" }}
// echo 'import file - testFile ';
// //# {{% @Import ".footer.pinc" }} 
// //# {{% @BALAFON_VERSION }} 
// PHP_CODE,

// <<<PHP_CODE
// <?php
// //# {{% @MainLayout }}
// use IGK\Helper\ViewHelper;
// \$t->div()->Content = "Presentations de : ".\$fname;
// \$t->article(\$ctrl, "list-option.template", \$data);

// //# {{% @Import ".footer.pinc" }} 
// PHP_CODE,
// 4=><<<'PHP'
// <?php
// $t->div()->article($ctrl, 'inner.article.template', [1=>[2,3]]);
// PHP,
// 5=><<<'PHP'
// <?php
// // anoymous function detection 
// $s = function(){
// };
// $t->div()->Content = 'function';
// PHP,
// 6 => <<<'PHP'
// <?php

// function renB(){
// }
// function renC(){
// }
// function renA(){
// }

// abstract class BA implements IA{
// }
// trait CBTrait{
// }
// interface IA{
//     function job();
// }
// final class AB{

// }
// $x = $ctrl->getParam('data');
// if ($x == 10){
//     echo 'rendering data ... in setting ....';
// }
// $t->div()->Content = 'func_detect';
// $t->div()->Content = "{$ctrl->getAppUri('func_detect')}";
// $t->div()->article($ctrl, 'article.template', $data);
// PHP
//         ];
//     }
//     public function _expect(){
// return [
//     3=><<<PHP
// <?php
// //# {{% @MainLayout }}
// use IGK\Helper\ViewHelper;
// ? ><div><div>Presentations de : temporyfiles</div><?php
// foreach(\$rawdata[0] as \$index=>\$raw){ \$context_raw = \$raw; ? ><li> data ok : <?= \$raw ? > </li><?php } ? >
// </div>
// PHP,
// 4=><<<PHP
// PHP,
// 5=> <<<'PHP'
// <?php
// \$s = function(){
// };
// ? ><div><div>function</div></div>
// PHP
// ,6 => ""
// ];
//     }

//     public function _articles(){
// return [
//     5=>"",
//     6=>"<quote>{{ \$raw }} </quote>"
// ];
//     }
//     // public function _test_view_files(){
//     //     $index = 3;
//     //     $files = [
//     //         "Views/default.phtml"=>$this->_test_code()[$index],             
//     //         "Views/.header.pinc"=>"",
//     //         "Views/.footer.pinc"=>"", // <?php \n \$t->div()->Content = 'bonjour';",
//     //     ];
//     //     $layout = new \IGK\System\WinUI\PageLayout;
//     //     $layout->viewDir = self::$sm_tempdir."/Views";

//     //     foreach($files as $k=>$v){
//     //         igk_io_w2file(self::$sm_tempdir."/{$k}", $v);
//     //     }
//     //     igk_debug(true);
//     //     $data = BalafonViewCompiler::CompileFile(self::$sm_tempdir."/Views/default.phtml", (object)["layout"=>$layout]);

//     //     // if (($data->readOptions->sourceType =='php') && strpos($data->source,"<?php")===0){
//     //     //     $data->source = ltrim(substr($data->source, 5));                        
//     //     // }
//     //     // if ($data->readOptions->inPHPScript && (strrpos($data->source, "? >")!==false)){
//     //     //     $data->source = substr($data->source, 0, -2);
//     //     // }
  

//     //     // // treat data source from layout
//     //     // $sb = new StringBuilder;
//     //     // $sb->appendLine("<? php");
//     //     // if ($data->readOptions->namespace){
//     //     //     $sb->appendLine("");
//     //     //     $sb->appendLine($data->readOptions->namespace);
//     //     //     $sb->appendLine("");
//     //     // }
//     //     // if ($data->readOptions->usings){
//     //     //     ksort($data->readOptions->usings);
//     //     //     $sb->appendLine("");
//     //     //     foreach($data->readOptions->usings as $m=>$alias){
//     //     //         $sb->append("use ".$m);
//     //     //         if ($m!=$alias){
//     //     //             $sb->append(" as ".$alias);
//     //     //         }
//     //     //         $sb->appendLine(";");
//     //     //     }
//     //     //     $sb->appendLine("");
//     //     // }
//     //     // $sb->appendLine($data->source);
//     //     // $data->source = "".$sb;


//     //     // igk_wln_e($data->readOptions->usings , $data->source);


//     //     $t = igk_create_node("div");
//     //     $fname = "temporyfiles";
//     //     $params = [];
//     //     $dataDB = [1,2,3];
//     //     $ctrl = new CompileTestController;
//     //     $ctrl->entryDir = self::$sm_tempdir;
//     //     igk_io_w2file($ctrl->getArticlesDir()."/list-option.template",
//     //          "<li > data ok : {{ \$raw }} </li>",
//     //          // "<ul><li > option:  {{ \$raw }} </li></ul>"
//     //     );
//     //     $args = new ViewEnvironmentArgs;
//     //     $args->t = $t;
//     //     $args->ctrl = $ctrl;
//     //     $args->fname = "default";
//     //     $args->rname = "test://";
//     //     $args->entry_uri = "test://";
//     //     $args->data = $dataDB;
//     //     BalafonViewCompiler::EvaluateCompiledSource($data->source, $ctrl, $args, $args->data );
//     //     // eval("? >".$data->source);
//     //     $render = $t->render();        

//     //     $this->assertEquals($this->_expect()[$index],
//     //     $render, 
//     //     "/!\\ - View compilation failed.");


//     //     // $ouput = "";
//     //     // $rawdata = [];
//     //     // $rawdata[] = $dataDB;
//     //     // $output = $this->_evalCompilation($render, $rawdata);
//     //     // igk_wln_e("source:", $data->source, "render:", $render, "output:", $output); 
//     // }

//     private function _evalCompilation($render, $rawdata){
//         ob_start();
//         eval("? >".$render."<?php");
//         $ouput = ob_get_contents();
//         ob_clean(); 
//         return $ouput;
//     }
//     private function _init_files($files){
//         foreach($files as $k=>$v){
//             if (is_null($v)) continue;
//             igk_io_w2file(self::$sm_tempdir."/{$k}", $v);
//         }
//     }
//     public function _test_article_in_article(){
//         $index = 4;
//     }
   
//     public function _test_func_in_article(){
//         igk_debug(true);
//         $this->_invoke_compile(5);
//     }
//     public function _test_detect_function_and_order(){
//         igk_debug(true);
//         $this->_invoke_compile(6);
//     }
//     public function test_string(){
//         igk_debug(true);
//         $this->_invoke_compile(10);
//     }
//     public function _invoke_compile($index){        
//         $files = [
//             "Views/default.phtml"=>igk_getv($this->_test_code(), $index) ?? 
//             file_get_contents(__DIR__."/.testfiles/test.".str_pad($index."", 2, "0", STR_PAD_LEFT).".php"),             
//             "Views/.header.pinc"=>"",
//             "Views/.footer.pinc"=>"<?php \n \$t->footer()->igk_copyright();",
//             "Articles/article.template"=>igk_getv($this->_articles(), $index)
//         ];
//         $this->_init_files($files);

//         $layout = new \IGK\System\WinUI\PageLayout;
//         $layout->viewDir = self::$sm_tempdir."/Views";
//         $compiler = new BalafonViewCompiler;
//         $compiler->source = file_get_contents(self::$sm_tempdir."/Views/default.phtml");
//         $compiler->controller = new CompileTestController;
//         $compiler->controller->setParam("data", 10);
//         $compiler->controller->entryDir = self::$sm_tempdir;
//         $compiler->args = new ViewEnvironmentArgs;
//         $compiler->args->t = new HtmlNode("div");
//         $compiler->args->ctrl = $compiler->controller;
//         $compiler->args->data = [1,5,6];       
//         $data = $compiler->compile((object)[
//             "layout" => $layout 
//         ]);
//         if ($data === false){
//             $this->fail("failed to compile");
//         }
 
//         $this->assertEquals(
//             $this->_expect()[$index],
//             $data->source,
//             "compilation failed"
//         );
//     }
    