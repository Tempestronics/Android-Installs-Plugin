<?php namespace Android\Installs;

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
            'icon'        => 'oc-icon-android'
        ];
    }

    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }
}
