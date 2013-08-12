<?php

namespace Browser;

/**
 * Autoloads Browser classes
 *
 * @package browser
 */
class Autoloader
{
    /**
     * Register the autoloader
     *
     * @return  void
     */
    public static function register()
    {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self, 'autoload'));
    }

    /**
     * Autoloader
     *
     * @param   string
     * @return  void
     */
    public static function autoload($class)
    {
        if (0 !== strpos($class, 'Browser\\')) {
            return;
        } else if (file_exists($file = dirname(__FILE__) . '/' . preg_replace('!^Browser\\\!', '', $class) . '.php')) {
            require $file;
        }
    }
}