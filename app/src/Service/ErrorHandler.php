<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;

class ErrorHandler implements ErrorHandlerInterface
{

    public function formHandler(FormInterface $form)
    {
        $errors['errors'] = [];

        foreach ($form->all() as $child) {
            foreach ($child->getErrors() as $error) {
                $name = $child->getName();
                $errors['errors'][$name] = $error->getMessage();
            }
        }

        return $errors;
    }
}