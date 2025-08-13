<?php
use IGK\Helper\IO;
use IGK\Helper\StringUtility;
use IGK\System\Console\Logger;
use IGK\System\IO\File\PHPScriptBuilder;
$clean = property_exists($command->options, '--clean');
$dir = igk_getv($params, 0) ?? getcwd()."/AppBundle";
$name = basename($dir);
$dir = dirname($dir);
if (!igk_str_endwith($name, "Bundle")){
    $name.='Bundle';
}
if (is_dir($dir) && $clean){
    IO::CleanDir($dir);
}
$properties = [
    'name'=>substr($name, 0, -6),
    'namespace'=>igk_getv($command->options, '--namespace', 'App\Bundles'),
    'author'=>IGK_AUTHOR,
];
$properties['kebab_name']= strtolower(StringUtility::GetSnakeKebab($properties['name']));
// create directory 
foreach(
    [
        $dir."/public",
        $dir."/Controller",
        $dir."/DependencyInjection/Compiler",
        $dir."/Resources/config",
        $dir."/Resources/views",
        $dir."/Tests",
    ] as $d){
    IO::CreateDir($d);
}
$d = [];
$d[$dir.'/'.$name.'.php'] = function($file)use($properties){
    $builder = new PHPScriptBuilder();
    $builder->type('class')
        ->namespace($properties['namespace'])
        ->uses(
            \Symfony\Component\Console\Application::class,
            \Symfony\Component\DependencyInjection\ContainerBuilder::class,
            \Symfony\Component\HttpKernel\Bundle\Bundle::class,
        )
        ->author($properties['author'])
        ->file(basename($file))
        ->extends(\Symfony\Component\HttpKernel\Bundle\Bundle::class)
        ->name($properties['name'].'Bundle')        
        ->defs(
            implode("\n", [
               'public function build(ContainerBuilder $container){',               
               '    parent::build($container); ',
               '    // + | ------------- ',
               '    // + | compiler pass ',
               '}  ',
               'function registerCommands(Application $application){',
               '}',
            ])
        );
    igk_io_w2file($file, $builder->render());
};
$d[$dir.'/Resources/config/services.yaml'] = function($f){    
    igk_io_w2file($f, implode("\n", [
        'services:',
        '   '
    ]));
};
$d[$dir.'/DependencyInjection/Configuration.php'] = function($f)use($properties){    
    $builder = new PHPScriptBuilder();
    $name = $properties['name'];
    $builder->type('class')
        ->namespace($properties['namespace']."\\DependencyInjection")
        ->uses(            
            \Symfony\Component\Config\Definition\Builder\TreeBuilder::class,
        )
        ->author($properties['author'])
        ->file(basename($f))
        ->extends(\Symfony\Component\Config\Definition\ConfigurationInterface::class)
        ->name('Configuration')        
        ->defs(
            implode("\n", [
                'public function getConfigTreeBuilder(){',
                '    $treebuilder = new TreeBuilder(\''.$properties['kebab_name'].'\'); ',
                '    $rootNode = $treebuilder->getRootNode();',
                '    return $treebuilder; ',
                '} ',
            ])
        );
    igk_io_w2file($f, $builder->render());
};
$d[$dir.'/DependencyInjection/'.$properties['name'].'Extension.php'] = function($f)use($properties){    
    $builder = new PHPScriptBuilder();
    $builder->type('class')
        ->namespace($properties['namespace']."\\DependencyInjection")
        ->uses( 
            \Symfony\Component\DependencyInjection\Extension\Extension::class,
\Symfony\Component\DependencyInjection\ContainerBuilder::class,
\Symfony\Component\DependencyInjection\Extension\Extension::class,
\Symfony\Component\DependencyInjection\Loader\YamlFileLoader::class
        )
        ->author($properties['author'])
        ->file(basename($f))
        ->extends( \Symfony\Component\DependencyInjection\Extension\Extension::class)
        ->name(igk_io_basenamewithoutext($f))        
        ->defs(
            implode("\n", [
                'public function load(array $configs, ContainerBuilder $container) { ',
                '    // $configuration = new Configuration;',
                '    // $config = $this->processConfiguration($configuration, $configs);',
                '    $loader = new YamlFileLoader($container, new FileLocator(__DIR__."/../Resources/config/"));',
                '    $loader->load(\'services.yaml\'); ',
                '}',
            ])
        );
    igk_io_w2file($f, $builder->render());
};
$force = 1;
foreach($d as $n=>$c){
    if ($force || !file_exists($n)){
        $c($n);
        Logger::info("generate : ".$n);
    }
}