ll) {
    // return nothing if the operation does not exist
    return
  }

  const [path] = pathMethod

  const operationProduces = operation.get("produces", null)
  const pathItemProduces = spec.getIn(["paths", path, "produces"], null)
  const globalProduces = spec.getIn(["produces"], null)

  return operationProduces || pathItemProduces || globalProduces
}

// Get the consumes options for an operation
export function consumesOptionsFor(state, pathMethod) {
  pathMethod = pathMethod || []

  const spec = specJsonWithResolvedSubtrees(state)
  const operation = spec.getIn(["paths", ...pathMethod], null)

  if (operation === null) {
    // return nothing if the operation does not exist
    return
  }

  const [path] = pathMethod

  const operationConsumes = operation.get("consumes", null)
  const pathItemConsumes = spec.getIn(["paths", path, "con