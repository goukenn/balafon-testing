<?php

// PHPFormatterTmSyntaxTrait

use IGK\System\Text\RegexMatcherContainer;
use IGK\System\Text\RegexMatcherUtility;

trait PHPFormatterTmSyntaxTrait{
      protected function initRegex(RegexMatcherContainer $regex)
    {
        $here_doc = [];
        RegexMatcherUtility::AppendPhpHereDoc($regex, $here_doc);
        $regex->patternCreatorClass = PHPFormatRegexMatcherPattern::class;
        $_multi_comment = $regex->appendMultilineComment()->last();
        $_depth_comment = $regex->match('(^\\h*\/\/.+)', 'depth-line-comment')->last();
        $_depth_comment->autoLineFeed = true;
        $_depth_comment->trimmed = true;

        $_doc_comment = $regex->appendCommentDocBlock()->last();
        $string = $regex->appendStringDetection('string', true)->last();
        $line_comment = $regex->appendSingleLineComment("\\h*\/\/")->last();

        $line_comment->trimmed = 'rtrim';
        $line_comment->flags = [
            'appendInlineInstruct'
        ];
        $_operator = $regex->match('\\b((\\.|-|\+|=|<|==|\\*)?=|<=|>=|<=>|\|\||&&|\+|-|%|\\*|\\/)\\b', 'f-operator')->last();

        $_start = $regex->match('\{', 'curl-start')->last();
        $_end = $regex->match('\}', 'curl-end')->last();

        $global_items = [];
        $global_items[] = $_var = $regex->match('\\$[a-z_]+[a-z0-9_]*\\b', 'f-variable')->last();
        $global_items[] = $_meth_call = $regex->begin('\(', '\)', 'f-func')->last();
        $global_items[] = $_array_call = $regex->begin('\[', '\]', 'f-array')->last();
        $global_items[] = $_notsymbol = $regex->match('\\h*!\\h*', 'f-notsymbol')->last();
        $global_items[] = $_end_instruct = $regex->match('\\s*(;)', 'f-instruct')->last();
        $global_items[] = $_number = $regex->match('(\\d+(\\.\\d+)?|\.\\d+)', 'f-number')->last();
        $global_items[] = $_white_space = $regex->match('[ ]{2,}', 'f-wspace')->last();
        // $global_items[] = $_declare_func = $regex->match('\\b(function)\\s+(&\\h*)?', 'f-func-declare')->last();



        $_f_func = $regex->createPattern([
            'begin' => '\\b(function)\\b(?:(\\s+(&\\s*))?|(\\s*))([a-zA-Z_][a-zA-Z_0-9]*)',
            'end' => '(?<=\}|;)',
            'tokenID' => 'f-func-global-block',
        ]);
        $_f_func->beginCaptures = [
            '2' => [
                'name' => 'func-ref',
                'replaceWith' => " \\1 "
            ],
            '4' => [
                'name' => 'func-space',
                'replaceWith' => "\\1"
            ]
        ];


        // $regex->append($_f_func);

        $global_items[] = $_return_symbol = $regex->match('::', 'f-static-call-symbol')->last();
        $global_items[] = $_return_symbol = $regex->match('\\h*:\\h*', 'f-symbol')->last();
        $global_items[] = $_reserved_word = $regex->match('\\b(as|break|case|continue|default|do|else|elseif|exit|endif|endwhile|enddo|endforeach|false|final|for|foreach|function|global|if|instanceof|int|interface|namespace|null|parent|private|protected|return|self|static|static|string|switch|trait|true|use|var|while|yield)\\b', 'f-reservedword')->last();
        $global_items[] = $_litteral = $regex->match('(@)?(\\\\)?([a-zA-Z_][a-zA-Z_0-9]*)((\\\\([a-zA-Z_][a-zA-Z_0-9]*))+)?', 'f-litteral')->last();
        $global_items[] = $_empty_line = $regex->appendEmptyLineDetection()->last();

        $_rm_white_space = $regex->createPattern(["match" => "\\s+", "replaceWith" => '', 'tokenID' => 'f_nowhite_space']);

        $comments = [
            $_multi_comment,
            $_depth_comment,
            $_doc_comment,
            $line_comment,
        ];


        $_sub_curl_definition = $regex->createPattern([
            "begin" => "(?<=\{)",
            "end" => "(?=\})",
            'tokenID' => 'f-subpattern-curl',
            'scopedBoundary' => true,
            'lineFeed' => true,
            'flags' => ['instruct']
        ]);
        $_sub_curl_definition->patterns = [
            ...$here_doc,
            ...$comments,
            $string,
            $_start,
            $_end,
            ...$global_items,
            $_sub_curl_definition
        ];
        // + | END INSTRUCT
        $_end_instruct->flags = ['instruct'];
        $_end_instruct->lineFeed = true;
        $_end_instruct->replaceWith = "\\1";

        $_f_func->patterns = [
            $regex->createPattern([
                'tokenID' => 'f_end_func_declaration_stop',
                'match' => '(?<=;)',
            ]),
            $_multi_comment,
            $_depth_comment,
            $_doc_comment,
            $line_comment,
            $regex->createPattern([
                'tokenID' => 'f_end_func_declaration',
                'match' => ';',
                'lineFeed' => true,
                'flags' => ['instruct']
            ]),
            $regex->createPattern([
                'tokenID' => 'f_end_return_type',
                'begin' => '(?:\\s*(:)\\s*)',
                'end' => "(?=;|\{)",
                'beginCaptures' => [
                    "0" => [
                        'replaceWith' => '\\1 '
                    ]
                ],
                'patterns' => [
                    ...$comments,
                    $_rm_white_space,
                    $_litteral
                ]
            ]),
            $_start,
            $_end,
            $_sub_curl_definition,
        ];


        $_inbox_patterns = [
            $string,
            ...$here_doc,
            $regex->createPattern([
                'match' => '\\s*(as|=>|=)\\s*',
                'tokenID' => 'f-operator-space',
                //'replaceWith'=>' \\1 ',
                'captures' => [
                    '0' => [
                        "name" => "operator"
                    ]
                ]
            ]),
            $regex->createPattern([
                'match' => '\\s*(;)\\s*',
                'tokenID' => 'f-operator-space',
                'replaceWith' => '\\1 '
            ]),
            $regex->createPattern([
                'match' => '(\+\+|--)\\s+',
                'tokenID' => 'f-operator-space',
                'replaceWith' => '\\1'
            ]),
            $regex->createPattern([
                'match' => '\\s+(?=\))',
                'tokenID' => 'f-operator-space',
                'replaceWith' => ''
            ]),
        ];
        // $_meth_call->beginCaptures = [
        //     'patterns' => [
        //         $regex->createPattern([
        //             'match' => '\\bfunction\\b',
        //             'tokenID' => 'f-f-func-name',
        //             'replaceWith' => '\\1'
        //         ]),
        //         $regex->createPattern([
        //             'match' => '\\h{2,}',
        //             'tokenID' => 'f-multispace',
        //             'replaceWith' => ' '
        //         ]),
        //     ]
        // ];        

        $_meth_call->patterns = [

            $regex->createPattern([
                'match' => '\\s*(=)\\s*',
                'tokenID' => 'f-arg-default',
                'replaceWith' => ' \\1 '
            ]),
            ...$_inbox_patterns,
            $regex->createPattern([
                'match' => '\\s*,\\s*',
                'tokenID' => 'f-arg-separator',
                'replaceWith' => ', '
            ]),
            $_empty_line,
            $_array_call,
            $_meth_call,
            $regex->createPattern([
                'match' => '\\s+',
                'tokenID' => 'f-space',
                'replaceWith' => ''
            ]),

        ];
        $_array_call->patterns = [
            ...$_inbox_patterns,
            $_empty_line,
            $_meth_call,
            $_array_call
        ];
        // pb when added capture
        $_meth_call->captures = [
            '0' => ['name' => 'captures']
        ];
        $_meth_call->replaceWith = "$0";
    }
}