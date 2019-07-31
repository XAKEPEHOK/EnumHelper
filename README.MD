# EnumHelper

`\XAKEPEHOK\EnumHelper\EnumHelper` - helper, that can help you work with enum-values (Value-objects) with few helpful 
functions, such as switch-case, guard invalid value, get all values or don't forget use all enum values as some array keys

## Usage

Define you enum class, for example, define ValueObject class `Status` (ValueObject is not necessary, you can not define 
constructor and getter, and use your class as simple helper, but much cases it very useful)

```php
<?php

class Status extends \XAKEPEHOK\EnumHelper\EnumHelper
{
    
    const STATUS_PROCESSING = 'processing';
    const STATUS_APPROVED = 'approved';
    const STATUS_CANCELED = 'canceled';

    private $status;

    public function __construct($status) 
    {
        self::guardValidValue($status);
        $this->status = $status;
    }

    public  function getStatus()
    {
        return $this->status;
    }
    
    public static function values() : array{
        return [
            self::STATUS_PROCESSING,
            self::STATUS_APPROVED,
            self::STATUS_CANCELED,
        ];
    }    
}
```

After that you can use your `Status` object as your domain type. But this helper provide more futures.

### Switch case
Your can easy forgot use one of you status in native php switch-case. Our helper provide alternative switch-case way:
```php
<?php
$statusValue = Status::STATUS_PROCESSING;

$result = Status::switchCase($statusValue, [
    Status::STATUS_PROCESSING => function ($case) {
        //doSomethins();
        return null; //
    },
    Status::STATUS_APPROVED => function ($case) {
        return true;
    },
    Status::STATUS_CANCELED => false,
]);
```

In this function you should provide all your enum values as keys (if you forgot something, exception 
`\XAKEPEHOK\EnumHelper\Exception\ForgottenSwitchCaseException` will be thrown), and use values or callable as result. For
more details see [EnumHelper](src/EnumHelper.php) code

### Associative
Can help you define associative array with your enum as array keys. If you forgot something, exception 
\XAKEPEHOK\EnumHelper\Exception\NotEqualsAssociationException` will be thrown

```php
<?php
$array = Status::associative([
    Status::STATUS_PROCESSING => 0,
    Status::STATUS_APPROVED => 1,
    Status::STATUS_CANCELED => -1,
]);
```

### Is valid

```php
<?php
Status::isVlaid(Status::STATUS_PROCESSING); //true
Status::isVlaid('qwerty'); //false
```

### Guard valid value
Throw `\XAKEPEHOK\EnumHelper\Exception\OutOfEnumException` exception if not enum value passed 
```php
<?php
Status::guardValidValue(Status::STATUS_PROCESSING); //OK
Status::guardValidValue('qwerty'); //throw exception
```