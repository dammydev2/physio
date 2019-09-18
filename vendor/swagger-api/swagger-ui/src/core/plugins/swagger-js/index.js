
/* eslint-env mocha */
import React from "react"
import expect, { createSpy } from "expect"
import { shallow } from "enzyme"
import { fromJS } from "immutable"
import Schemes from "components/schemes"

describe("<Schemes/>", function(){
  it("calls props.specActions.setScheme() when no currentScheme is selected", function(){

    let setSchemeSpy = createSpy()

    // Given
    let props = {
      specActions: {
        setScheme: setSchemeSpy
      },
      schemes: fromJS([
        "http",
        "https"
      ]),
      currentScheme: undefined,
      path: "/test",
      method: "get"
    }

    // When
    let wrapper = shallow(<Schemes {...props}/>)

    // Then currentScheme should default to first scheme in options list
    expect(props.specActions.setScheme).toHaveBeenCalledWith("http", "/test" , "get")

    // When the currentScheme is no longer in the list of options
    props.schemes = fromJS([
      "https"
    ])
    wrapper.setProps(props)

    // Then currentScheme should default to first schem