<?php namespace Android\Installs\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use Android\Installs\Models\Install;

class AddExtrasColumn extends Migration
{

    public function up()
    {
        Schema::table('android_installs', function($table)
        {
            $table->text('extras') -> after('device_id');
        });

        foreach(Install::all() as $install)
        {
          $extras = [
            'manufacturer' => $install -> manufacturer,
            'model' => $install -> model
          ];
          $install -> extras = $extras;
          $install -> save();
        }

        Schema::table('android_installs', function($table)
        {
            $table -> dropColumn('manufacturer');
            $table -> dropColumn('model');
        });

    }

    public function down()
    {
        Schema::table('android_installs', function($table)
        {
            $table -> string('manufacturer') -> after('device_id');
            $table -> string('model') -> after('manufacturer');
        });

        foreach(Install::all() as $install)
        {
          $extras = $install -> extras;
          $install -> manufacturer = $extras['manufacturer'];
          $install -> model = $extras['model'];
          $install -> save();
        }

        Schema::table('android_installs', function($table)
        {
            $table -> dropColumn('extras');
        });
    }
}
