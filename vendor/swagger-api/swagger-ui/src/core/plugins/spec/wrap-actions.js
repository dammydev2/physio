
/* eslint-env mocha */
import React from "react"
import expect from "expect"
import { mount, render } from "enzyme"
import { fromJS } from "immutable"
import SchemesContainer from "containers/schemes"
import Schemes from "components/schemes"
import { Col } from "components/layout-utils"

describe("<SchemesContainer/>", function(){

  const components = {
    schemes: Schemes,
    Col,
    authorizeBtn: () => <span className="mocked-button" id="mocked-button" />
  }
  const mockedProps = {
    specSelectors: {
      securityDefinitions() {},
      operationScheme() {},
      schemes() {}
    },
    specActions: {
      setScheme() {}
    },
    getComponent: c => components[c]
  }
  const twoSecurityDefinitions = {
    "petstore_auth": {
      "type": "oauth2",
      "authorizationUrl": "http://petstore.swagger.io/oauth/dialog",
      "flow": "implicit",
      "scopes": {
        "write:pets": "modify pets in your account",
        "read:pets": "read your pets"
      }
    },
    "api_key": {
      "type": "apiKey"