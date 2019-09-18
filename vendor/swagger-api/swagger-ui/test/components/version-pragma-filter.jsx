  })

    it("should encapsulate thrown errors in a complex selector", function(){

      // Given
      const system = new System({
        plugins: {
          statePlugins: {
            throw: {
              selectors: {
                func: (state, arg1) => system => {
                  throw new Error("this selector THROWS!")
                }
              }
            }
          }
        }

      })

      expect(system.getSystem().throwSelectors.func).toNotThrow()
    })

    it("should encapsulate thrown errors in a wrapAction", function(){

      // Given
      const system = new System({
        plugins: {
          statePlugins: {
            throw: {
              actions: {
                func: () => {
                  return {
                    type: "THROW_FUNC",
                    payload: "this original action does NOT throw"
                  }
                }
              },
              wrapActions: {
                func: (ori) => (...args) => {
                  throw new Error("this wrapAction UNRAVELS EVERYTHING!")
                }
              }
            }
          }
        }

      })

      expect(system.getSystem().throwActions.func).toNotThrow()
    })

    it("should encapsulate thrown errors in a wrapSelector", function(){

      // Given
      const system = new System({
        plugins: {
          statePlugins: {
            throw: {
              selectors: {
                func: (state, arg1) => {
                  return 123
                }
              },
              wrapSelectors: {
                func: (ori) => (...props) => {
                  return ori(...props)
                }
              }
            }
          }
        }

      })

      expect(system.getSystem().throwSelectors.func).toNotThrow()
    })

    describe("components", function() {
      it("should catch errors thrown inside of React Component Class render methods", function() {
        // Given
        // eslint-disable-next-line react/require-render-return
        class BrokenComponent extends React.Component {
          render() 