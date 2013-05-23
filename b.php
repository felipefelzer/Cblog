<?php
if( !defined('cc') ) exit();
class APP
{

    private $_version = '0.1';

    private $_blogNmae = 'charlie';
    
    private $_defaultBlogName = '';

    private $_DB = NULL;

    private $_isCustom = true;

    private static $_conf = null;

    public $Stat = array();

    private $_user = null;

    public function Init()
    {
        if(!spl_autoload_register(array($this , 'Loader')))
            exit('Loader error');

        if(!Config::SetConf())
            exit('config error');
        
        $ch = new Channel();
        $chList = Config::GetConf('channel');
        if( $chList != FAILED )
            $chs = $ch->SetCh( $chList )
                    ->OpenCh();
        else
            exit();

        /*
        $view  = new View();
        $view->prepare
        */ 
    }

    public static function Loader($class,$path = CLASS_PATH)
    {

        if( substr($class,0,2) == 'CH' )
            $path = CH_PATH;

        $file = $path.$class.'.php';
        echo $path.'<br>';
        if(file_exists($file))
        {
            include $file;
        }else{
            return FAILED;
        }
        return SUCCESS;
    }

    public function WhoAmI()
    {
        return empty($this->$_user) ? 'no one' : $this->$_user;
    }


    public function GetVersion()
    {
        return $this->_version;
    }

}
