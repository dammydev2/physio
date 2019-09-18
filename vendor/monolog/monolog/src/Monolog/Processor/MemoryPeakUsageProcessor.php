ace', 'type'),
                'rows' => array(
                    'test',
                    'test',
                    'test',
                    'test',
                ),
                'request_uri' => '',
            )))),
        );

        $this->assertEquals($expected, $handler2->getHeaders());
    }
}

class TestChromePHPHandler extends ChromePHPHandler
{
    protected $headers = array();

    public static function resetStatic()
    {
        self::$initialized = false;
        self::$overflowed = false;
        self::$sendHeaders = true;
        self::$json['rows'] = array();
    }

    protected function sendHeader($header, $content)
    {
        $this->headers[$header] = $content;
    }

    public function getHeaders()
    {
        return $this->headers;
  