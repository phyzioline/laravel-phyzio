<?php
namespace App\Services\Dashboard;

use App\Models\ShippingPolicy;
use App\Traits\HasImage;

class ShippingPolicyService
{
    use HasImage;
    public function __construct(public ShippingPolicy $model)
    {}

    public function show()
    {

        $shipping_policy = $this->model->first();
        return view("dashboard.pages.shipping_policy.edit", compact("shipping_policy"));
    }
    public function update($data)
    {
        $shipping_policy = $this->model->first();
        
        if ($shipping_policy) {
            $shipping_policy->update($data);
        } else {
            $this->model->create($data);
        }
    }

}
