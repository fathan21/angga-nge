<?php


if (!function_exists('random_string')) {
    function random_string($length = 8, $numberOnly = false)
    {
        $password = '';
        $sets = array();
        $sets[] = '0123456789';
        if (!$numberOnly) {
            $sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        }

        // $sets[] = 'abcdefghijklmnopqrstuvwxyz';

        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }

        while (strlen($password) < $length) {
            $randomSet = $sets[array_rand($sets)];
            $password .= $randomSet[array_rand(str_split($randomSet))];
        }

        return str_shuffle($password);
    }
}

if (!function_exists('auth_user')) {
    function auth_user()
    {
        return request()->get('auth_user');
    }
}
