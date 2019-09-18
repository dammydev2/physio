oSessionHandler changes
   - implemented different session locking strategies to prevent loss of data by concurrent access to the same session
   - [BC BREAK] save session data in a binary column without base64_encode
   - [BC BREAK] added lifetime column to the session table which allows to have different lifetimes for each session
   - implemented lazy connections that are only opened when a session is used by either passing a dsn string
     explicitly or falling back to session.save_path ini setting
   - added a createTable method that initializes a correctly defined table depending on the database vendor

2.5.0
-----

 * added `JsonResponse::setEncodingOptions()` & `JsonResponse::getEncodingOptions()` for easier manipulation
   of the options used while encoding data to JSON format.

2.4.0
-----

 * added RequestStack
 * added Request::getEncodings()
 * added accessors methods to session handlers

2.3.0
-----

 * added support for ranges of IPs in trusted proxies
 * `UploadedFile::isValid` now returns false if the file was not uploaded via HTTP (in a non-test mode)
 * Improved error-handling of `\Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler`
   to ensure the supplied PDO handler throws Exceptions on error (as the class expects). Added related test cases
   to verify that Exceptions are properly thrown when the PDO queries fail.

2.2.0
-----

 * fixed the Request::create() precedence (URI information always take precedence now)
 * added Request::getTrustedProxies()
 * deprecated Request::isProxyTrusted()
 * [BC BREAK] JsonResponse does not turn a top level empty array to an object anymore, use an ArrayObject to enforce objects
 * added a IpUtils class to check if an IP belongs to a CIDR
 * added Request::getRealMethod() to get the "real" HTTP method (getMethod() returns the "intended" HTTP method)
 * disabled _method request parameter