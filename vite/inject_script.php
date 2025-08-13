<?php
// create injection script 
use igk\js\babel\System\Console\Commands\BabelCommand;
use igk\js\common\JSExpression;
use igk\js\Vue3\Compiler\VueSFCUtility;
use igk\js\Vue3\Libraries\VueRouter;
use IGK\System\Exceptions\CssParserException;
use IGK\System\Exceptions\ArgumentTypeNotValidException;
use IGK\System\Shell\OsShell;
igk_require_module(igk\js\Vue3::class);
class ViteApplicationHelper{
    var $ctrl;
    var $dist;
    var $entryNamespace = 'viteApp';
    var $target = '#app';
    var $mode = 'development'; // production|development
    var $routeName = 'vite-router';
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
            'uses'=>(object)[],  // uses to inject // each use represent an usages
            'components'=>[], // store injected components - builded or not 
            'menus'=>[], // menu presentation object
            'configs'=>[] // store extra configuration. to pass to application 
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
       // echo $src.PHP_EOL;
        // TODO: babel for production 
        if ($this->mode == 'production'){
            // + | --------------------------------------------------------------------
            // + | babel and uglifies
            // + |
            if ($bin = OsShell::Where('babel')){
                $f = igk_io_tempfile('parser');
                igk_io_w2file($f, $src);
                $out = `{$bin} --no-comments --minified $f -o {$f}`;
                if ($uglify = OsShell::Where('uglifyjs')){   
                    // `$uglify $f -b quote_style=3 -c -o $f`;
                    `$uglify $f -c -o $f`;
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