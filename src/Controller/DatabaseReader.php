<?php
/**
 * Created by PhpStorm.
 * User: admin-
 * Date: 28.05.19.
 * Time: 20:08
 */

namespace App\Controller;

use App\Entity\Words;

class DatabaseReader
{

    public function search_word_db($word, $repository)
    {
        $res = $repository->findOneByWord($word);
        return $res;
    }

}