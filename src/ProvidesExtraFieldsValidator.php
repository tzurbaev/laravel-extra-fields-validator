<?php

declare(strict_types=1);

namespace Laravel\ExtraFieldsValidator;

trait ProvidesExtraFieldsValidator
{
    public function validator()
    {
        return $this->makeExtraFieldsValidator();
    }

    protected function makeExtraFieldsValidator()
    {
        /** @var ValidationFactory $factory */
        $factory = $this->container->make(ValidationFactory::class);
        $rules = method_exists($this, 'rules') ? $this->container->call([$this, 'rules']) : [];
        $data = $this->validationData();

        /** @var Validator $validator */
        $validator = $factory->make($data, $rules, $this->messages(), $this->attributes());

        return $validator->afterSuccess(function (Validator $validator) {
            /** @var ExtraFieldsDetector $detector */
            $detector = $this->container->make(
                config('extra-validator.extra_fields_detector', ExtraFieldsDetector::class)
            );

            $extraField = $detector->getFirstExtraField($validator->getData(), $validator->getRules());

            if (is_null($extraField)) {
                return true;
            }

            $validator->errors()->add($extraField, $this->getExtraFieldErrorMessage($extraField));

            return false;
        });
    }

    public function getExtraFieldErrorMessage(string $field): string
    {
        return trans('validation.custom.extra_field', ['attribute' => $field]);
    }
}
