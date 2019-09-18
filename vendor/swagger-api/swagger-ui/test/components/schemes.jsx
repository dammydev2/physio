const system = new System({
        plugins: [
          () => {
            return [MyPlugin]
          }
        ]
      })

      // When
      var res = system.getSystem().wow()
      expect(res).toEqual("so selective")
    })
    it("should call a registered plugin's `afterLoad` method after the plugin is loaded", function() {
      // Given
      const MyPlugin = {
        afterLoad(system) {
          this.rootInjects.wow = system.dogeSelectors.wow
        },
        statePlugins: {
          doge: {
            selectors: {
              wow: () => (system) => {
                return "so selective"
              }
            }
          }
        }
      }

      const system = new System({
        plugins: []
      })

      system.register([MyPlugin])

      // When
      var res = system.getSystem().wow()
      expect(res).toEqual("so selective")
    })
  })

  describe("rootInjects", function() {
    it("should attach a rootInject function as an instance method", function() {
      // This is the same thing as the `afterLoad` tests, but is here for posterity
      
      // Given
      const system = new System({
        plugins: [
          {
            afterLoad(system) {
              this.rootInjects.wow = system.dogeSelectors.wow
            },
            statePlugins: {
              doge: {
                selectors: {
                  wow: () => (system) => {
                    return "so selective"
                  }
                }
              }
            }
          }
        ]
      })

      // When
      var res = system.getSystem().wow()
      expect(res).toEqual("so selective")
    })
  })

  describe("error catching", function() {
    it("should encapsulate thrown errors in an afterLoad method", function() {
      // Given
      const ThrowyPlugin = {
        afterLoad(sys