<?php

namespace App\Service;

use App\Service\Error\ServiceError;

class Validator
{
    /**
     * @throws ServiceError
     */
    final public function check($criteria, string $errorText): void
    {
        if (!$criteria) {
            throw new ServiceError($errorText);
        }
    }

    /**
     * @throws ServiceError
     */
    final public function checkAll(array $rules): void
    {
        foreach ($rules as $rule) {
            [$criteria, $message] = $rule;
            $this->check($criteria, $message);
        }
    }
}
