<?php
/**
 * Created by PhpStorm.
 * User: albertleao
 * Date: 3/10/17
 * Time: 2:29 PM
 */

namespace Mock;

/**
 * Class CodeSnippet
 * @package Mock
 */
class CodeSnippetWebsite
{

    /**
     * @param array $data
     * @return \App\CodeSnippetWebsite
     */
    public static function create($data = [])
    {
        $code_snippet = new \App\CodeSnippetWebsite();
        $code_snippet->company_id = 2;
        $code_snippet->api_key = md5(time());
        $code_snippet->save();

        return $code_snippet;
    }
}