<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class WeekOfYear implements Rule
{
    private mixed $year;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($year)
    {
        $this->year = $year;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (is_numeric($this->year)) {
            if (Carbon::canBeCreatedFromFormat($this->year, 'Y')) {
                $yearDate = Carbon::createFromFormat('Y', $this->year);

                return (int) $value <= $yearDate->isoWeeksInYear;
            }
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute exceeds number of weeks in specified year.';
    }
}
