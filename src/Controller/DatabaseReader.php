<?php
/**
 * Created by PhpStorm.
 * User: admin-
 * Date: 28.05.19.
 * Time: 20:08
 */

namespace App\Controller;

class DatabaseReader
{

    public function search_word_db($word, $repository)
    {
        $res = $repository->findOneBy(['word' => $word]);
        return $res;
    }

}