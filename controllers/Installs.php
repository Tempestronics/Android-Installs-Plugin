<?php namespace Android\Installs\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Android\Installs\Models\Settings;

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

    public function listExtendColumns($widget)
    {
      $extras = Settings::get('extras');
      if(isset($extras))
      {
        foreach($extras as $key => $value)
          {
            if($value['showInList'] == '1')
              {
                  $widget->addColumns([
                      $value['name'] => [
                          'label' => ucfirst($value['name'])
                      ]
                  ]);
              }
          }
        }
    }

    public function listOverrideColumnValue($record, $columnName)
    {
        $extras = Settings::get('extras');
        if(isset($extras))
        {
          foreach($extras as $key => $value)
            {
              if($value['showInList'] == '1')
                {
                  if( $columnName == $value['name'] )
                     return isset($record->extras[$value['name']]) ? $record->extras[$value['name']] : null;
                }
            }
        }
    }
}
