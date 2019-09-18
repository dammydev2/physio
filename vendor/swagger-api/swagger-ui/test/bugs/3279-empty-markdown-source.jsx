/* eslint-env mocha */
import expect from "expect"
import { fromJS } from "immutable"
import reducer from "corePlugins/spec/reducers"

describe("spec plugin - reducer", function(){

  describe("update operation meta value", function() {
    it("should update the operation metadata at the specified key", () => {
      const updateOperationValue = reducer["spec_update_operation_meta_value"]

      const state = fromJS({
        resolved: {
          "paths": {
            "/pet": {
              "post": {
                "description": "my operation"
              }
            }
          }
        }
      })

      let result = updateOperationValue(state, {
        payload: {
          path: ["/pet", "post"],
          value: "application/json",
          key: "consumes_value"
        }
      })

      let expectedResult = {
        resolved: {
      