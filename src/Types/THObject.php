<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 09/07/2017
 * Time: 16:41
 */

namespace THP\Types;


class THObject
{
    public function call($parameters)
    {
        throw new \Exception("Hata!Bu obje çağrılamaz");
    }

    public function add($parameters)
    {
        throw new \Exception("Hata!Bu obje toplanamaz");
    }

    public function mul($parameters)
    {
        throw new \Exception("Hata!Bu obje çarpılamaz");
    }

    public function div($parameters)
    {
        throw new \Exception("Hata!Bu obje bölünemez");
    }

    public function sub($parameters)
    {
        throw new \Exception("Hata!Bu obje çıkarılamaz");
    }
}