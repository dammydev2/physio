
    const mySystem = new System({
      plugins: [
        () => {
          return {
            statePlugins: {
              doge: {
                selectors: {
                  wow: () => () => {
                    return "WOW much data"
                  }
                }
              }
            },
            components: {
              wow: () => <div>Original component</div>
            }
          }
        }
      ]
    })

    mySystem.register([
      function() {
        return {
          // Wrap the component and use the system
          wrapComponents: {
            wow: (OriginalComponent, system) => (props) => {
              return <container>
                <OriginalComponent {...props}></OriginalComponent>
                <div>{system.dogeSelectors.wow()}</div>
              </container>
            }
          }
        }
      }
    ])

    // Then
    var Component = mySystem.getSystem().getComponents("wow")
    const wrapper = render(<Component name="Normal" />)

    const container = wrapper.children().first()
    expect(container[0].name).toEqual("container")

    const children = container.children()
    expect(children.length).toEqual(2)
    expect(children.eq(0).text()).toEqual("Original component")
    expect(chi