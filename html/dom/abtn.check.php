<?php
// @author: C.A.D. BONDJE DOUE
// @filename: abtn.check.php
// @date: 20250901 08:20:11
// @desc: check the for abtn loading 
// @command: balafon --run .test/html/dom/abtn.check.php
use function igk_html_host as _h;
// $c = _h('abtn.register', ['/sign/register'], 'Register loading data');
$c = _h(
            'div.sign-or-register.dispflex.flex-row.flex-item-start',
            _h('abtn.sign', ['/signin/identifier'], 'SignIn'),
            _h('abtn.register', ['/sign/register'], 'Register')
);
igk_wln_e(__FILE__.":".__LINE__ , $c);