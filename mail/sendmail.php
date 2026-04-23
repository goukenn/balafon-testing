<?php
// @command: balafon --run .test/mail/sendmail.php [email] 
// @author: C.A.D. BONDJE DOUE
// @filename: sendmail.php
// @date: 20250929 18:48:57
// @desc: send mail demonstration 
// @balafon-command: sendmail 
use IGK\System\Net\Mail;
use function igk_html_host as _h;

$option = igk_mail_option();
$html = _h(igk_create_node('div'), 
    'Ajouter du contenu utile',
    _h('p', 'Combattre les données du jour'),
    _h('ul.fitw.dispflex',  _h('@loop', [range(0,10), function($ul, $item){ 
            $ul->li()->add('span')->content = 'Items loading.... '.$item; 
        }] ))
)->render($option);
$cnf = igk_configs();
$from = $cnf->get("mail_contact", "info@".$cnf->get("website_domain"));
list($title, $msg, $to, $fromTitle) = igk_extract([
    'title'=>'Participer aux données',
    'msg'=>$html,
    'to'=> igk_getv($params, 0) ?? 'cbondje@igkdev.com', 
    'fromTitle'=>'IGKDEV - BONDJE DOUE',
], 'title|msg|to|fromTitle');
$_mail = new Mail();
$_mail->setTitle($title);
$_mail->setHtmlMsg($msg);
$_mail->setFromTitle("IGKDEV", $from);
$_mail->addTo($to); 
igk_wln_e( ' = '.$_mail->sendMail());