<?php

namespace Faker\Provider\pl_PL;

/**
 * Most popular first and last names published by Ministry of the Interior:
 * @link https://msw.gov.pl/pl/sprawy-obywatelskie/ewidencja-ludnosci-dowo/statystyki-imion-i-nazw
 */
class Person extends \Faker\Provider\Person
{
    protected static $lastNameFormat = array(
        '{{lastNameMale}}',
        '{{lastNameFemale}}',
    );

    protected static $maleNameFormats = array(
        '{{firstNameMale}} {{lastNameMale}}',
        '{{firstNameMale}} {{lastNameMale}}',
        '{{firstNameMale}} {{lastNameMale}}',
        '{{title}} {{firstNameMale}} {{lastNameMale}}',
        '{{firstNameMale}} {{lastNameMale}}',
        '{{title}} {{title}} {{firstNameMale}} {{lastNameMale}}',
    );

    protected static $femaleNameFormats = array(
        '{{firstNameFemale}} {{lastNameFemale}}',
        '{{firstNameFemale}} {{lastNa