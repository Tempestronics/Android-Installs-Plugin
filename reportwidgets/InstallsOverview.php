<?php namespace Android\Installs\ReportWidgets;

use Exception;
use Carbon\Carbon;
use ApplicationException;
use Android\Installs\Models\Install;
use Backend\Classes\ReportWidgetBase;

 /**
 * App Installs overview widget.
 *
 * @package backend
 * @author Saifur Rahman Mohsin
 */
class InstallsOverview extends ReportWidgetBase
{
    /**
     * Renders the widget.
     */
    public function render()
    {
        try {
            $this->loadData();
        }
        catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }
        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title'             => 'backend::lang.dashboard.widget_title_label',
                'default'           => e(trans('android.installs::lang.widgets.title_installs')),
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error'
            ],
            'days' => [
                'title'             => 'Number of days to display data for',
                'default'           => '30',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$'
            ]
        ];
    }
    protected function loadData()
    {
        $days = $this->property('days');
        if (!$days)
            throw new ApplicationException('Invalid days value: '.$days);

        $installs = Install::select('created_at')
          ->orderBy('created_at', 'desc')
          ->where('created_at', '>=', Carbon::now()->subDays($days))
          ->get()
          ->groupBy(function($date) {
              return Carbon::parse($date->created_at)->format('d M'); // grouping by years
          });

        $points = [];
        foreach ($installs as $key => $value)
        {
            $point = [
                strtotime("+1 day", strtotime($key)) * 1000,
                count($value)
            ];
            $points[] = $point;
        }

        $this->vars['rows'] = str_replace('"', '', substr(substr(json_encode($points), 1), 0, -1));
    }

}
