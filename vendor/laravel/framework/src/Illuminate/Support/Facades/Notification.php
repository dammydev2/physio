    protected $numericRules = ['Numeric', 'Integer'];

    /**
     * Create a new Validator instance.
     *
     * @param  \Illuminate\Contracts\Translation\Translator  $translator
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return void
     */
    public function __construct(Translator $translator, array $data, array $rules,
                                array $messages = [], array $customAttributes = [])
    {
        $this->initialRules = $rules;
        $this->translator = $translator;
        $this->customMessages = $messages;
        $this->data = $this->parseData($data);
        $this->customAttributes = $customAttributes;

        $this->setRules($rules);
    }

    /**
     * Parse the data array, converting dots to ->.
     *
     * @param  array  $data
     * @return array
     */
    public function parseData(array $data)
    {
        $newData = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = $this->parseData($value);
            }

            // If the data key contains a dot, we will replace it with another character
            // sequence so it doesn't interfere with dot processing when working with
            // array based validation rules and array_dot later in the validations.
            if (Str::contains($key, '.')) {
             