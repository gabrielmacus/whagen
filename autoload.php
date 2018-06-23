<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 22/06/2018
 * Time: 20:32
 */

$_ENV["lang"]=substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

$envs = ["production","development"];

foreach ($envs as $env):
    $path=__DIR__."/env/{$env}.php";
    if(file_exists($path)):
        include($path);
    endif;
endforeach;

$langPath = __DIR__."/lang/{$_ENV["lang"]}.php";
if(!file_exists($langPath))
{
 $langPath =  __DIR__."/lang/en.php";
}
include ($langPath);