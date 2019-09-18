/* eslint-env mocha */
import React from "react"
import { List, fromJS } from "immutable"
import expect, { createSpy } from "expect"
import { render } from "enzyme"
import ParameterRow from "components/parameter-row"

describe("bug #4557: default parameter values", function(){
  it("should apply a Swagger 2.0 default value", function(){

    const paramValue = fromJS({
      description: "a pet",
      type: "string",
      default: "MyDefaultValue"
    })

    let props = {
      getComponent: ()=> "div",
      specSelectors: {
        security(){},
        parameterWithMetaByIdentity(){ return paramValue },
        isOAS3(){ return false },
        isSwagger2(){ return true }
      },
      fn: {},
      operatio