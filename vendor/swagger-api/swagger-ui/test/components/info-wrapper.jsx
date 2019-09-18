Type: "one",
        responseContentType: "application/xml"
      })
    })

    it("should default to `application/json` if a default produces value is not available", function(){
      // Given
      let state = fromJS({
        json: {
          paths: {
            "/one": {
              get: {}
            }
          }
        },
        meta: {
          paths: {
            "/one": {
              get: {
                "consumes_value": "one"
              }
            }
          }
        }
      })

      // When
      let contentTypes = contentTypeValues(state, [ "/one", "get" ])
      // Then
      expect(contentTypes.toJS()).toEqual({
        requestContentType: "one",
        responseContentType: "application/json"
      })
    })

    it("should prioritize consumes value first from an operation", function(){
      // Given
      let state = fromJS({
        json: {
          paths: {
            "/one": {
              get: {
                "parameters": [{
                  "type": "file"
                }],
              }
            }
          }
        },
        meta: {
          paths: {
            "/one": {
              get: {
                "consumes_value": "one",
              }
            }
          }
        }
      })

      // When
      let contentTypes = contentTypeValues(state, [ "/one", "get" ])
      // Then
      expect(contentTypes.toJS().requestContentType).toEqual("one")
    })

    it("should fallback to multipart/form-data if there is no consumes value but there is a file parameter", function(){
      // Given
      let state = fromJS({
        json: {
          paths: {
            "/one": {
              get: {
              