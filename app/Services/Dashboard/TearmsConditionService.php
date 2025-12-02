<?php
namespace App\Services\Dashboard;

use App\Models\TearmsCondition;
use App\Traits\HasImage;

class TearmsConditionService
{
    use HasImage;
    public function __construct(public TearmsCondition $model)
    {}

    public function show()
    {

        $tearms_conditions = $this->model->first();
        return view("dashboard.pages.tearms_conditions.edit", compact("tearms_conditions"));
    }
    public function update($data)
    {
        $tearms_conditions = $this->model->first();

        if ($tearms_conditions) {
            $tearms_conditions->update($data);
        } else {
            $this->model->create($data);
        }
    }

}
