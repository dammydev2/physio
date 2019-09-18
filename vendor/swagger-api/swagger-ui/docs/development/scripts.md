# `swagger-ui-react`

[![NPM version](https://badge.fury.io/js/swagger-ui-react.svg)](http://badge.fury.io/js/swagger-ui-react)

`swagger-ui-react` is a flavor of Swagger UI suitable for use in React applications.

It has a few differences from the main version of Swagger UI:
* Declares `react` and `react-dom` as peerDependencies instead of production dependencies
* Exports a component instead of a constructor function

Versions of this module mirror the version of Swagger UI included in the distribution.

## Quick start

Install `swagger-ui-react`:

```
$ npm i --save swagger-ui-react
```

Use it in your React application:

```js
import SwaggerUI from "swagger-ui-react"
import "swagger-ui-react/swagger-ui.css"

export default App = () => <SwaggerUI url="https://petstore.swagger.io/v2/swagger.json" />
```

## Props

These props map to [Swagger UI configuration options](https://github.com/swagger-api/swagger-ui/blob/master/docs/usage/configuration.md) of the same name.

#### `spec`: PropTypes.object

An OpenAPI document respresented as a JavaScript object, JSON string, or YAML string for Swagger UI to display.

⚠️ Don't use this in conjunction with `url` - unpredictable behavior may occur.

#### `url`: PropTypes