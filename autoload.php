<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 22/06/2018
 * Time: 20:32
 */


$envs = ["production","development"];

foreach ($envs as $env):
    $path=__DIR__."/env/{$env}.php";
    if(file_exists($path)):
        include($path);
    endif;
endforeach;

function logError($data)
{
    file_put_contents("error.log",$data);
}