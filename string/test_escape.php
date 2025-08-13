<?php
if (!function_exists('igk_str_escape')) {
    /**
     * use to escape char
     * @param string $str 
     * @param string $list char list as string
     * @return string 
     */
    function igk_str_escape(string $str, string $char_list = "'")
    {
        $tab = str_split($char_list, 1);
        while (count($tab) > 0) {
            $q = array_shift($tab);
            $offset = 0;
            while (false !== ($pos = strpos($str, $q, $offset))) {
                if ($pos == 0) {
                    $str = '\\' . $str;
                    $offset = 1;
                } else {
                    if ($str[$pos - 1] == "\\") {
                        $offset = $pos + 1;
                    } else {
                        $str = substr($str, 0, $pos) . "\\" . substr($str, $pos);
                        $offset = $pos + 1;
                    }
                }
            }
        }
        return $str;
    }
}
echo igk_str_escape("''information") . PHP_EOL;
echo igk_str_escape("T'ai ou") . PHP_EOL;
echo igk_str_escape("partir @dans la visiont '", "!@'") . PHP_EOL;
echo igk_str_escape("already \'escaped") . PHP_EOL;
exit;