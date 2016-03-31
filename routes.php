<?php

use Android\Installs\Classes\InstallUtils;

Route::post('android_install.json', function () {

    $instance_id = post('instance_id');
    $device_id = post('device_id');
    $manufacturer = post('manufacturer');
    $model = post('model');

    // $instance_id = 'fl_6i-xgTjz';
    // $device_id = 'adec9dd52b1e4cd7';
    // $manufacturer = 'Coolpad';
    // $model = 'test';

    if(isset($model) && isset($manufacturer)) // For backward compatibility with < v1.0.4
      {
        $extras = [
          'manufacturer' => $manufacturer,
          'model' => $model
        ];
      }
    else
      $extras = json_decode(post('extras'));

    $result = InstallUtils::pushInstall($instance_id, $device_id, $extras);
    return Response::make(json_encode($result), 200, array('Content-Type' => 'application/json'));
});
