<?php
namespace App\Services\Dashboard;

use App\Models\PrivacyPolicy;
use App\Traits\HasImage;

class PrivacyPolicyService
{
    use HasImage;
    public function __construct(public PrivacyPolicy $model)
    {}

    public function show()
    {

        $privacy_policy = $this->model->first();
        return view("dashboard.pages.privacy_policy.edit", compact("privacy_policy"));
    }
    public function update($data)
    {
        $privacy_policy = $this->model->first();

        if ($privacy_policy) {
            $privacy_policy->update($data);
        } else {
            $this->model->create($data);
        }
    }

}
