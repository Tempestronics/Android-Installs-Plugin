<?php namespace Android\Installs\Classes;

use Event;
use Exception;
use Carbon\Carbon;
use Android\Installs\Models\Install;
use Android\Installs\Models\Settings;

class InstallUtils
{

  // This value is set when result is success, otherwise null
  protected static $install;

  public static function isSuccess()
  {
      return !is_null(self::$install);
  }

  public static function getInstall()
  {
    return self::$install;
  }

  public static function pushInstall($instance_id, $device_id, $extras = [])
  {
    if(!isset($instance_id) || empty($instance_id))
      return ['result' => 'error', 'reason' => 'empty-instance-id'];

    if(!isset($device_id) || empty($device_id))
      return ['result' => 'error', 'reason' => 'empty-device-id'];

    $extrasSetting = Settings::get('extras');
    if(isset($extras))
    {
      foreach($extrasSetting as $key => $value)
        {
          $name = $value['name'];
          if(!array_key_exists($name, $extras))
            return ['result' => 'error', 'reason' => 'missing-' . $name];
        }
    }

    $install = Install::where('device_id','=', $device_id) -> first();
    if($install != null)
      {
          try {
            $old_id = $install -> instance_id;
            $install -> instance_id = $instance_id;
            $install -> extras = $extras;
            $install -> updated_at = Carbon::now()->format('Y-m-d H:i:s');
            $install -> save();

            // Same device ID, but new instance ID. User reset device, or re-installed app.
            if($install -> instance_id != $instance_id)
              Event::fire('android.installs.resetInstall', [$old_id, $install]);

            $existingInstallResponses = Event::fire('android.installs.existingInstall', [$install]);
            foreach($existingInstallResponses as $existingInstallResponse)
              {
                if(isset($existingInstallResponse['result']) && $existingInstallResponse['result'] == "error")
                  return $existingInstallResponse;
              }

            self::$install = $install;
            return ['result' => 'success', 'reason' => 'existing-install'];
          } catch(Exception $e) {}
      } else {
          try {
            $install = new Install;
            $install -> instance_id = $instance_id;
            $install -> device_id = $device_id;
            $install -> extras = $extras;
            $install -> save();
            $newInstallResponses = Event::fire('android.installs.newInstall', [$install]);
            foreach($newInstallResponses as $newInstallResponse)
              {
                if(isset($newInstallResponse['result']) && $newInstallResponse['result'] == "error")
                  return $newInstallResponse;
              }

            self::$install = $install;
            return ['result' => 'success', 'reason' => 'new-install'];
          } catch(Exception $e) {
            if($e -> getCode() == '23000')
              return ['result' => 'error', 'reason' => 'duplicate'];
          }
      }

    return ['result' => 'error', 'reason' => 'unknown'];
  }
}
