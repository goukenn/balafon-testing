<?php
$n = igk_create_notagnode();
$n->load(<<<'HTML'
<picture class="dp-new-gallery__picture">
    <source srcSet="https://prod.pictures.autoscout24.net/listing-images/f97c0a40-9866-4b77-953a-44d76244081d_9681b3cc-03bc-48f6-ba7d-dd3ff7b31dcc.jpg/250x188.webp" media="(min-width: 768px) and (max-width: 1099px) and (-webkit-max-device-pixel-ratio: 1)" type="image/webp" height="188" width="250"/>
    <source srcSet="https://prod.pictures.autoscout24.net/listing-images/f97c0a40-9866-4b77-953a-44d76244081d_9681b3cc-03bc-48f6-ba7d-dd3ff7b31dcc.jpg/250x188.jpg" media="(min-width: 768px) and (max-width: 1099px) and (-webkit-max-device-pixel-ratio: 1)" type="image/jpeg" height="188" width="250"/>
    <source srcSet="https://prod.pictures.autoscout24.net/listing-images/f97c0a40-9866-4b77-953a-44d76244081d_9681b3cc-03bc-48f6-ba7d-dd3ff7b31dcc.jpg/480x360.webp" media="(max-width: 767px), (min-width: 1100px), (-webkit-min-device-pixel-ratio: 1.01)" type="image/webp" height="360" width="480"/>
    <source srcSet="https://prod.pictures.autoscout24.net/listing-images/f97c0a40-9866-4b77-953a-44d76244081d_9681b3cc-03bc-48f6-ba7d-dd3ff7b31dcc.jpg/480x360.jpg" media="(max-width: 767px), (min-width: 1100px), (-webkit-min-device-pixel-ratio: 1.01)" type="image/jpeg" height="360" width="480"/><img src="https://prod.pictures.autoscout24.net/listing-images/f97c0a40-9866-4b77-953a-44d76244081d_9681b3cc-03bc-48f6-ba7d-dd3ff7b31dcc.jpg/250x188.webp" class="dp-new-gallery__img" alt="" height="188" width="250" loading="eager"/></picture>
HTML);
function get_source($n){
    $t = $n->tagName();
    if ($t){
        $fc = 'visit_content_image_'.strtolower($t);        
        $visitor = get_visitor();
        if (method_exists($visitor,  $fc)){
            return call_user_func_array([$visitor, $fc], [$n]);
        } 
    } 
}
function get_picture($n){
    return array_map(function($a){
        return get_source($a); 
    }, array_filter($n->getElementsByTagName(function($a){
        if ($t = $a->getTagName()){
            return in_array($t, ['source', 'img']);
        }
    }))); 
}
class imageVisitor{
    public function visit_content_image_source($n){
        if ($g = $n['srcSet']){
            return $g;
        }
    }
    public function visit_content_image_img($n){
        if ($g = $n['src']){
            return $g;
        }
    }
    public function visit_content_image_link($n){
        if ($src = $n['href']){
            if ($n['as'] == 'image'){                
                return $src;
            }
        }
    }
}
 function get_visitor(){ 
    static $visitor;
    if (is_null($visitor)){
        $visitor = new imageVisitor();
    }
    return  $visitor ?? $visitor = new imageVisitor();
 }
$src = array_unique(array_filter(array_map('get_picture', $n->getElementsByTagName('picture'))));
igk_wln($src[0]);