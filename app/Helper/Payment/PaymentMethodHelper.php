<?php

namespace App\Helper\Payment;

use App\Helper\Payment\Method\IMethod;

/**
 * Payment method Helper
 */
class PaymentMethodHelper
{
    protected IMethod $method;

    public function __construct(IMethod $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Pay using payment method
     */
    public function store()
    {
        return $this->method->store();
    }

    /**
     * Get payment info
     */
    public function retrieve($id)
    {
        return $this->method->retrieve($id);
    }
}
