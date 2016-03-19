<?php

use Carbon\Carbon;
use Android\Installs\Models\Install;

Route::post('android_install.json', function () {

    $instance_id = post('instance_id');
    $device_id = post('device_id');
    $manufacturer = post('manufacturer');
    $model = post('model');

    if(empty($instance_id))
      return Response::make(json_encode(['result' => 'error', 'reason' => 'empty-instance-id']), 200, array('Content-Type' => 'application/json'));

    if(empty($device_id))
      return Response::make(json_encode(['result' => 'error', 'reason' => 'empty-device-id']), 200, array('Content-Type' => 'application/json'));

    $install = Install::where('device_id','=', $device_id) -> first();
    if($install != null)
      {
          try {
            $old_id = $install -> instance_id;
            $install -> instance_id = $instance_id;
            $install -> manufacturer = $manufacturer;
            $install -> model = $model;
            $install -> updated_at = Carbon::now()->format('Y-m-d H:i:s');
            $install -> save();

            // Same device ID, but new instance ID. User reset device, or re-installed app.
            if($install -> instance_id != $instance_id)
              Event::fire('android.installs.resetInstall', [$old_id, $install]);
          } catch(Exception $e) {}
      } else {
          try {
            $install = new Install;
            $install -> instance_id = $instance_id;
            $install -> device_id = $device_id;
            $install -> manufacturer = $manufacturer;
            $install -> model = $model;
            $install -> save();
            Event::fire('android.installs.newInstall', [$install]);
          } catch(Exception $e) {
            if($e -> getCode() == '23000')
              return Response::make(json_encode(['result' => 'success', 'reason' => 'duplicate']), 200, array('Content-Type' => 'application/json'));
          }
    }

    return Response::make(json_encode(['result' => 'error', 'reason' => '']), 200, array('Content-Type' => 'application/json'));
});
