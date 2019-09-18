<!--
    🛑 DON'T REMOVE ME.
    This issue template apply to all
      - bug reports,
      - feature proposals,
      - and documentation requests

    Having all those informations will allow us to know exactly
    what you expect and answer you faster and precisely (answer
    that matches your Carbon version, PHP version and usage).
    
    Note: Comments between <!- - and - -> won't appear in the final
    issue (See [Preview] tab).
-->
Hello,

I encountered an issue with the following code:
```php
echo Carbon::parse('2018-06-01')->subDay()->month;
```

Carbon version: **PUT HERE YOUR COMPOSER CARBON VERSION**

PHP version: **PUT HERE YOUR PHP VERSION**

<!--
    Run the command `composer show nesbot/carbon`
    to get "versions"
    Use `echo phpversion();`
    to get PHP version.

    Some issues can depends on your context, settings,
    macros, timezone, language. So to be sure the code
    you give is enough to reproduce your bug, try it
    first in:
    https://try-carbon.herokuapp.com/?theme=xcode&export&embed

    You can use the [Options] button to change the version
    then when you get the bug with this editor, you can use
    the [Export] button, copy the link of the opened tab,
    then paste it in the issue. Then we can immediatly get
   