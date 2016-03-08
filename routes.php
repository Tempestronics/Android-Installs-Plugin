<?php

use Android\Installs\Models\Install;

Route::post('android_install.json', function () {

    $instance_id = post('instance_id');
    $device_id = post('device_id');
    $manufacturer = post('manufacturer');
    $model = post('model');

    // $device_id = '1b2249378929b98d';

    $install = Install::where('device_id','=', $device_id) -> first();
    if($install != null && $install -> instance_id != $instance_id)
      {
          try { // Same device ID, but new instance ID. User reset device, or re-installed app.
            $old_id = $install -> instance_id;
            $install -> instance_id = $instance_id;
            $install -> save();
            Event::fire('android.installs.resetInstall', [$old_id, $this]);
          } catch(Exception $e) {}
      } else {
          try {
            $install = new Install;
            $install -> instance_id = $instance_id;
            $install -> device_id = $device_id;
            $install -> manufacturer = $manufacturer;
            $install -> model = $model;
            $install -> save();
            Event::fire('android.installs.newInstall', [$this]);
          } catch(Exception $e) {
            if($e -> getCode() == '23000')
              return Response::make(json_encode(['result' => 'success', 'reason' => 'duplicate']), 200, array('Content-Type' => 'application/json'));
          }
    }

    return Response::make(json_encode(['result' => 'error', 'reason' => '']), 200, array('Content-Type' => 'application/json'));
});
