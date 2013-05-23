<?php
if( !defined('cc') ) exit();

class CHBlog extends ActiveRecord
{
    public function __construct()
    {
        parent::register('shopnc_cards','Card_ID');
    }

}
