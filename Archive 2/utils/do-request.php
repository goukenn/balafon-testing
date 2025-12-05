<?php
// @command: php .test/utils/do-request.php
// @sample:  php .test/utils/do-request.php src/public/index.php /sample bt62rduiu9jpch4q89o09ctnvb 
// argument : --env to set environment (nocache)
// argument : --request data to pass as request
($filename = $argv[1]) ?? igk_die("missing access file");
$uri = isset($argv[2]) ? $argv[2] : null;
$sess_id = isset($argv[3]) ? $argv[3] : null;
(function ($filename, $uri, $sessid, $argv) {
    if ($sessid) {
        $_COOKIE[session_name()] = $sessid;
    }
    $commands = (object)['options' => (object)[]];
    foreach ($argv as $s) {
        if (preg_match("/^--(?P<name>[a-z]+[a-z-]+)(:|\\b)/", $s, $tab)) {
            $k = $tab['name'];
            $v = substr($s, strlen($tab[0]));
            if (property_exists($commands->options, $k)) {
                if (!is_array($commands->options->{$k})) {
                    $commands->options->{$k} =  [$commands->options->{$k}];
                }
                $commands->options->{$k}[] = $v;
            } else {
                $commands->options->{$k} = $v;
            }
        }
    }
    if (isset($commands->options->secure)) {
        $_SERVER['HTTPS'] = 'on';
    }
    if (isset($commands->options->env)) {
        $r = $commands->options->env;
        if (!is_array($r)) {
            $r = [$r];
        }
        while (count($r) > 0) {
            $q = array_shift($r);
            switch ($q) {
                case 'nocache':
                    // $_ENV['IGK_ENV_NO_AUTOCACHEVIEW'] = 1;
                    putenv(sprintf('%s=1', 'IGK_ENV_NO_AUTOCACHEVIEW'));
                    break;
            }
        }
    }
    if (isset($commands->options->ajx)) {
        $_SERVER['HTTP_IGK_AJX'] = 1;
    }
    $method = 'GET';
    if (isset($commands->options->method)) {
        if (in_array($commands->options->method, ['GET', 'POST', 'OPTIONS', 'PUT', 'DELETE', 'OPTIONS'])) {
            $method = $commands->options->method;
        }
    }
    $server =  getenv('IGK_MYSQL_DB_SERVER', 'server');
    if (isset($commands->options->request)) {
        $r = $commands->options->request;
        if (is_string($r) && ($data = json_decode($r))) {
            $_SERVER['IGK_PHP_INPUT_DATA'] = $r;
            $r = null;
        } else if (is_string($r)) {
            $tab = [];
            if ($method == 'GET')
                $tab = &$_GET;
            else
                $tab =  &$_POST;
            parse_str($r, $tab);
            $_REQUEST = array_merge($_GET, $_POST);
        }
        if ($r){
        $v_gh = fopen("php://input", "w+");
        fputs($v_gh, $r);
        fclose($v_gh);
        }
    }
    $uri = $uri ?? '/';
    $_SERVER['REQUEST_METHOD'] = $method;
    $_SERVER['REQUEST_URI'] =  $uri; // '/assets/Styles/balafon.css';
    $_SERVER['REQUEST_PATH'] = $uri; //  
    $p = explode("?", $uri, 2);
    $path = array_shift($p);
    $query = '';
    if ($p)
        $query = array_shift($p);
    $_SERVER['PATH_INFO'] = $path; // 'path to resolve !important
    $_SERVER['QUERY_STRING'] = $query;
    // mandatory fields
    $_SERVER['SERVER_NAME']  = 'localhost';
    $_SERVER['SERVER_PORT']  = '7300'; // set secure port
    $_SERVER['HTTP_USER_AGENT'] = 'balafon-local-request-server';
    $l = realpath($filename);
    $_SERVER['PHP_SELF'] = '/' . basename($l);
    $_SERVER['SCRIPT_NAME'] = basename($l);
    chdir(dirname($l));
    if (!function_exists('igk_boot_request_environment')) {
        /**
         * 
         * @return void 
         */
        function igk_boot_request_environment($app) {
            $cnf = igk_configs();
            foreach([
                'IGK_MYSQL_DB_SERVER'=>'db_server',
                'IGK_MYSQL_DB_PASSWORD'=>'db_password',
                'IGK_MYSQL_DB_NAME'=>'db_name',
                'IGK_MYSQL_DB_USER'=>'db_user',
                ] as $k=>$tv){
                if (false !== ($v = getenv($k))){
                    $cnf->{$tv} = $v ?? $cnf->{$tv};
                }
            }
        }
    }
    (function () {
        include func_get_arg(0);
    })(basename($l));
})($filename, $uri, $sess_id, array_slice($argv, 1));