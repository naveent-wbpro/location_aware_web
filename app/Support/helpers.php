<?php


function EncodingASCII($input) {
    $output = '';
    $length = strlen($input);
    for ($i=0; $i < $length; $i++) {
        $output .= ord($input[$i]);
    }

    return $output;
}

/**
 * @return float
 */
function reverse_rating($rating) {
    return 5 - $rating;
}

function clean_number_format($number) {
    $formatted = number_format($number, 2);

    return (float) str_replace('.00', '', $formatted);
}

function user_is($auth_type)
{
    $user_role_id = \Auth::user()->role_id;

    if (($auth_type === 1 || $auth_type === 'admin') && $user_role_id <= 1) {
        return true;
    }
    if (($auth_type === 2 || $auth_type === 'company_super_admin') && $user_role_id <= 2) {
        return true;
    }
    if (($auth_type === 3 || $auth_type === 'company_admin') && $user_role_id <= 3) {
        return true;
    }
    if (($auth_type === 4 || $auth_type === 'company_employee') && $user_role_id <= 4) {
        return true;
    }
    return false;
}

