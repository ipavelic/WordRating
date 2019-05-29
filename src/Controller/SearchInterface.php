<?php
/**
 * Created by PhpStorm.
 * User: admin-
 * Date: 28.05.19.
 * Time: 19:51
 */

namespace App\Controller;

interface SearchInterface{

    public function getRocks($word):int;

    public function getSucks($word):int;
}