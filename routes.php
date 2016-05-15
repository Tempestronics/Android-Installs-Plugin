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
      $extras = json_decode(post('extras'), true);

    $result = InstallUtils::pushInstall($instance_id, $device_id, $extras);

    if(InstallUtils::isSuccess())
      {
        $responses = Event::fire('android.installs.postVerifyInstall', [InstallUtils::getInstall()]);
        //if(isset($response[0]['result']))
        foreach($responses as $response)
          {
            if(isset($response['result']) && $response['result'] == 'error')
              {
                $result = $response;
                break;
              }
          }
      }

    return Response::make(json_encode($result), 200, array('Content-Type' => 'application/json'));
});
