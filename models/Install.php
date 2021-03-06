<?php namespace Android\Installs\Models;

use Model;

/**
 * Model
 */
class Install extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Validation
     */
    public $rules = [
        'instance_id' => 'required',
        'device_id' => 'required'
    ];

    public $jsonable = [
        'extras'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'android_installs';
}
