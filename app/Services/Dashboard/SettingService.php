<?php
namespace App\Services\Dashboard;

use App\Models\Setting;
use App\Traits\HasImage;

class SettingService
{
    use HasImage;
    public function __construct(public Setting $model)
    {}

    public function show()
    {

        $setting = $this->model->first();
        return view("dashboard.pages.setting.edit", compact("setting"));
    }
    public function update($data)
    {
        $setting = $this->model->first();
        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'settings');
        }
        if ($setting) {
            $setting->update($data);
        } else {
            $this->model->create($data);
        }
    }

}
