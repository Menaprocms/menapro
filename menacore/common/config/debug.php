<?php
/**
 *
 * PLEASE: DELETE THIS FILE BEFORE GOING TO PRODUCTION, OR ADD TO GITIGNORE
 */

function l($data)
{
//    if(defined('YII_DEBUG') && YII_DEBUG==true)
    if(true) //Desactivado
    {
        echo "<br>********" . get_caller_info() . "********<br>";
        if (is_object($data)) {
            var_dump($data);
        } else if (is_array($data)) {
            echo nl2br(print_r($data, true));
        } else {
            echo $data;
        }
        echo "<br>********";

    }
}


function get_caller_info() {
    $c = '';
    $file = '';
    $func = '';
    $class = '';
    $trace = debug_backtrace();
    if (isset($trace[2])) {
        $file = $trace[1]['file'];
        $func = $trace[2]['function'];
        if ((substr($func, 0, 7) == 'include') || (substr($func, 0, 7) == 'require')) {
            $func = '';
        }
    } else if (isset($trace[1])) {
        $file = $trace[1]['file'];
        $func = '';
    }
    if (isset($trace[3]['class'])) {
        $class = $trace[3]['class'];
        $func = $trace[3]['function'];
        $file = $trace[2]['file'];
    } else if (isset($trace[2]['class'])) {
        $class = $trace[2]['class'];
        $func = $trace[2]['function'];
        $file = $trace[1]['file'];
    }
    if ($file != '') $file = basename($file);
    $c = $file . ": ";
    $c .= ($class != '') ? ":" . $class . "->" : "";
    $c .= ($func != '') ? $func . "(): " : "";
    return($c);
}
?>
