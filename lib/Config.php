<?php
if( !defined('cc')) exit();
class Config
{

    private static $_conf = null;

    /*
     *  SetConf
     *  @$config 二维数组
     *  如果IsUpdate = true 之前设置好的参数将被覆盖
     */
    public static function SetConf($config = null, $value = null)
    {
        if( empty($config) )
        {
            Config::$_conf = self::GetConf();
        }else
        { 
            if( is_array($value) )
            {
                Config::$_conf[$config] = array_replace(Config::$_conf[$config],$value);
            }else{
                return FAILED;
            }
        }
        return SUCCESS;
    }

    public static function GetConf($name = null)
    {
        if( !empty(Config::$_conf) )
        {
            if( !empty($name) )
            {
                if( array_key_exists($name, Config::$_conf) )
                    return Config::$_conf[$name];
            }else{
                return Config::$_conf;
            }
        }elseif( file_exists(CONF_PATH) )
        {
                return parse_ini_file(CONF_PATH,true);
        }
        return FAILED;
    }
}
