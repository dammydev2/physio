two",
              ],
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

      const result = producesOptionsFor(state, ["/", "get"])

      expect(result.toJS()).toEqual([
        "operation/one",
        "operation/two",
      ])
    })
    it("should favor a path-item produces value over a global value", function () {
      const state = fromJS({
        json: {
          produces: [
            "global/one",
            "global/two",
          ],
          paths: {
            "/": {
              produces: [
                "path-item/one",
                "path-item/two",
              ],
              "get": {
                description: "my operation"
              }
            }
          }
        }
      })

      const result = producesOptionsFor(state, ["/", "get"])

      expect(result.toJS()).toEqual([
        "path-item/one",
        "path-item/two",
      ])
    })
  })
  describe("consumesOptionsFor", function() {
    it("should return an operation consumes value", function () {
      const state = fromJS({
        json: {
          paths: {
            "/": {
              "get": {
                description: "my operation",
                consumes: [
                  "operation/one",
                  "operation/two",
                ]
              }
            }
          }
        }
      })

      const result = consumesOptionsFor(state, ["/", "get"])

      expect(result.toJS()).toEqual([
        "operation/one",
        "operation/two",
      ])
    })
    it("should return a path item consumes value", function () {
      const state = fromJS({
        json: {
          paths: {
            "/": {
              "get": {
                description: "my operation",
                consumes: [
                  "path-item/one",
                  "path-item/two",
                ]
              }
            }
          }
        }
      })

      const result = consumesOptionsFor(state, ["/", "get"])

      expect(result.toJS()).toEqual([
        "path-item/one",
        "path-item/two",
      ])
    })
    it("should return a global consumes value", function () {
      const state = fromJS({
        json: {
          consumes: [
            "global/one",
            "global/two",
          ],
          paths: {
            "/": {
              "get": {
                description: "my operation"
              }
            }
          }
        }
      })

      const result = consumesOptionsFor(state, ["/", "get"])

      expect(result.toJS()).toEqual([
        "global/one",
        "global/two",
      ])
    })
    it("should favor an operation consumes value over a path-item value", function () {
      const state = fromJS({
        json: {
          paths: {
            "/": {
              consumes: [
                "path-item/one",
                "path-item/two",
              ],
              "get": {
                description: "my operation",
                consumes: [
                  "operation/one",
                  "operation/two",
                ]
              }
            }
          }
        }
      })

      const result = consumesOptionsFor(state, ["/", "get"])

      expect(result.toJS()).toEqual([
        "operation/one",
        "operation/two",
      ])
    })
    it("should favor a path-item consumes value over a global value", function () {
      const state = fromJS({
        json: {
          consumes: [
            "global/one",
            "global/two",
          ],
          paths: {
            "/": {
              consumes: [
                "path-item/one",
                "path-item/two",
              ],
              "get": {
                description: "my operation"
              }
            }
          }
        }
      })

      const result = consumesOptionsFor(state, ["/", "get"])

      expect(result.toJS()).toEqual([
        "path-item/one",
        "path-item/two",
      ])
    })
  })
  describe("taggedOperations", function () {
    it("should return a List of ad-hoc tagged operations", func