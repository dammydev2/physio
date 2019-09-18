ad", "patch", "trace"]`. List of HTTP methods that have the Try it out feature enabled. An empty array disables Try it out for all operations. This does not filter the operations from the display.
<a name="validatorUrl"></a>`validatorUrl` | `VALIDATOR_URL` | `String="https://online.swagger.io/validator" OR null`. By default, Swagger-UI attempts to validate specs against swagger.io's online validator. You can use this parameter to set a different validator URL, for example for locally deployed validators ([Validator Badge](https://github.com/swagger-api/validator-badge)). Setting it to `null` will disable validation.
<a name="withCredentials"></a>`withCredentials` | `WITH_CREDENTIALS` | `Boolean=false` If set to `true`, enables passing credentials, [as defined in the Fetch standard](https://fetch.spec.whatwg.org/#credentials), in CORS requests that are sent by the browser. Note that Swagger UI cannot currently set cookies cross-domain (see [swagger-js#1163](https://github.com/swagger-api/swagger-js/issues/1163)) - as a result, you will have to rely on browser-supplied cookies (which this setting enables sending) that Swagger UI cannot control.

##### Macros

Parameter name | Docker variable | Description
--- | --- | -----
<a name="modelPropertyMacro"></a>`modelPropertyMacro` | _Unavailable_ | `Function`. Function to set default values to each property in model. Accepts one argument modelPropertyMacro(property), property is immutable
<a name="parameterMacro"></a>`parameterMacro` | _Unavailable_ | `Function`. Function to set default value to parameters. Accepts two arguments parameterMacro(operation, parameter). Operation and parameter are objects passed for context, both remain immutable

### Instance methods

**💡 Take note! These are methods, not parameters**.

Method name | Docker variable | Description
--- | --- | -----
<a name="initOAuth"></a>`initOAuth` | [_See `oauth2.md`_](./oauth2.md) | `(configObj) => void`. Provide Swagger-UI with information about your OAuth server - see the OAuth2 documentation for more information.
<a name="preauthorizeBasic"></a>`preauthorizeBasic` | _Unavailable_ | `(authDefinitionKey, username, password) => action`. Programmatically set values for a Basic authorization scheme.
<a name="preauthorizeApiKey"></a>`preauthorizeApiKey` | _Unavailable_ | `(authDefinitionKey, apiKeyValue) => action`. Programmatically set values for an API key authorization scheme.

### Docke