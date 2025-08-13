<?php
use igk\io\GraphQl\GraphQlParser;
use igk\io\GraphQl\Tests\GraphQlMockInlineSpearListener;
define('IGK_TEST_INIT', 1);
igk_require_module(igk\io\GraphQl::class);
$src = <<<'GQL'
Source{
    name 
    # ... on User{
    #     """
    #     first name definition 
    #     """
    #     firstName
    #     lastName
    #     gender: sexe
    # }
    ... on Person{
        # __typename
        refId{
           
            """
            bind inline
            """
            ... on User{
                likes
            }
            #{
            #    __typename
            #    barcode
            #    y{
            #        __typename
            #        s
            #        ... on Person{
            #            __typename
            #            likes
            #        }
            #    }
            #}
        }
    }
}
GQL;
$p = new GraphQlMockInlineSpearListener('Person');
$p->setSource([
    ['name' => 'Bondje', 'likes'=>'455'],
    ['name' => 'One Bondje', 'likes'=>8, 'refId'=>['x'=>
    ['y'=>['s'=>'the sssssy value', 'likes'=>90], 
    'likes'=>89,
    'barcode'=>'00088898798']]],
]);
$o = GraphQlParser::Parse(
    $src,
    $p
);
print_r($o);
igk_wln_e($o);