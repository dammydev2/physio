         {
            components: {
              ContainerComponent
            }
          },
          {
            statePlugins: {
              example: {
                selectors: {
                  foo() { return "and this came from the system" }
                }
              }
            }
          }
        ]
      })

      // When
      var Component = system.getSystem().getComponent("ContainerComponent", true)
      const renderedComponent = render(
        <Provider store={system.getStore()}>
          <Component fromOwnProps="and this came from my own props" />
        </Provider>
      )

      // Then
      expect(renderedComponent.text()).toEqual("This came from mapStateToProps and this came from the system and this came from my own props")
    })

    it("gives the system and own props as props to a container's `mapStateToProps` function", function() {
      // Given
      class ContainerComponent extends PureComponent {
        mapStateToProps(nextState, props) {
          const { exampleSelectors, fromMapState, fromOwnProps } = props
          return {
            "fromMapState": `This came from mapStateToProps ${exampleSelectors.foo()} ${fromOwnProps}`
          }
        }

        static defaultProps = {
          "fromMapState" : ""
        }

        render() {
          const { fromMapState } = this.props
          return (
            <div>{ fromMapState }</div>
          )
        }
      }
      const system = new System({
        plugins: [
          ViewPlugin,
          {
            components: {
              ContainerComponent
            }
          },
          {
            statePlugins: {
              example: {
                selectors: {
                  foo() { return "and this came from the system" }
                }
              }
            }
          }
        ]
      })

      // When
      var Component = system.getSystem().getComponent("ContainerComponent", true)
      const renderedComponent = render(
        <Provider store={system.getStore()}>
          <Component fromOwnProps="and this came from my own props" />
        </Provider>
      )

      // Then
      expect(renderedComponent.text()).toEqual("This came from mapStateToProps and this came from the system and this came from my own props")
    })
  })

  describe("afterLoad", function() {
    it("should call a plugin's `afterLoad` method after the plugin is loaded", function() {
      // Given
      const system = new System({
        plugins: [
       