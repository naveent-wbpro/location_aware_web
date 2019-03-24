<?php
/**
 * Created by PhpStorm.
 * User: albertleao
 * Date: 3/7/17
 * Time: 12:37 PM
 */

namespace Mock;

/**
 * Class AppToken
 * @package Mock
 */
class AppToken
{
    /**
     * @param $user_id
     * @return \App\AppToken
     */
    public static function create($user_id)
    {
        $app_token = new \App\AppToken();
        $app_token->user_id = $user_id;
        $app_token->access_token = str_random(40);
        $app_token->save();

        return $app_token;
    }
}