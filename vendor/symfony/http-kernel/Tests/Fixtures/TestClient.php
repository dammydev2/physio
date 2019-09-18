0, "\xff" => 0,
    ];

    private $data = '';
    private $dataSize = 0;
    private $map = [];
    private $charCount = 0;
    private $currentPos = 0;
    private $fixedWidth = 0;

    /**
     * @param resource|string $input
     */
    public function __construct($input, ?string $charset = 'utf-8')
    {
        $charset = strtolower(trim($charset)) ?: 'utf-8';
        if ('utf-8' === $charset || 'utf8' === $charset) {
            $this->fixedWidth = 0;
            $this->map = ['p' => [], 'i' => []];
        } else {
            switch ($charset) {
                // 16 bits
                case 'ucs2':
                case 'ucs-2':
                case 'utf16':
                case 'utf-16':
                    $this->fixedWidth = 2;
                    break;

               