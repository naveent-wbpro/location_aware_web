<?php
/**
 * Created by PhpStorm.
 * User: albertleao
 * Date: 3/14/17
 * Time: 2:36 PM
 */

namespace Mock;

/**
 * Class ApiToken
 * @package Mock
 */
class ApiToken
{
    /**
     * @param array $data
     * @return \App\ApiToken
     */
    public static function create($data = [])
    {
        $token = new \App\ApiToken();
        $token->company_id = $data['company_id'];
        $token->access_token = md5(time());
        $token->save();

        return $token;
    }
}