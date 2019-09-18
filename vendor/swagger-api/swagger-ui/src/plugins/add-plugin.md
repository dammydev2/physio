t be greater than Minimum"
    
    it("doesn't return for valid input", function() {
      expect(validateMinimum(2, 1)).toBeFalsy()
      expect(validateMinimum(20, 10)).toBeFalsy()
    })
    
    it("returns a message for invalid input", function() {
      expect(validateMinimum(-1, 0)).toEqual(errorMessage)
      expect(validateMinimum(1, 2)).toEqual(errorMessage)
      expect(validateMinimum(10, 20)).toEqual(errorMessage)
    })
  })
  
  describe("validateNumber", function() {
    let errorMessage = "Value must be a number"
    
    it("doesn't return for whole numbers", function() {
      expect(validateNumber(0)).toBeFalsy()
      expect(validateNumber(1)).toBeFalsy()
      expect(validateNumber(20)).toBeFalsy()
      expect(validateNumber(5000000)).toBeFalsy()
      expect(validateNumber("1")).toBeFalsy()
      expect(validateNumber("2")).toBeFalsy()
      expect(validateNumber(-1)).toBeFalsy()
      expect(validateNumber(-20)).toBeFalsy()
      expect(validateNumber(-5000000)).toBeFalsy()
    })
    
    it("doesn't return for negative numbers", function() {
      expect(validateNumber(-1)).toBeFalsy()
      expect(validateNumber(-20)).toBeFalsy()
      expect(validateNumber(-5000000)).toBeFalsy()
    })
    
    it("doesn't return for decimal numbers", function() {
      expect(validateNumber(1.1)).toBeFalsy()
      expect(validateNumber(2.5)).toBeFalsy()
      expect(validateNumber(-30.99)).toBeFalsy()
    })
    
    it("returns a message for strings", function() {
      expect(validateNumber("")).toEqual(errorMessage)
      expect(validateNumber(" ")).toEqual(errorMessage)
      expect(validateNumber("test")).toEqual(errorMessage)
    })
    
    it("returns a message for invalid input", function() {
      expect(validateNumber(undefined)).toEqual(errorMessage)
      expect(validateNumber(null)).toEqual(errorMessage)
      expect(validateNumber({})).toEqual(errorMessage)
      expect(validateNumber([])).toEqual(errorMessage)
      expect(validateNumber(true)).toEqual(errorMessage)
      expect(validateNumber(false)).toEqual(errorMessage)
    })
  })
  
  describe("validateInteger", function() {
    let errorMessage = "Value must be an integer"
    
    it("doesn't return for positive integers", function() {
      expect(validateInteger(0)).toBeFalsy()
      expect(validateInteger(1)).toBeFalsy()
      expect(validateInteger(20)).toBeFalsy()
      expect(validateInteger(5000000)).toBeFalsy()
      expect(validateInteger("1")).toBeFalsy()
      expect(validateInteger("2")).toBeFalsy()
      expect(validateInteger(-1)).toBeFalsy()
      expect(validateInteger(-20)).toBeFalsy()
      expect(validateInteger(-5000000)).toBeFalsy()
    })
    
    it("doesn't return for negative integers", function() {
      expect(validateInteger(-1)).toBeFalsy()
      expect(validateInteger(-20)).toBeFalsy()
      expect(validateInteger(-5000000)).toBeFalsy()
    })
    
    it("returns a message for decimal values", function() {
      expect(validateInteger(1.1)).toEqual(errorMessage)
      expect(validateInteger(2.5)).toEqual(errorMessage)
      expect(validateInteger(-30.99)).toEqual(errorMessage)
    })
    
    it("returns a message for strings", function() {
      expect(validateInteger("")).toEqual(errorMessage)
      expect(validateInteger(" ")).toEqual(errorMessage)
      expect(validateInteger("test")).toEqual(errorMessage)
    })
    
    it("returns a message for invalid input", function() {
      expect(validateInteger(undefined)).toEqual(errorMessage)
      expect(validateInteger(null)).toEqual(errorMessage)
      expect(validateInteger({})).toEqual(errorMessage)
      expect(validateInteger([])).toEqual(errorMessage)
      expect(validateInteger(true)).toEqual(errorMessage)
      expect(validateInteger(false)).toEqual(errorMessage)
    })
  })
  
  describe("validateFile", function() {
    let errorMessage = "Value must be a file"
    
    it("validates against objects which are instances of 'File'", function() {
      let fileObj = new win.File([], "Test File")
      expect(validateFile(fileObj)).toBeFalsy()
      expect(validateFile(null)).toBeFalsy()
      expect(validateFile(undefined)).toBeFalsy()
      expect(validateFile(1)).toEqual(errorMessage)
      expect(validateFile("string")).toEqual(errorMessage)
    })
  })
  
  describe("validateDateTime", function() {
    let errorMessage = "Value must be a DateTime"
    
    it("doesn't return for valid dates", function() {
      expect(validateDateTime("Mon, 25 Dec 1995 13:30:00 +0430")).toBeFalsy()
    })
    
    it("returns a message for invalid input'", function() {
      expect(validateDateTime(null)).toEqual(errorMessage)
      expect(validateDateTime("string")).toEqual(errorMessage)
    })
  })
  
  describe("validateGuid", function() {
    let errorMessage = "Value must be a Guid"
    
    it("doesn't return for valid guid", function() {
      expect(validateGuid("8ce4811e-cec5-4a29-891a-15d1917153c1")).toBeFalsy()
      expect(validateGuid("{8ce4811e-cec5-4a29-891a-15d1917153c1}")).toBeFalsy()
      expect(validateGuid("8CE4811E-CEC5-4A29-891A-15D1917153C1")).toBeFalsy()
      expect(validateGuid("6ffefd8e-a018-e811-bbf9-60f67727d806")).toBeFalsy()
      expect(validateGuid("6FFEFD8E-A018-E811-BBF9-60F67727D806")).toBeFalsy()
      expect(validateGuid("00000000-0000-0000-0