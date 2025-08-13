<?php
// @command: balafon --run .test/get_width_column.list.php
use IGK\Models\Users;
        $conditions = [];
        $cl =['tbigk_users.clLogin'];//  Users::columnList(null, '/clPwd/');
        $options = [
            'Limit'=>1,
            'Columns'=> $cl
        ];
        $users = is_null($user) ?Users::select_all($conditions, $options): null;
        igk_wln_e($cl, json_encode($users, JSON_PRETTY_PRINT));