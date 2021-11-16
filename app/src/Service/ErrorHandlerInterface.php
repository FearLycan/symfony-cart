<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;

interface ErrorHandlerInterface
{
    public function formHandler(FormInterface $formErrors);
}