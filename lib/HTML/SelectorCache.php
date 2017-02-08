<?php
/*
Copyright 2015 Lcf.vs
 -
Released under the MIT license
 -
https://github.com/Lcfvs/PHPDOM
*/
namespace PHPDOM\HTML;

final class SelectorCache
{
    protected static $_cache;
    protected static $_isUptoDate = true;
    protected static $_filename;

    private function __construct()
    {}
    
    public static function get($selector)
    {
        $query = static::$_cache->{$selector} ?? null;

        if ($query) {
            return $query;
        }
    }
    
    public static function set($selector, $query)
    {
        static::$_isUptoDate = false;
    
        return static::$_cache->{$selector} = $query;
    }
    
    public static function load($filename)
    {
        if (static::$_filename) {
            return;
        }

        static::$_filename = str_replace('\\', '/', $filename);
        static::$_isUptoDate = true;
        static::$_cache = json_decode(@file_get_contents($filename)) ?? new \stdClass();
    }
    
    public static function clear()
    {
        if (is_file(static::$_filename)) {
            unlink(static::$_filename);
        }
        
        static::$_isUptoDate = true;
        static::$_cache = new \stdClass();
    }
    
    public static function save()
    {
        if (static::$_isUptoDate) {
            return;
        }

        $directory = dirname(static::$_filename);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        
        file_put_contents(static::$_filename, json_encode(static::$_cache));
    }
}