<?php namespace Android\Installs\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Installs extends Controller
{
    public $implement = ['Backend\Behaviors\ListController'];
    
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = [
        'android.installs.view_installs' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Android.Installs', 'android', 'installs');
    }
}