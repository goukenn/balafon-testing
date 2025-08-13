<?php
echo $ctrl->model('RecycleForms')->select_row(1)->getRecylesProductDetails()->to_json(
    null,
    JSON_PRETTY_PRINT); 
exit;