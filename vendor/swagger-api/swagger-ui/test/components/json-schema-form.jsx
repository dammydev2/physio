a correctly merged tree", function(){
      // Given
      let state = fromJS({
        json: {
          definitions: {
            Asdf: {
              $ref: "#/some/path",
              randomKey: "this should be removed b/c siblings of $refs must be removed, per the specification",
              description: "same for this key"
            },
            Fgsfds: {
              $ref: "#/another/path"
            },
            OtherDef: {
              description: "has no refs"
            }
          }
        },
        resolvedSubtrees: {
          definitions: {
            Asdf: {
              type: "object",
              $$ref: "#/some/path"
            }
          }
        }
      })

      // When
      let result = specJsonWithResolvedSubtrees(state)
      // Then
      expect(result.toJS()).toEqual({
        definitions: {
          Asdf: {
            type: "object",
            $$ref: "#/some/path"
          },
          Fgsfds: {
            $ref: "#/another/path"
          },
          OtherDef: {
            description: "has no refs"
          }
        }
      })
    })
    it("should preserve initial map key ordering", function(){
      // Given
      let state = fromJSOrdered({
        json: Petstore,
        resolvedSubtrees: {
            paths: {
              "/pet/{petId}": {
                post: {
                  tags: [
                    "pet"
                  ],
                  summary: "Updates a pet in the store with form data",
                  description: "",
                  operationId: "updatePetWithForm",
                  consumes: [
                    "application/x-www-form-urlencoded"
                  ],
                  produces: [
                    "application/xml",
                    "application/json"
                  ],
                  parameters: [
                    {
                      name: "petId",
                      "in": "path",
                      description: "ID of pet that needs to be updated",
                      required: true,
                      type: "integer",
                      format: "int64"
                    },
                    {
                      name: "name",
                      "in": "formData",
                      description: "Updated name of the pet",
                      required: false,
                      type: "string"
                    },
                    {
                      name: "status",
                      "in": "formData",
                      description: "Updated status of the pet",
                      required: false,
                      type: "string"
                    }
                  ],
                  responses: {
                    "405": {
                      description: "Invalid input"
                    }
                  },
                  security: [
                    {
                      petstore_auth: [
                        "write:pets",
                        "read:pets"
                      ]
                    }
                  ],
                  __originalOperationId: "updatePetWithForm"
                }
              }
            }
        }
      })

      // When
      let result = specJsonWithResolvedSubtrees(state)

      // Then
      const correctOrder = [
        "/pet",
        "/pet/findByStatus",
        "/pet/findByTags",
        "/pet/{petId}",
        "/pet/{petId}/uploadImage",
        "/store/inventory",
        "/store/order",
        "/store/order/{orderId}",
        "/user",
        "/user/createWithArray",
        "/user/createWithList",
        "/user/login",
        "/user/logout",
        "/user/{username}"
      ]
      expect(state.getIn(["json", "paths"]).keySeq().toJS()).toEqual(correctOrder)
      expect(result.getIn(["paths"]).keySeq().toJS()).toEqual(correctOrder)
    })
  })

  describe("operationWithMeta", function() {
    it("should support merging in {in}.{name} keyed param metadata", function () {
      const state = fromJS({
        json: {
          paths: {
            "/": {
              "get": {
                parameters: [
                  {
                    name: "myBody",
                    in: "body"
                  }
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
                  "body.myBody": {
                    value: "abc123"
                  }
                }
              }
            }
          }
        }
      })

      const result = operationWithMeta(state, "/", "get")

      expect(result.toJS()).toEqual({
        parameters: [
          {
            name: "myBody",
            in: "body",
            value: "abc123"
          }
        ]
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

      const result = operationWithMeta(state, "/", "get")

      expect(result.toJS()).toEqual({
        parameters: [
          {
            name: "myBody",
            in: "body",
            value: "abc123"
          }
        ]
      })
    })
  })
  describe("parameterWithMeta", function() {
    it("should support merging in {in}.{name} keyed param metadata", function () {
      const state = fromJS({
        json: {
          paths: {
            "/": {
              "get": {
                parameters: [
                  {
                    name: "myBody",
                    in: "body"
                  }
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
                  "body.myBody": {
                    value: "abc123"
                  }
                }
              }
            }
          }
        }
      })

      const result = parameterWithMeta(state, ["/", "get"], "myBody", "body")

      expect(result.toJS()).toEqual({
        name: "myBody",
        in: "body",
        value: "abc123"
      })
    })
    it("should give best-effort when encountering hash-keyed param metadata", function () {
      const bodyParam = fromJS({
        name: "myBody",
        in: "body"
      })

      const state = fromJS({
        json: {
          paths: {
            "/"