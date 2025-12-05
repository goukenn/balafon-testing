<?php
use igk\js\Vue3\Components\VueComponent;
use IGK\System\Html\Dom\HtmlFormNode;
use IGK\System\Html\Forms\FieldBuilder;
use IGK\System\Html\HtmlNodeBuilder;
$fields = new FieldBuilder;
$fields
->fieldset('Current POST')
->textarea('post')
->placeholder('Please enter your post')
->maxLength(300)
->pattern('/[0-9]+/')
->endfieldset()
->actionbar([
    'submit'
]);//->submit()
// ->text("login")
// ->password('password')
// ->fieldset()
// ->text('local')
// ->id('local')
// ->placeholder('local info')
// ->endfieldset()
// ->text('jump')
// ;
$r = new VueComponent('div');
$d = new HtmlNodeBuilder($r);
$d([
    'form'=>[
        'fields'=>[
            '@'=>[$fields->to_array()]
        ]
    ]
]);
// $form = new HtmlFormNode(); 
// $form->fields($fields->to_array()); 
// $form->renderAJX(); 
$r->renderAJX();
igk_exit();