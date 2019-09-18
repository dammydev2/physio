cess()
    {
        $esi = new Esi();

        $request = Request::create('/');
        $response = new Response('foo <esi:comment text="some comment" /><esi:include src="..." alt="alt" onerror="continue" />');
        $esi->process($request, $response);

        $this->assertEquals('foo <?php echo $this->surrogate->handle($this, \'...\', \'alt\', true) ?>'."\n", $response->getContent());
        $this->assertEquals('ESI', $response->headers->get('x-body-eval'));

        $response = new Response('foo <esi:comment text="some comment" /><esi:include src="foo\'" alt="bar\'" onerror="continue" />');
        $esi->process($request, $response);

        $this->assertEquals('foo <?php echo $this->surrogate->handle($this, \'foo\\\'\', \'bar\\\'\', true) ?>'."\n", $response->getContent());

        $response = new Response('foo <esi:include src="..." />');
        $esi->process($request, $response);

        $this->assertEquals('foo <?php echo $this->surrogate->handle($this, \'...\', \'\', false) ?>'."\n", $response->getContent());

        $response = new Response('foo <esi:include src="..."></esi:include>');
        $esi->process($request, $response);

        $this->assertEquals('foo <?php echo $this->surrogate->handle($this, \'...\', \'\', false) ?>'."\n", $response->getContent());
    }

    public function testProcessEscapesPhpTags()
    {
        $esi = new Esi();

        $request = Request::create('/');
        $response = new Response('<?php <? <% <script language=php>');
        $esi->process($request, $response);

        $this->assertEquals('<?php echo "<?"; ?>php <?php echo "<?"; ?> <?php echo "<%"; ?> <?php echo "<s"; ?>cript language=php>', $response->getContent());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testProcessWhenNoSrcInAnEsi()
    {
        $esi = new Esi();

        $request = Request::create('/');
        $response = new Response('foo <esi:include />');
        $esi->process($request, $response);
    }

    public function testProcessRemoveSurrogateControlHeader()
    {
        $esi = new Esi();

        $request = Request::create('/');
    