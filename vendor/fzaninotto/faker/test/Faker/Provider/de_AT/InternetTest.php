YearProvider
     */
    public function test_validYear_returnsValidCnp($value)
    {
        $cnp = $this->faker->cnp(null, $value);
        $this->assertTrue(
            $this->isValidCnp($cnp),
            sprintf("Invalid CNP '%' generated for valid year '%s'", $cnp, $value)
        );
    }

    /**
     * @param string $value year of birth
     *
     * @dataProvider invalidYearProvider
     */
    public function test_invalidYear_throwsException($value)
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->faker->cnp(null, $value);
    }

    /**
     * @param $value
     * @dataProvider validCountyCodeProvider
     */
    public function test_vali