<?php
use igk\js\common\JSExpression;
echo JSExpression::Stringify((object)[
    "components"=>["one", "./data/presentations"]
]);
igk_wln_e("done");