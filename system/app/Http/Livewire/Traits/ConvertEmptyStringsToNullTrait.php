<?php

namespace App\Http\Livewire\Traits;

use Livewire\Request;

trait ConvertEmptyStringsToNullTrait
{
    /**
     * @var string[]
     */
    protected $convertEmptyStringsExcept = [
        //
    ];

    /**
     * @param string $name
     * @param mixed $value
     */
    public function updatedConvertEmptyStringsToNullTrait(string $name, $value): void
    {
        if (! is_string($value) || in_array($name, $this->convertEmptyStringsExcept)) {
            return;
        }

        $value = trim($value);
        $value = $value === '' ? null : $value;

        data_set($this, $name, $value);
    }
}