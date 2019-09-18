            kyle: {
                wrapActions: {
                  simple: (ori) => () => {
                    const obj = ori()
                    obj.type += "-three"
                    return obj
                  }
                }
              }
            }
          }
        ]
      })

      // When
      var action = system.getSystem().kyleActions.simple(1)
      expect(action.type).toEqual("one-two-three")

    })

    it("should have a the latest system", function(){
      // Given
      const system = new System({
        plugins: [
          {
            statePlugins: {
              kyle: {
                actions: {
                  simple: () => {
                    return {
                      type: "one",
                    }
                  }
                },
                wrapActions: {
                  simple: (ori, {joshActions}) => () => {
                    return joshActions.hello()
                  }
                }
              }
            }
          },
        ]
      })

      // When
      const kyleActions = system.getSystem().kyleActions

      system.register({
        statePlugins: {
          josh: {
            actions: {
              hello(){ return {type: "hello" } }
            }
          }
        }
      })

 