`            | Check that a string has at least a certain number of characters
`maxLength($value, $max, $message = '')`            | Check that a string has at most a certain number of characters
`lengthBetween($value, $min, $max, $message = '')`  | Check that a string has a length in the given range
`uuid($value, $message = '')`                       | Check that a string is a valid UUID
`ip($value, $message = '')`                         | Check that a string is a valid IP (either IPv4 or IPv6)
`ipv4($value, $message = '')`                       | Check that a string is a valid IPv4
`ipv6($value, $message = '')`                       | Check that a string is a valid IPv6
`notWhitespaceOnly($value, $message = '')`          | Check that a string contains at least one non-whitespace character

### File Assertions

Method                              | Description
----------------------------------- | --------------------------------------------------
`fileExists($value, $message = '')` | Check that a value is an existing path
`file($value, $message = '')`       | Check that a value is an existing file
`directory($value, $message = '')`  | Check that a value is an existing directory
`readable($value, $message = '')`   | Check that a value is a readable path
`writable($value, $message = '')`   | Check that a value is a writable path

### Object Assertions

Method                                                | Description
----------------------------------------------------- | --------------------------------------------------
`classExists($value, $message = '')`                  | Check that a value is an existing class name
`subclassOf($value, $class, $message = '')`           | Check that a class is a subclass of another
`interfaceExists($value, $message = '')`              | Check that a value is an existing interface name
`implementsInterface($value, $class, $message = '')`  | Check that a class implements an interface
`propertyExists($value, $property, $message = '')`    | Check that a property exists in a class/object
`propertyNotExists($value, $property, $message = '')` | Check that a property does not exist in a class/object
`methodExists($value, $method, $message = '')`        | Check that a method exists in a class/object
`methodNotExists($value, $method, $message = '')`     | Check that a method does not exist in a class/object

### Array Assertions

Method                          