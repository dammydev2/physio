tdClass"/>
 *         <file>MyRelativeFile.php</file>
 *         <directory>MyRelativeDir</directory>
 *       </arguments>
 *     </listener>
 *   </listeners>
 *
 *   <logging>
 *     <log type="coverage-html" target="/tmp/report" lowUpperBound="50" highLowerBound="90"/>
 *     <log type="coverage-clover" target="/tmp/clover.xml"/>
 *     <log type="coverage-crap4j" target="/tmp/crap.xml" threshold="30"/>
 *     <log type="plain" target="/tmp/logfile.txt"/>
 *     <log type="teamcity" target="/tmp/logfile.txt"/>
 *     <log type="junit" target="/tmp/logfile.xml"/>
 *     <log type="testdox-html" target="/tmp/testdox.html"/>
 *     <log type="testdox-text" target="/tmp/testdox.txt"/>
 *     <log type="testdox-xml" target="/tmp/testdox.xml"/>
 *   </logging>
 *
 *   <php>
 *     <includePath>.</includePath>
 *     <ini name="foo" value="bar"/>
 *     <const name="foo" value="bar"/>
 *     <var name="foo" value="bar"/>
 *     <env name="foo" value="bar"/>
 *     <post name="foo" value="bar"/>
 *     <get name="foo" value="bar"/>
 *     <cookie name="foo" value="bar"/>
 *     <server name="foo" value="bar"/>
 *     <files name="foo" value="bar"/>
 *     <request name="foo" value="bar"/>
 *   </php>
 * </phpunit>
 * </code>
 */
final class Configuration
{
    /**
     * @var self[]
     */
    private static $instances = [];

    /**
     * @var \DOMDocument
     */
    private $document;

    /**
     * @var DOMXPath
     */
    private $xpath;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var \LibXMLError[]
     */
    private $errors = [];

    /**
     * Returns a