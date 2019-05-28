<?php
/**
 * Created by PhpStorm.
 * User: admin-
 * Date: 28.05.19.
 * Time: 20:11
 */

namespace App\Controller;

use App\Entity\Words;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseWriter
{

    public function insert_word($request, EntityManagerInterface $em)
    {
        $word = new Words();
        $word->setWord($request["word"]);
        $word->setPositive($request["positive"]);
        $word->setNegative($request["negative"]);
        $word->setSource(1);

        $em->persist($word);
        $em->flush();
    }
}