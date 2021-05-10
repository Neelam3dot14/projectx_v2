<?php


namespace App\Repositories\Tracer;


class TracerFactory
{
    public static function getTracer()
    {
        $crawlerClass = self::availableTracers();
        $object = new $crawlerClass;
        return $object;
    }

    public static function availableTracers()
    {
        $data = [
            NodeTracer::class,
        ];
        return $data[array_rand( $data,1)];
    }
}
