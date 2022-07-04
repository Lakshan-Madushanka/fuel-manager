<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NICValidator implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function no__construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (strlen($value) == 10) {
            return $this->validateOldNIC($value);
        }

        if (strlen($value) == 12) {
            return $this->validateNewNIC($value);
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
        return 'Invalid nic number.';
    }

    public function validateNewNIC(string $nic): bool
    {
        //this is the new id card number
        $year = ltrim($nic[0].$nic[1].$nic[2].$nic[3], "0"); //this will erase preceding zeros
        if ($year > 1900) {
            //year should not be larger than current year

            if ($year > date("Y") - 15) {
                return false;
            } else {
                $checkingPart = ltrim($nic[4].$nic[5].$nic[6], "0");

                if ($checkingPart <= 0 or $checkingPart > 866) {
                    return false;
                } else {
                    return true;
                }
            }
        }

        return false;
    }

    public function validateOldNIC(string $nic): bool
    {
        $lastLetter = $nic[9];
        if ($lastLetter == "v" or $lastLetter == "V" or $lastLetter == "x" or $lastLetter == "X") {
            $year = ltrim($nic[0].$nic[1], "0"); //this will erase preceding zeros
            if ($year > 0) {
                $checkingPart = ltrim(
                    $nic[2].$nic[3].$nic[4],
                    "0"
                ); //this number indicates the birthday it should be larger than 0 and smaller than 866

                if ($checkingPart <= 0 or $checkingPart > 866) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }

        return false;
    }
}
