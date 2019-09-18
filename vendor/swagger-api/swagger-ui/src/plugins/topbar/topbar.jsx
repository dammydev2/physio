with special characters", function() {
      const result = createDeepLinkPath("!@#$%^&*(){}[]")
      expect(result).toEqual("!@#$%^&*(){}[]")
    })
    
    it("returns an empty string for invalid input", function() {
      expect( createDeepLinkPath(null) ).toEqual("")
      expect( createDeepLinkPath(undefined) ).toEqual("")
      expect( createDeepLinkPath(1) ).toEqual("")
      expect( createDeepLinkPath([]) ).toEqual("")
      expect( createDeepLinkPath({}) ).toEqual("")
    })
  })
  
  describe("escapeDeepLinkPath", function() {
    it("creates and escapes a deep link path", function() {
      const result = escapeDeepLinkPath("tag id with spaces?")
      expect(result).toEqual("tag_id_with_spaces\\?")
    })
    
    it("escapes a deep link path that starts with a number", function() {
      const result = escapeDeepLinkPath("123")
      expect(result).toEqual("\\31 23")
    })
    
    it("escapes a deep link path with a class selector", function() {
      const result = escapeDeepLinkPath("hello.world")
      expect(result).toEqual("hello\\.world")
    })
    
    it("escapes a deep link path with an id selector", function() {
      const result = escapeDeepLinkPath("hello#world")
      expect(result).toEqual("hello\\#world")
    })
    
    it("escapes a deep link path with a space", function() {
      const result = escapeDeepLinkPath("hello world")
      expect(result).toEqual("hello_world")
    })
    
    it("escapes a deep link path with a percent-encoded space", function() {
      const result = escapeDeepLinkPath("hello%20world")
      expect(result).toEqual("hello_world")
    })
  })
  
  describe("getExtensions", function() {
    const objTest = Map([[ "x-test", "a"], ["minimum", "b"]])
    it("does not error on empty array", function() {
      const result1 = getExtensions([])
      expect(result1).toEqual([])
      const result2 = getCommonExtensions([])
      expect(result2).toEqual([])
    })
    it("gets only the x- keys", function() {
      const result = getExtensions(objTest)
      expect(result).toEqual(Map([[ "x-test", "a"]]))
    })
    it("gets the common keys", function() {
      const result = getCommonExtensions(objTest, true)
      expect(result).toEqual(Map([[ "minimum", "b"]]))
    })
  })
  
  describe("deeplyStripKey", function() {
    it("should filter out a specified key", function() {
      const input = {
        $$ref: "#/this/is/my/ref",
        a: {
          $$ref: "#/this/is/my/other/ref",
          value: 12345
        }
      }
      const result = deeplyStripKey(input, "$$ref")
      expect(result).toEqual({
        a: {
          value: 12345
        }
      })
    })
    
    it("should filter out a specified key by predicate", function() {
      const input = {
        $$ref: "#/this/is/my/ref",
        a: {
          $$ref: "#/keep/this/one",
          value: 12345
        }
      }
      const result = deeplyStripKey(input, "$$ref", (v) => v !== "#/keep/this/one")
      expect(result).toEqual({
        a: {
          value: 12345,
          $$ref: "#/keep/this/one"
        }
      })
    })
    
    it("should only call the predicate when the key matches", function() {
      const input = {
        $$ref: "#/this/is/my/ref",
        a: {
          $$ref: "#/this/is/my/other/ref",
          value: 12345
        }
      }
      let count = 0
      
      const result = deeplyStripKey(input, "$$ref", () => {
        count++
        return true
      })
      expect(count).toEqual(2)
    })
  })
  
  describe("parse and serialize search", function() {
    afterEach(function() {
      win.location.search = ""
    })
    
    describe("parsing", function() {
      it("works with empty search", function() {
        win.location.search = ""
        expect(parseSearch()).toEqual({})
      })
      
      it("works with only one key", function() {
        win.location.search = "?foo"
        expect(parseSearch()).toEqual({foo: ""})
      })
      
      it("works with keys and values", function() {
        win.location.search = "?foo=fooval&bar&baz=bazval"
        expect(parseSearch()).toEqual({foo: "fooval", bar: "", baz: "bazval"})
      })
      
      it("decode url encoded components", function() {
        win.location.search = "?foo=foo%20bar"
        expect(parseSearch