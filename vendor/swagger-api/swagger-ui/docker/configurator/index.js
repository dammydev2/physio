# Configuration

### How to configure

Swagger-UI accepts configuration parameters in four locations.

From lowest to highest precedence:
- The `swagger-config.yaml` in the project root directory, if it exists, is baked into the application
- configuration object passed as an argument to Swagger-UI (`SwaggerUI({ ... })`)
- configuration document fetched from a specified `configUrl`
- configuration items passed as key/value pairs in the URL query string

### Parameters

Parameters with dots in their names are single strings used to organize subordinate parameters, and are not indicative of a nested structure.

For readability, parameters are grouped by category and sorted alphabetically.

Type notations are formatted like so:
- `String=""` means a String type with a default value of `""`.
- `String=["a"*, "b", "c", "d"]` means a String type that can be `a`, `b`, `c`, or `d`, with the `*` indicating that `a` is the default value.

##### Core

Parameter name | Docker variable | Description
--- | --- | -----
<a name="configUrl"></a>`configUrl` | `CONFIG_URL` |  `String`. URL to fetch external configuration document from.
<a name="dom_id"></a>`dom_id` | `DOM_ID` |`String`, **REQUIRED** if `domNode` is not provided. The id of a dom element inside which SwaggerUi will put the user interface for swagger.
<a name="domNode"></a>`domNode` | _Unavailable_ | `Element`, **REQUIRED** if `dom_id` is not provided. The HTML DOM element inside which SwaggerUi will put the user interface for swagger. Overrides `dom_id`.
<a name="spec"></a>`spec` | `SPEC` | `Object={}`. A JS object describing the OpenAPI Specification. When used, the `url` parameter will not be parsed. This is useful for testing manually-generated specifications without hosting them.
<a name="url"></a>`url` | `URL` | `String`. The url pointing to API definition (normally `swagger.json` or `swagger.yaml`). Will be ignored if `urls` or `spec` is used.
<a name="urls"></a>`urls` | `UR