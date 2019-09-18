to the original", function(){

        // Given
        const system = new System({
          plugins: [
            {
              statePlugins: {
                doge: {
                  selectors: {
                    wow: () => (system) => {
                      return "original"
                    }
                  }
                }
              }
            },
            {
              statePlugins: {
                doge: {
                  wrapSelectors: {
                    wow: (ori) => (system) => {
                      // Then
                      return ori() + " wrapper"
                    }
                  }
                }
              }
            }
          ]
        })

        // When
        var res = system.getSystem().dogeSelectors.wow(1)
        expect(res).toEqual("original wrapper")

      })

      it("should provide a live reference to the system to a wrapper", function(done){

        // Given
        const mySystem = new System({
          plugins: [
            {
              statePlugins: {
                doge: {
                  selectors: {
                    wow: () => (system) => {
                      return "original"
                    }
                  }
                }
              }
            },
            {
              statePlugins: {
                doge: {
                  wrapSelectors: {
                    wow: (ori, system) => () => {
                      // Then
                      expect(mySystem.getSystem()).toEqual(system.getSystem())
                      done()
                      return ori() + " wrapper"
                    }
                  }
                }
              }
            }
          ]
        })

        mySystem.getSystem().dogeSelectors.wow(1)
      })

