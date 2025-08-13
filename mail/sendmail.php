<?php
// @command: balafon --run .test/mail/sendmail.php
use IGK\System\Net\Mail;
use function igk_html_host as _h;
$option = igk_mail_option();
$html = _h(igk_create_node('div'), 
    'Ajouter du contenu utile',
    _h('p', 'Combattre les données du jour'),
    _h('ul.fitw',  _h('@loop', [range(0,10), function($ul, $item){ 
            $ul->li()->add('span')->content = 'Items loading.... '.$item; }] ))
)->render($option);
$cnf = igk_configs();
$from = $cnf->get("mail_contact", "info@".$cnf->get("website_domain"));
list($title, $msg, $to, $fromTitle) = igk_extract([
    'title'=>'Participer aux données',
    'msg'=>$html,
    'to'=>'bondje.doue@gmail.com',
    'fromTitle'=>'IGKDEV',
], 'title|msg|to|fromTitle');
$_mail = new Mail();
$_mail->setTitle($title);
$_mail->setHtmlMsg($msg);
$_mail->setFromTitle("IGKDEV", $from);
$_mail->addTo($to); 
igk_wln_e( ' = '.$_mail->sendMail());