<?php
use igk\js\babel\System\Console\Commands\BabelCommand;
use igk\js\common\JSExpression;
use igk\js\Vue3\Compiler\VueSFCUtility;
use igk\js\Vue3\Libraries\VueRouter;
use IGK\System\Exceptions\CssParserException;
use IGK\System\Exceptions\ArgumentTypeNotValidException;
use IGK\System\Shell\OsShell;

igk_require_module(igk\js\Vue3::class);
/**
* auto generate doc.
*/
class ViteApplicationHelper{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $ctrl;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $dist;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $entryNamespace = 'viteApp';
    /**
    * auto generate doc.
    * @var mixed
    */
    var $target = '#app';
    /**
    * auto generate doc.
    * @var mixed
    */
    var $mode = 'development'; 
    /**
    * auto generate doc.
    * @var mixed
    */
    var $routeName = 'vite-router';
    /**
    * auto generate doc.
    * @var mixed
    */
    const APP_JS_MIME_TYPE = 'text/balafon-vite-app';
    /**
     * build application injection settings
     * @return null|string 
     * @throws IGKException 
     * @throws Exception 
     * @throws CssParserException 
     * @throws ArgumentTypeNotValidException 
     * @throws ReflectionException 
     */
    public function buildApplicationInjection(){
        $s = igk_create_node('script');
        $s['type'] = self::APP_JS_MIME_TYPE;
        $options = [
            'target'=>$this->target,
            'entryNamespace'=>$this->entryNamespace,
            'uses'=>(object)[],  
            'components'=>[], 
            'menus'=>[], 
            'configs'=>[] 
        ];
        if ($this->routeName){ 
            $ref_options = null;
            $ref_router = null;
            $r = VueRouter::InitRoute($this->ctrl, $this->routeName, null, $ref_router,$ref_options, $this->mode);
            $sr = $r->render();
            $inject = VueSFCUtility::RenderLibraryAsConstantDeclaration($r->getLibraries(), $globalImport);
            $options['uses']->router = JSExpression::Litteral('(function(){'. $sr .'; return router;})()'); 
        }
        $src = sprintf('(function(){/*- define option -*/ igk.system.defineOption("%s", %s);})();', $this->entryNamespace, 
        JSExpression::Stringify((object)$options));
        if ($this->mode == 'production'){
            // + | --------------------------------------------------------------------
            // + | babel and uglifies
            // + |
            if ($bin = OsShell::Where('babel')){
                $f = igk_io_tempfile('parser');
                igk_io_w2file($f, $src);
                $out = shell_exec("{$bin} --no-comments --minified $f -o {$f}");
                if ($uglify = OsShell::Where('uglifyjs')){   
                    shell_exec("$uglify $f -c -o $f");
                }
                $out = file_get_contents($f);
                @unlink($f); 
                $src = $out;
            }
        } 
        $s->setContent($src); 
        return $s->render();
    }
}
$ctrl = AppTestProject::ctrl();
$ctrl->register_autoload();
$b = new ViteApplicationHelper;
$b->ctrl = $ctrl;
$b->dist = 'dist/vueapp';
$b->mode = 'build'; 
$b->routeName = 'vueapp/default-routes';
$src = $b->buildApplicationInjection();
echo 'result : '.$src . PHP_EOL;
exit;