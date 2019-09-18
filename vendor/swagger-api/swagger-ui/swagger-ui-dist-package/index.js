cted = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<animals>\n\t<dog>string</dog>\n\t<additionalProp>string</additionalProp>\n</animals>"
      var definition = {
        type: "object",
        properties: {
          dog: {
            type: "string"
          }
        },
        additionalProperties: {
          type: "string"
        },
        xml: {
          name: "animals"
        }
      }

      expect(sut(definition)).toEqual(expected)
    })

    it("returns object with additional props =true", function () {
      var expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<animals>\n\t<dog>string</dog>\n\t<additionalProp>Anything can be here</additionalProp>\n</animals>"
      var definition = {
        type: "object",
        properties: {
          dog: {
            type: "string"
   