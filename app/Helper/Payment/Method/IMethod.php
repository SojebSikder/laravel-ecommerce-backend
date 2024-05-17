<?php

namespace App\Helper\Payment\Method;

/**
 * Stripe Payment method
 */
interface IMethod
{
    /**
     * Store payment
     */
    public function store();
    /**
     * Retrieve payment
     */
    public function retrieve($id);
}
