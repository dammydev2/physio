/* eslint-env mocha */
import React, { PureComponent } from "react"
import expect from "expect"
import System from "core/system"
import { fromJS } from "immutable"
import { render } from "enzyme"
import ViewPlugin from "core/plugins/view/index.js"
import filterPlugin from "core/plugins/filter/index.js"
import { connect, Provider } from "react-redux"

describe("bound system", function(){

  describe("wrapActions", function(){

    it("should replace an action", function(){
      // Given
      const system = new System({
        plugins: {
          statePlugins: {
            josh: {
              actions: {
                simple: () => {
                  return { type: "simple" }
                }
              },
              wrapActions: {
                simple: () => () => {
                  return { type: "newSimple" }
                }
              }
            }
          }
        }
      })

      // When
      var action = system.getSystem().joshActions.simple(1)
      expect(action).toEqual({
        type: "newSimple"
      })

    })

    it("should expose the original action, and the system as args", function(){
      // Given
      const simple = () => ({type: "simple" })
      const system = new System({
        plugins: {
          statePlugins: {
            josh: {
              actions: { simple },
              wrapActions: {
                simple: (oriAction, system) => (actionArg) => {
                  return {
                    type: "newSimple",
                    oriActionResult: oriAction(),
                    system: system.getSystem(),
                    actionArg
 