it("should convert an Immutable parameter map lacking an `in` value to an identifier", () => {
      const param = fromJS({
        name: "id"
      })
      
      const res = paramToIdentifier(param, { returnAll: true })
      
      expect(res).toEqual(["id"])
    })
    
    it("should throw gracefully when given a non-Immutable parameter input", () => {
      const param = {
        name: "id"
      }
      
      let error = null
      let res = null
      
      try {
        const res = paramToIdentifier(param)
      } catch(e) {
        error = e
      } 
      
      expect(error).toBeA(Error)
      expect(error.message).toInclude("received a non-Im.Map parameter as input")
      expect(res).toEqual(null)
    })
  })
  
  describe("paramToValue", function() {
    it("should identify a hash-keyed value", () => {
      const param = fromJS({
        name: "id",
        in: "query"
      })
      
      const paramValues = {
        "query.id.hash-606199662": "asdf"
      }
      
      const res = paramToValue(param, paramValues)
      
      expect(res).to