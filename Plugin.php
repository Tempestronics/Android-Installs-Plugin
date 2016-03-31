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
                        'icon'        => 'icon-download',
                        'url'         => Backend::url('android/installs/installs'),
                        'permissions' => ['android.installs.view_installs']
                    ]
                ]
            ]
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Settings',
                'description' => 'Manage the android settings.',
                'category'    => 'Android',
                'icon'        => 'icon-android',
                'class'       => 'Android\Installs\Models\Settings',
                'order'       => 100,
                'keywords'    => 'android settings',
                'permissions' => ['android.installs.access_settings']
            ]
        ];
    }

    public function registerPermissions()
    {
        return [
            'android.installs.view_installs' => [
                'label' => 'android.installs::lang.install.view_installs',
                'tab' => 'android.installs::lang.plugin.android'
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
