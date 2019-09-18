/* eslint-env mocha */
import expect from "expect"
import { fromJS } from "immutable"
import { fromJSOrdered } from "core/utils"
import {
  definitions,
  parameterValues,
  contentTypeValues,
  operationScheme,
  specJsonWithResolvedSubtrees,
  producesOptionsFor,
  operationWithMeta,
  parameterWithMeta,
  parameterWithMetaByIdentity,
  parameterInclusionSettingFor,
  consumesOptionsFor,
  taggedOperations
} from "corePlugins/spec/selectors"

import Petstore from "./assets/petstore.json"

  describe("definitions", function(){
    it("should return definitions by default", function(){

      // Given
      const spec = fromJS({
        json: {
          swagger: "2.0",
          definitions: {
            a: {
              type: "string"
            },
            b: {
              type: "string"
            }
          }
        }
      })

      // When
      let res = definitions(spec)

      // Then
      expect(res.toJS()).toEqual({
        a: {
          type: "string"
        },
        b: {
          type: "string"
        }
      })
    })
    it("should return an empty Map when missing definitions", function(){

      // Given
      const spec = fromJS({
        json: {
          swagger: "2.0"
        }
      })

      // When
      let res = definitions(spec)

      // Then
      expect(res.toJS()).toEqual({})
    })
    it("should return an empty Map when given non-object definitions", function(){

      // Given
      const spec = fromJS({
        json: {
          swagger: "2.0",
          definitions: "..."
        }
      })

      // When
      let res = definitions(spec)

      // Then
      expect(res.toJS()).toEqual({})
    })
  })

  describe("parameterValue", function(){

    it("s