<?php

declare(strict_types=1);

namespace Laravel\ExtraFieldsValidator;

use Illuminate\Validation\Validator as BaseValidator;

class Validator extends BaseValidator
{
    private array $extraCallbacks = [];

    public function afterSuccess(callable $callback): Validator
    {
        return $this->registerExtraCallback('success', $callback);
    }

    public function afterFailure(callable $callback): Validator
    {
        return $this->registerExtraCallback('fail', $callback);
    }

    public function fails()
    {
        $fails = !$this->passes();

        if ($fails) {
            $this->runExtraCallbacks('fail');
        } else {
            $fails = $this->runExtraCallbacks('success') === false;
        }

        return $fails;
    }

    private function registerExtraCallback(string $type, callable $callback): Validator
    {
        if (!isset($this->extraCallbacks[$type]) || !is_array($this->extraCallbacks[$type])) {
            $this->extraCallbacks[$type] = [];
        }

        $this->extraCallbacks[$type][] = fn () => call_user_func_array($callback, [$this]);

        return $this;
    }

    private function runExtraCallbacks(string $type): bool
    {
        if (empty($this->extraCallbacks[$type])) {
            return true;
        }

        foreach ($this->extraCallbacks[$type] as $callback) {
            if ($callback() === false) {
                return false;
            }
        }

        $this->extraCallbacks[$type] = [];

        return true;
    }
}
