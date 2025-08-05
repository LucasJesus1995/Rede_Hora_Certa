<?php

namespace App\Http\Traits\Requests;

use Dingo\Api\Exception\ResourceException;
use Illuminate\Contracts\Validation\Validator;

trait FormRequestTrait
{

    /**
     * {@inheritdoc}
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->is(config('api.prefix') . '/*')) {
            throw new ResourceException('Invalid request', $validator->getMessageBag());
        }

        parent::failedValidation($validator);
    }

}