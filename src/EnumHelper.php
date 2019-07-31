<?php
/**
 * Datetime: 18.10.2018 19:27
 * @author Timur Kasumov aka XAKEPEHOK
 */

namespace XAKEPEHOK\EnumHelper;


use XAKEPEHOK\EnumHelper\Exception\ForgottenSwitchCaseException;
use XAKEPEHOK\EnumHelper\Exception\NotEqualsAssociationException;
use XAKEPEHOK\EnumHelper\Exception\OutOfEnumException;
use XAKEPEHOK\EnumHelper\Exception\UnexpectedSwitchCaseValueException;

abstract class EnumHelper
{


    /**
     * @param mixed $case value, used for switch expression
     * @param callable[]|array $switchCaseOrCallbacks associative array like 'case' => callback()
     * @param callable|null $caseConvert, callback, which can convert value to $switchCaseCallbacks array
     * @return mixed
     * @throws ForgottenSwitchCaseException
     * @throws UnexpectedSwitchCaseValueException
     */
    public static function switchCase($case, array $switchCaseOrCallbacks, callable $caseConvert = null)
    {
        if (is_null($caseConvert)) {
            $caseConvert = static::caseConvertCallback();
        }

        $diff = array_diff(static::values(), array_keys($switchCaseOrCallbacks));
        if (!empty($diff)) {
            throw new ForgottenSwitchCaseException(implode(", ", $diff));
        }

        $caseConverted = $caseConvert($case);
        foreach ($switchCaseOrCallbacks as $key => $callbackOrValue) {
            if ($key == $caseConverted) {
                return is_callable($callbackOrValue) ? $callbackOrValue($case) : $callbackOrValue;
            }
        }

        throw new UnexpectedSwitchCaseValueException('Unexpected value "' . $caseConverted . '"');
    }

    /**
     * @param array $array
     * @param callable|null $keysConvert
     * @return array
     * @throws NotEqualsAssociationException
     */
    public static function associative(array $array, callable $keysConvert = null)
    {
        if ($keysConvert === null) {
            $keysConvert = function ($key) {
                return $key;
            };
        }
        $diff = array_diff(static::values(), array_keys($array));
        if (!empty($diff)) {
            throw new NotEqualsAssociationException(implode(", ", $diff));
        }

        $result = [];
        foreach ($array as $key => $value) {
            $result[$keysConvert($key)] = $value;
        }

        return $result;
    }

    public static function caseConvertCallback(): callable
    {
        return function ($value) {
            return $value;
        };
    }

    public static function isValid($value): bool
    {
        return in_array($value, static::values(), false);
    }

    /**
     * @param $value
     * @throws OutOfEnumException
     */
    public static function guardValidValue($value)
    {
        if (!static::isValid($value)) {
            throw new OutOfEnumException("Value '{$value}' is not in enum list of class " . static::class);
        }
    }

    abstract public static function values(): array;

}