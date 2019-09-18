
                  "body.myBody": {
                    value: "abc123"
                  }
                }
              }
            }
          }
        }
      })

      const result = parameterWithMetaByIdentity(state, ["/", "get"], bodyParam)

      expect(result.toJS()).toEqual({
        name: "myBody",
        in: "body",
        value: "abc123"
      })
    })
    it("should support merging in hash-keyed param metadata", function () {
      const bodyParam = fromJS({
        name: "myBody",
        in: "body"
      })

      const state = fromJS({
        json: {
          paths: {
            "/": {
              "get": {
                parameters: [
                  bodyParam
                ]
              }
            }
          }
        },
        meta: {
          paths: {
            "/": {
              "get": {
                parameters: {
                  [`body.myBody.hash-${bodyParam.hashCode()}`]: {
                    value: "abc123"
                  }
                }
              }
            }
          }
        }
      })

      const result = parameterWithMetaByIdentity(state, ["/", "get"], bodyParam)

      expect(result.toJS()).toEqual({
        name: "myBody",
        in: "body",
        value: "abc123"
      })
    })
  })
  describe("parameterInclusionSettingFor", function() {
    it("should support getting {in}.{name} param inclusion settings", function () {
      const param = fromJS({
        name: "param",
        in: "query",
        allowEmptyValue: true
      })

      const state = fromJS({
        json: {
          paths: {
            "/": {
              "get": {
                parameters: [
                  param
                ]
              }
            }
          }
        },
        meta: {
          paths: {
            "/": {
              "get": {
                "parameter_inclusions": {
                  [`query.param`]: true
                }
              }
            }
          }
        }
      })

      const result = parameterInclusionSettingFor(state, ["/", "get"], "param", "query")

      expect(result).toEqual(true)
    })
  })
  describe("producesOptionsFor", function() {
    it("should return an operation produces value", function () {
      const state = fromJS({
        json: {
          paths: {
            "/": {
              "get": {
                description: "my operation",
                produces: [
                  "operation/one",
                  "operation/two",
                ]
              }
            }
          }
        }
      })

      const result 