>andReturn('AUTH');
        $ext1->shouldReceive('afterEhlo')
             ->once()
             ->with($smtp);
        $ext2->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('SIZE');
        $ext2->shouldReceive('afterEhlo')
             ->zeroOrMoreTimes()
             ->with($smtp);
        $ext3->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('STARTTLS');
        $ext3->shouldReceive('afterEhlo')
             ->never()
             ->with($smtp);
        $this->finishBuffer($buf);

        $smtp->setExtensionHandlers([$ext1, $ext2, $ext3]);
        $smtp->start();
    }

    public function testExtensionsCanModifyMailFromParams()
    {
        $buf = $this->getBuffer();
        $dispatcher = $this->createEventDispatcher();
        $smtp = new Swift_Transport_EsmtpTransport($buf, [], $dispatcher, 'example.org');
        $ext1 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();
        $ext2 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();
        $ext3 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();
        $message = $this->createMessage();

        $message->shouldReceive('getFrom')
                ->zeroOrMoreTimes()
                ->andReturn(['me@domain' => 'Me']);
        $message->shouldReceive('getTo')
                ->zeroOrMoreTimes()
                ->andReturn(['foo@bar' => null]);

        $buf->shouldReceive('readLine')
            ->once()
            ->with(0)
            ->andReturn("220 server.com foo\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with('~^EHLO .*?\r\n$~D')
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250-ServerName.tld\r\n");
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250-AUTH PLAIN LOGIN\r\n");
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250 SIZE=123456\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with("MAIL FROM:<me@domain> FOO ZIP\r\n")
            ->andReturn(2);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(2)
            ->andReturn("250 OK\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with("RCPT TO:<foo@bar>\r\n")
            ->andReturn(3);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(3)
            ->andReturn("250 OK\r\n");
        $this->finishBuffer($buf);

        $ext1->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('AUTH');
        $ext1->shouldReceive('getMailParams')
             ->once()
             ->andReturn('FOO');
        $ext1->shouldReceive('getPriorityOver')
             ->zeroOrMoreTimes()
             ->with('STARTTLS')
             ->andReturn(1);
        $ext1->shouldReceive('getPriorityOver')
             ->zeroOrMoreTimes()
             ->with('SIZE')
             ->andReturn(-1);
        $ext2->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('SIZE');
        $ext2->shouldReceive('getMailParams')
             ->once()
             ->andReturn('ZIP');
        $ext2->shouldReceive('getPriorityOver')
             ->zeroOrMoreTimes()
             ->with('AUTH')
             ->andReturn(1);
        $ext2->shouldReceive('getPriorityOver')
             ->zeroOrMoreTimes()
             ->with('STARTTLS')
             ->andReturn(1);
        $ext3->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('STARTTLS');
        $ext3->shouldReceive('getMailParams')
             ->never();
        $ext3->shouldReceive('getPriorityOver')
             ->zeroOrMoreTimes()
             ->with('AUTH')
             ->andReturn(-1);
        $ext3->shouldReceive('getPriorityOver')
             ->zeroOrMoreTimes()
             ->with('SIZE')
             ->andReturn(-1);

        $smtp->setExtensionHandlers([$ext1, $ext2, $ext3]);
        $smtp->start();
        $smtp->send($message);
    }

    public function testExtensionsCanModifyRcptParams()
    {
        $buf = $this->getBuffer();
        $dispatcher = $this->createEventDispatcher();
        $smtp = new Swift_Transport_EsmtpTransport($buf, [], $dispatcher, 'example.org');
        $ext1 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();
        $ext2 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();
        $ext3 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();
        $message = $this->createMessage();

        $message->shouldReceive('getFrom')
                ->zeroOrMoreTimes()
                ->andReturn(['me@domain' => 'Me']);
        $message->shouldReceive('getTo')
                ->zeroOrMoreTimes()
                ->andReturn(['foo@bar' => null]);

        $buf->shouldReceive('readLine')
            ->once()
            ->with(0)
            ->andReturn("220 server.com foo\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with('~^EHLO .+?\r\n$~D')
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250-ServerName.tld\r\n");
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250-AUTH PLAIN LOGIN\r\n");
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250 SIZE=123456\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with("MAIL FROM:<me@domain>\r\n")
            ->andReturn(2);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(2)
            ->andReturn("250 OK\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with("RCPT TO:<foo@bar> FOO ZIP\r\n")
            ->andReturn(3);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(3)
            ->andReturn("250 OK\r\n");
        $this->finishBuffer($buf);

        $ext1->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('AUTH');
        $ext1->shouldReceive('getRcptParams')
             ->once()
             ->andReturn('FOO');
        $ext1->shouldReceive('getPriorityOver')
             ->zeroOrMoreTimes()
             ->with('STARTTLS')
             ->andReturn(1);
        $ext1->shouldReceive('getPriorityOver')
             ->zeroOrMoreTimes()
             ->with('SIZE')
             ->andReturn(-1);
        $ext2->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('SIZE');
        $ext2->shouldReceive('getRcptParams')
             ->once()
             ->andReturn('ZIP');
        $ext2->shouldReceive('getPriorityOver')
             ->zeroOrMoreTimes()
             ->with('STARTTLS')
             ->andReturn(1);
        $ext2->shouldReceive('getPriorityOver')
             ->zeroOrMoreTimes()
             ->with('AUTH')
             ->andReturn(1);
        $ext3->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('STARTTLS');
        $ext3->shouldReceive('getRcptParams')
             ->never();
        $ext3->shouldReceive('getPriorityOver')
             ->zeroOrMoreTimes()
             ->with('AUTH')
             ->andReturn(-1);
        $ext3->shouldReceive('getPriorityOver')
             ->zeroOrMoreTimes()
             ->with('SIZE')
             ->andReturn(-1);

        $smtp->setExtensionHandlers([$ext1, $ext2, $ext3]);
        $smtp->start();
        $smtp->send($message);
    }

    public function testExtensionsAreNotifiedOnCommand()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $ext1 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();
        $ext2 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();
        $ext3 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();

        $buf->shouldReceive('readLine')
            ->once()
            ->with(0)
            ->andReturn("220 server.com foo\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with('~^EHLO .+?\r\n$~D')
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250-ServerName.tld\r\n");
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250-AUTH PLAIN LOGIN\r\n");
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250 SIZE=123456\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with("FOO\r\n")
            ->andReturn(2);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(2)
            ->andReturn("250 Cool\r\n");
        $this->finishBuffer($buf);

        $ext1->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('AUTH');
        $ext1->shouldReceive('onCommand')
             ->once()
             ->with($smtp, "FOO\r\n", [250, 251], \Mockery::any(), \Mockery::any());
        $ext2->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('SIZE');
        $ext2->shouldReceive('onCommand')
             ->once()
             ->with($smtp, "FOO\r\n", [250, 251], \Mockery::any(), \Mockery::any());
        $ext3->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('STARTTLS');
        $ext3->shouldReceive('onCommand')
             ->never()
             ->with(\Mockery::any(), \Mockery::any(), \Mockery::any(), \Mockery::any(), \Mockery::any());

        $smtp->setExtensionHandlers([$ext1, $ext2, $ext3]);
        $smtp->start();
        $smtp->executeCommand("FOO\r\n", [250, 251]);
    }

    public function testChainOfCommandAlgorithmWhenNotifyingExtensions()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $ext1 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();
        $ext2 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();
        $ext3 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();

        $buf->shouldReceive('readLine')
            ->once()
            ->with(0)
            ->andReturn("220 server.com foo\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with('~^EHLO .+?\r\n$~D')
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250-ServerName.tld\r\n");
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250-AUTH PLAIN LOGIN\r\n");
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250 SIZE=123456\r\n");
        $buf->shouldReceive('write')
            ->never()
            ->with("FOO\r\n");
        $this->finishBuffer($buf);

        $ext1->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('AUTH');
        $ext1->shouldReceive('onCommand')
             ->once()
             ->with($smtp, "FOO\r\n", [250, 251], \Mockery::any(), \Mockery::any())
             ->andReturnUsing(function ($a, $b, $c, $d, &$e) {
                 $e = true;

                 return '250 ok';
             });
        $ext2->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('SIZE');
        $ext2->shouldReceive('onCommand')
             ->never()
             ->with(\Mockery::any(), \Mockery::any(), \Mockery::any(), \Mockery::any());

        $ext3->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('STARTTLS');
        $ext3->shouldReceive('onCommand')
             ->never()
             ->with(\Mockery::any(), \Mockery::any(), \Mockery::any(), \Mockery::any());

        $smtp->setExtensionHandlers([$ext1, $ext2, $ext3]);
        $smtp->start();
        $smtp->executeCommand("FOO\r\n", [250, 251]);
    }

    public function testExtensionsCanExposeMixinMethods()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $ext1 = $this->getMockery('Swift_Transport_EsmtpHandlerMixin')->shouldIgnoreMissing();
        $ext2 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();

        $ext1->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('AUTH');
        $ext1->shouldReceive('exposeMixinMethods')
             ->zeroOrMoreTimes()
             ->andReturn(['setUsername', 'setPassword']);
        $ext1->shouldReceive('setUsername')
             ->once()
             ->with('mick');
        $ext1->shouldReceive('setPassword')
             ->once()
             ->with('pass');
        $ext2->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('STARTTLS');
        $this->finishBuffer($buf);

        $smtp->setExtensionHandlers([$ext1, $ext2]);
        $smtp->setUsername('mick');
        $smtp->setPassword('pass');
    }

    public function testMixinMethodsBeginningWithSetAndNullReturnAreFluid()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $ext1 = $this->getMockery('Swift_Transport_EsmtpHandlerMixin')->shouldIgnoreMissing();
        $ext2 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();

        $ext1->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('AUTH');
        $ext1->shouldReceive('exposeMixinMethods')
             ->zeroOrMoreTimes()
             ->andReturn(['setUsername', 'setPassword']);
        $ext1->shouldReceive('setUsername')
             ->once()
             ->with('mick')
             ->andReturn(null);
        $ext1->shouldReceive('setPassword')
             ->once()
             ->with('pass')
             ->andReturn(null);
        $ext2->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('STARTTLS');
        $this->finishBuffer($buf);

        $smtp->setExtensionHandlers([$ext1, $ext2]);
        $ret = $smtp->setUsername('mick');
        $this->assertEquals($smtp, $ret);
        $ret = $smtp->setPassword('pass');
        $this->assertEquals($smtp, $ret);
    }

    public function testMixinSetterWhichReturnValuesAreNotFluid()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $ext1 = $this->getMockery('Swift_Transport_EsmtpHandlerMixin')->shouldIgnoreMissing();
        $ext2 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();

        $ext1->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('AUTH');
        $ext1->shouldReceive('exposeMixinMethods')
             ->zeroOrMoreTimes()
             ->andReturn(['setUsername', 'setPassword']);
        $ext1->shouldReceive('setUsername')
             ->once()
             ->with('mick')
             ->andReturn('x');
        $ext1->shouldReceive('setPassword')
             ->once()
             ->with('pass')
             ->andReturn('x');
        $ext2->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('STARTTLS');
        $this->finishBuffer($buf);

        $smtp->setExtensionHandlers([$ext1, $ext2]);
        $this->assertEquals('x', $smtp->setUsername('mick'));
        $this->assertEquals('x', $smtp->setPassword('pass'));
    }
}
                                                                                                                                                                                                                                                                                                                                                       ISO-2022-JPは、インターネット上(特に電子メール)などで使われる日本の文字用の文字符号化方式。ISO/IEC 2022のエスケープシーケンスを利用して文字集合を切り替える7ビットのコードであることを特徴とする (アナウンス機能のエスケープシーケンスは省略される)。俗に「JISコード」と呼ばれることもある。

概要
日本語表記への利用が想定されている文字コードであり、日本語の利用されるネットワークにおいて、日本の規格を応用したものである。また文字集合としては、日本語で用いられる漢字、ひらがな、カタカナはもちろん、ラテン文字、ギリシア文字、キリル文字なども含んでおり、学術や産業の分野での利用も考慮たものとなっている。規格名に、ISOの日本語の言語コードであるjaではなく、国・地域名コードのJPが示されているゆえんである。
文字集合としてJIS X 0201のC0集合（制御文字）、JIS X 0201のラテン文字集合、ISO 646の国際基準版図形文字、JIS X 0208の1978年版（JIS C 6226-1978）と1983年および1990年版が利用できる。JIS X 0201の片仮名文字集合は利用できない。1986年以降、日本の電子メールで用いられてきたJUNETコードを、村井純・Mark Crispin・Erik van der Poelが1993年にRFC化したもの(RFC 1468)。後にJIS X 0208:1997の附属書2としてJISに規定された。MIMEにおける文字符号化方式の識別用の名前として IANA に登録されている。
なお、符号化の仕様についてはISO/IEC 2022#ISO-2022-JPも参照。

ISO-2022-JPと非標準的拡張使用
「JISコード」（または「ISO-2022-JP」）というコード名の規定下では、その仕様通りの使用が求められる。しかし、Windows OS上では、実際にはCP932コード (MicrosoftによるShift JISを拡張した亜種。ISO-2022-JP規定外文字が追加されている。）による独自拡張（の文字）を断りなく使うアプリケーションが多い。この例としてInternet ExplorerやOutlook Expressがある。また、EmEditor、秀丸エディタやThunderbirdのようなMicrosoft社以外のWindowsアプリケーションでも同様の場合がある。この場合、ISO-2022-JPの範囲外の文字を使ってしまうと、異なる製品間では未定義不明文字として認識されるか、もしくは文字化けを起こす原因となる。そのため、Windows用の電子メールクライアントであっても独自拡張の文字を使用すると警告を出したり、あえて使えないように制限しているものも存在する。さらにはISO-2022-JPの範囲内であってもCP932は非標準文字（FULLWIDTH TILDE等）を持つので文字化けの原因になり得る。
また、符号化方式名をISO-2022-JPとしているのに、文字集合としてはJIS X 0212 (いわゆる補助漢字) やJIS X 0201の片仮名文字集合 (いわゆる半角カナ) をも符号化している例があるが、ISO-2022-JPではこれらの文字を許容していない。これらの符号化は独自拡張の実装であり、中にはISO/IEC 2022の仕様に準拠すらしていないものもある[2]。従って受信側の電子メールクライアントがこれらの独自拡張に対応していない場合、その文字あるいはその文字を含む行、時にはテキスト全体が文字化けすることがある。

                                                                                                                                                                                                                                                                                                                                                                                                                                                                             Op mat eraus hinnen beschte, rou zënne schaddreg ké. Ké sin Eisen Kaffi prächteg, den haut esou Fielse wa, Well zielen d'Welt am dir. Aus grousse rëschten d'Stroos do, as dat Kléder gewëss d'Kàchen. Schied gehéiert d'Vioule net hu, rou ke zënter Säiten d'Hierz. Ze eise Fletschen mat, gei as gréng d'Lëtzebuerger. Wäit räich no mat.

Säiten d'Liewen aus en. Un gëtt bléit lossen wee, da wéi alle weisen Kolrettchen. Et deser d'Pan d'Kirmes vun, en wuel Benn rëschten méi. En get drem ménger beschte, da wär Stad welle. Nun Dach d'Pied do, mä gét ruffen gehéiert. Ze onser ugedon fir, d'Liewen Plett'len ech no, si Räis wielen bereet wat. Iwer spilt fir jo.

An hin däischter Margréitchen, eng ke Frot brommt, vu den Räis néierens. Da hir Hunn Frot nozegon, rout Fläiß Himmel zum si, net gutt Kaffi Gesträich fu. Vill lait Gaart sou wa, Land Mamm Schuebersonndeg rei do. Gei geet Minutt en, gei d'Leit beschte Kolrettchen et, Mamm fergiess un hun.

Et gutt Heck kommen oft, Lann rëscht rei um, Hunn rëscht schéinste ke der. En lait zielen schnéiwäiss hir, fu rou botze éiweg Minutt, rem fest gudden schaddreg en. Noper bereet Margréitchen mat op, dem denkt d'Leit d'Vioule no, oft ké Himmel Hämmel. En denkt blénken Fréijor net, Gart Schiet d'Natur no wou. No hin Ierd Frot d'Kirmes. Hire aremt un rou, ké den éiweg wielen Milliounen.

Mir si Hunn Blénkeg. Ké get ston derfir d'Kàchen. Haut d'Pan fu ons, dé frou löschteg d'Meereische rei. Sou op wuel Léift. Stret schlon grousse gin hu. Mä denkt d'Leit hinnen net, ké gét haut fort rëscht.

Koum d'Pan hannendrun ass ké, ké den brét Kaffi geplot. Schéi Hären d'Pied fu gét, do d'Mier néierens bei. Rëm päift Hämmel am, wee Engel beschéngt mä. Brommt klinzecht der ke, wa rout jeitzt dén. Get Zalot d'Vioule däischter da, jo fir Bänk päift duerch, bei d'Beem schéinen Plett'len jo. Den haut Faarwen ze, eng en Biereg Kirmesdag, um sin alles Faarwen d'Vioule.

Eng Hunn Schied et, wat wa Frot fest gebotzt. Bei jo bleiwe ruffen Klarinett. Un Feld klinzecht gét, rifft Margréitchen rem ke. Mir dé Noper duurch gewëss, ston sech kille sin en. Gei Stret d'Wise um, Haus Gart wee as. Monn ménger an blo, wat da Gart gefällt Hämmelsbrot.

Brommt geplot och ze, dat wa Räis Well Kaffi. Do get spilt prächteg, as wär kille bleiwe gewalteg. Onser frësch Margréitchen rem ke, blo en huet ugedon. Onser Hemecht wär de, hu eraus d'Sonn dat, eise deser hannendrun da och.

As durch Himmel hun, no fest iw'rem schéinste mir, Hunn séngt Hierz ke zum. Séngt iw'rem d'Natur zum an. Ke wär gutt Grénge. Kënnt gudden prächteg mä rei. Dé dir Blénkeg Klarinett Kolrettchen, da fort muerges d'Kanner wou, main Feld ruffen vu wéi. Da gin esou Zalot gewalteg, gét vill Hemecht blénken dé.

Haut gréng nun et, nei vu Bass gréng d'Gaassen. Fest d'Beem uechter si gin. Oft vu sinn wellen kréien. Et ass lait Zalot schéinen.                                                                                                                                                                                                                           