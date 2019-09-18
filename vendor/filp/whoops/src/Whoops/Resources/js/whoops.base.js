mber
echo $faker->pesel; // "40061451555"
// Generates a random personal identity card number
echo $faker->personalIdentityNumber; // "AKX383360"
// Generates a random taxpayer identification number (NIP)
echo $faker->taxpayerIdentificationNumber; // '8211575109'
```

### `Faker\Provider\pl_PL\Company`

```php
<?php

// Generates a random REGON number
echo $faker->regon; // "714676680"
// Generates a random local REGON number
echo $faker->regonLocal; // "15346111382836"
```

### `Faker\Provider\pl_PL\Payment`

```php
<?php

// Generates a random bank name
echo $faker->bank; // "Narodowy Bank Polski"
// Generates a random bank account number
echo $faker->bankAccountNumber; // "PL14968907563953822118075816"
```

### `Faker\Provider\pt_PT\Person`

```php
<?php

// Generates a random taxpayer identification number (in portuguese - Número de Identificação Fiscal NIF)
echo $faker->taxpayerIdentificationNumber; // '165249277'
```

### `Faker\Provider\pt_BR\Address`

```php
<?php

// Generates a random region name
echo $faker->region; // 'Nordeste'

// Generates a random region abbreviation
echo $faker->regionAbbr; // 'NE'
```

### `Faker\Provider\pt_BR\PhoneNumber`

```php
<?php

echo $faker->areaCode;  // 21
echo $faker->cellphone; // 9432-5656
echo $faker->landline;  // 2654-3445
echo $faker->phone;     // random landline, 8-digit or 9-digit cellphone number

// Using the phone functions with a false argument returns unformatted numbers
echo $faker->cellphone(false); // 74336667

// cellphone() has a special second argument to add the 9th digit. Ignored if generated a Radio number
echo $faker->cellphone(true, true); // 98983-3945 or 7343-1290

// Using the "Number" suffix adds area code to the phone
echo $faker->cellphoneNumber;       // (11) 98309-2935
echo $faker->landlineNumber(false); // 3522835934
echo $faker->phoneNumber;           // formatted, random landline or cellphone (obeying the 9th digit rule)
echo $faker->phoneNumberCleared;    // not formatted, random landline or cellphone (obeying the 9th digit rule)
```

### `Faker\Provider\pt_BR\Person`

```php
<?php

// The name generator may include double first or double last names, plus title and suffix
echo $faker->name; // 'Sr. Luis Adriano Sepúlveda Filho'

// Valid document generators have a boolean argument to remove formatting
echo $faker->cpf;        // '145.343.345-76'
echo $faker->cpf(false); // '45623467866'
echo $faker->rg;         // '84.405.736-3'
echo $faker->rg(false);  // '844057363'
```

### `Faker\Provider\pt_BR\Company`

```php
<?php

// Generates a Brazilian formatted and valid CNPJ
echo $faker->cnpj;        // '23.663.478/0001-24'
echo $faker->cnpj(false); // '23663478000124'
```

### `Faker\Provider\ro_MD\Payment`

```php
<?php

// Generates a random bank account number
echo $faker->bankAccountNumber; // "MD83BQW1CKMUW34HBESDP3A8"
```

### `Faker\Provider\ro_RO\Payment`

```php
<?php

// Generates a random bank account number
echo $faker->bankAccountNumber; // "RO55WRJE3OE8X3YQI7J26U1E"
```

### `Faker\Provider\ro_RO\Person`

```php
<?php

// Generates a random male name prefix/title
echo $faker->prefixMale; // "ing."
// Generates a random female name prefix/title
echo $faker->prefixFemale; // "d-na."
// Generates a random male first name
echo $faker->firstNameMale; // "Adrian"
// Generates a random female first name
echo $faker->firstNameFemale; // "Miruna"


// Generates a random Personal Numerical Code (CNP)
echo $faker->cnp; // "2800523081231"
// Valid option values:
//    $gender: null (random), male, female
//    $dateOfBirth (1800+): null (random), Y-m-d, Y-m (random day), Y (random month and day)
//          i.e. '1981-06-16', '2015-03', '1900'
//    $county: 2 letter ISO 3166-2:RO county codes and B1, B2, B3, B4, B5, B6 for Bucharest's 6 sectors
//    $isResident true/false flag if the person resides in Romania
echo $faker->cnp($gender = null, $dateOfBirth = null, $county = null, $isResident = true);

```

### `Faker\Provider\ro_RO\PhoneNumber`

```php
<?php

// Generates a random toll-free phone number
echo $faker->tollFreePhoneNumber; // "0800123456"
// Generates a random premium-rate phone number
echo $faker->premiumRatePhoneNumber; // "0900123456"
```

### `Faker\Provider\ru_RU\Payment`

```php
<?php

// Generates a Russian bank name (based on list of real russian banks)
echo $faker->bank; // "ОТП Банк"

//Generate a Russian Tax Payment Number for Company
echo $faker->inn; //  7813540735

//Generate a Russian Tax Code for Company
echo $faker->kpp; // 781301001
```

### `Faker\Provider\sv_SE\Payment`

```php
<?php

// Generates a random bank account number
echo $faker->bankAccountNumber; // "SE5018548608468284909192"
```

### `Faker\Provider\sv_SE\Person`

```php
<?php

//Generates a valid Swedish personal identity number (in Swedish - Personnummer)
echo $faker->personalIdentityNumber() // '950910-0799'

//Since the numbers are different for male and female persons, optionally you can specify gender.
echo $faker->personalIdentityNumber('female') // '950910-0781'
```
### `Faker\Provider\tr_TR\Person`

```php
<?php

//Generates a valid Turkish identity number (in Turkish - T.C. Kimlik No)
echo $faker->tcNo // '55300634882'

```


### `Faker\Provider\zh_CN\Payment`

```php
<?php

// Generates a random bank name (based on list of real chinese banks)
echo $faker->bank; // '中国建设银行'
```

### `Faker\Provider\uk_UA\Payment`

```php
<?php

// Generates an Ukraine bank name (based on list of real Ukraine banks)
echo $faker->bank; // "Ощадбанк"
```

### `Faker\Provider\zh_TW\Person`

```php
<?php

// Generates a random personal identify number
echo $faker->personalIdentityNumber; // A223456789
```

### `Faker\Provider\zh_TW\Company`

```php
<?php

// Generates a random VAT / Company Tax number
echo $faker->VAT; //23456789
```


## Third-Party Libraries Extending/Based On Faker

* Symfony2 bundles:
  * [BazingaFakerBundle](https://github.com/willdurand/BazingaFakerBundle): Put the awesome Faker library into the Symfony2 DIC and populate your database with fake data.
  * [AliceBundle](https://github.com/hautelook/AliceBundle), [AliceFixturesBundle](https://github.com/h4cc/AliceFixturesBundle): Bundles for using [Alice](https://packagist.org/packages/nelmio/alice) and Faker with data fixtures. Able to use Doctrine ORM as well as Doctrine MongoDB ODM.
* [FakerServiceProvider](https://github.com/EmanueleMinotto/FakerServiceProvider): Faker Service Provider for Silex
* [faker-cli](https://github.com/bit3/faker-cli): Comman