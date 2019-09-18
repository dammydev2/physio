/* eslint-env mocha */
import React from "react"
import expect, { createSpy } from "expect"
import { shallow } from "enzyme"
import { fromJS, Map } from "immutable"
import VersionPragmaFilter from "components/version-pragma-filter"

describe("<VersionPragmaFilter/>", function(){
  it("renders children for a Swagger 2 definition", function(){
    // When
    let wrapper = shallow(
      <VersionPragmaFilter isSwagger2={true} isOAS3={false}>
        hello!
      </VersionPragmaFilter>
    )

    // Then
    expect(wrapper.find("div").length).toEqual(1)
    expect(wrapper.find("div").text()).toEqual("hello!")
  })
  it("renders children for an OpenAPI 3 definition", function(){
    // When
    let wrapper = shallow(
      <VersionPragmaFilter isSwagger2={false} isOAS3={true}>
        hell