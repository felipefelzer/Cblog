<?php
if( !defined('cc')) exit();
class Channel extends APP
{
    private $_channelList;

    private $_chs = array();

    public function SetCh($config)
    {
        if( !empty($config) && is_array($config))
        {
            $this->_channelList = $config;
            return $this;
        }
        return FAILED;
    }

    public function OpenCh()
    {
        foreach( $this->_channelList as $key => $value )
        {
            if( $value = 'true' )
            {
                $path = ROOT.'ch/'.$key.'.php';
                if( file_exists($path) )
                {
                    include $path;
                    $this->_channelList[$key] = new $key;
                }
            }
        }

        $b = new CHBlog();
        return $this;
    }

    public function SelectCh($ch)
    {

    }

    public function DelCh($Ch)
    {

    }

    public function AddCh($ch)
    {

    }

    public function UpdateCh($ch)
    {

    }

    public function _loadCh()
    {

    }

}
