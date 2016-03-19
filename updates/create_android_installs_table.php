<?php namespace Android\Installs\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateAndroidInstallsTable extends Migration
{
    public function up()
    {
        Schema::create('android_installs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('instance_id')->unique();
            $table->string('device_id', 16)->unique();
            $table->string('manufacturer');
            $table->string('model');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('android_installs');
    }
}
