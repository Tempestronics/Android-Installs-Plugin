<?php namespace Android\Installs;

use Backend;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'android.installs::lang.plugin.name',
            'description' => 'android.installs::lang.plugin.description',
            'author'      => 'Android',
            'icon'        => 'oc-icon-android',
            'homepage'    => 'tempestronics.com'
        ];
    }

    public function registerNavigation()
    {
        return [
            'android' => [
                'label'       => 'android.installs::lang.plugin.android',
                'url'         => Backend::url('android/installs/installs'),
                'icon'        => 'icon-android',
                'permissions' => ['android.*'],
                'order'       => 500,

                'sideMenu' => [
                    'installs' => [
                        'label'       => 'android.installs::lang.plugin.name',
                        'icon'        => 'icon-user-plus',
                        'url'         => Backend::url('android/installs/installs'),
                        'permissions' => ['android.installs.view_installs']
                    ]
                ]
            ]
        ];
    }

    public function registerPermissions()
    {
        return [
            'android.installs.view_installs' => [
                'label' => 'android.installs::lang.install.view_installs',
                'tab' => 'android.installs::lang.plugin.full_name'
            ]
        ];
    }

    public function registerReportWidgets()
    {
        return [
            'Android\Installs\ReportWidgets\InstallsOverview'=>[
                'label'   => 'App Installs Overview',
                'context' => 'dashboard'
            ]
        ];
    }
}
