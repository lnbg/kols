<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\FacebookProfile;

/**
 * Class FacebookProfileTransformer.
 *
 * @package namespace App\Transformers;
 */
class FacebookProfileTransformer extends TransformerAbstract
{
    /**
     * Transform the FacebookProfile entity.
     *
     * @param \App\Entities\FacebookProfile $model
     *
     * @return array
     */
    public function transform(FacebookProfile $model)
    {
        return [
            'id'         => (int) $model->id,
            /* place your other model properties here */
            'facebook_id' => (string) $model->facebook_id,
            'type' => (int) $model->type,
            'name' => (string) $model->name,
            'username' => (string) $model->username,
            'fan_count' => (int) $model->fan_count,
            'verification_status' => (string) $model->verification_status,
            'picture' => (string) $model->picture
        ];
    }
}
