That(array('foo', 'bar'), typeOf('array'));
    assertThat(new stdClass(), typeOf('object'));

* Added type-specific matchers in new Hamcrest_Type package.

  - IsArray
  - IsBoolean
  - IsDouble (includes float values)
  - IsInteger
  - IsObject
  - IsResource
  - IsString

    assertThat($count, integerValue());
    assertThat(3.14159, floatValue());
    assertThat('foo', stringValue());

* Added Hamcrest_Type_IsNumeric to assert is_numeric().
  Matches values of type int and float/double or strings that are formatted as numbers.

    assertThat(5, numericValue());
    assertThat('-5e+3', numericValue());

* Added Hamcrest_Type_IsCallable to assert is_callable().

    assertThat('preg_match', callable());
    assertThat(array('SomeClass', 'SomeMethod'), callable()