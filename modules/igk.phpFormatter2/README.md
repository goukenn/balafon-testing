regex : ^$d used for global dreference \\1 use for the trimmed capture data 

- \\1 the trimmed value
- \\0 all captured value
- $`number` to retrieve the captured the group captured data 

```php
[
    // ...
'tokenID' => 'f_end_return_type',
                'begin' => '(?:\\s*(:)\\s*)',
                'end' => "(?=;|\{)",
                'beginCaptures' => [
                    "1" => [
                        "name" => "jump",
                        "replaceWith" => "\\1"
                    ],
                    "2" => [
                        "name" => "jump",
                        "replaceWith" => ""
                    ],
                    "0" => [
                        'replaceWith' => '\\1 -- $1 '
                    ]
                ],
// ...
```