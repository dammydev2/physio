h(123)->twice();
    }

    /** @test */
    public function it_throws_if_it_was_called_more_than_the_number_of_times_we_expected()
    {
        $spy = spy(function () {});

        $spy();
        $spy();
        $spy();

        $this->expectException(InvalidCountException::class);
        $spy->shouldHaveBeenCalled()->twice();
    }

    /** @test */
    public function it_throws_if_it_was_called_more_than_the_number_of_times_we_expected_with_particular_arguments()
    {
        $spy = spy(function () {});

        $spy(123);
        $spy(123);
        $spy(123);

        $this->expectException(InvalidCountException::class);
        $spy->shouldHaveBeenCalled()->with(123)->twice();
    }

    /** @test */
    public function it_acts_as_partial()
    {
        $spy = spy(function ($number) { return $number + 1;});
