<?php
use IGK\System\DataArgs;
use IGK\System\IO\StringBuilder;
use IGK\System\Net\MailDocument;
$ctrl = bantubeatController::ctrl();
$g = "data";
$sb = new StringBuilder;
$n = new MailDocument();
/**
 * @var mixed $m
 */
$n->article($ctrl,'registrationMail', new DataArgs([
    'firstName'=>$g,
    'activate_uri'=>$ctrl->getAppUri("api/users/activate"),
    'firstName'=>$m->clFirstName,
    'lastName'=>$m->clLastName
]));
$sb->appendLine($n->render());
echo $sb->__toString();