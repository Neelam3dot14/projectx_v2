<?php


namespace App\Repositories\Tracer;


interface Traceable
{
    public function trace($link, $dataArray);
}
