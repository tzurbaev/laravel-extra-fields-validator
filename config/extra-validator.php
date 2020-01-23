<?php

return [
    'extra_fields_detector' => Laravel\ExtraFieldsValidator\ExtraFieldsDetector::class,
    'data_source' => env('EXTRA_VALIDATOR_DATA_SOURCE', 'default'),
];
