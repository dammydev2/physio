ual(1)
      expect(system.specActions.setRequest.calls.length).toEqual(1)
    })
  })

  xit("should call specActions.setResponse, when fn.execute resolves", function(){

    const response = {serverResponse: true}
    const system = {
      fn: {
        execute: createSpy().andReturn(Promise.resolve(response))
      },
      specActions: {
        setResponse: createSpy()
      },
      errActions: {
        newSpecErr: createSpy()
      }
    }

    // When
    let executeFn = executeRequest({
      pathName: "/one",
      method: "GET"
    })
    let executePromise = executeFn(system)

    // Then
    return executePromise.then( () => {
      expect(system.specActions.setResponse.calls.length).toEqual(1)
      expect(system.specActions.setResponse.calls[0].arguments).toEqual([
        "/one",
        "GET",
        response
      ])
    })
  }