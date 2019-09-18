ter_inclusions", paramKey], false)
}


export const parameterWithMeta = (state, pathMethod, paramName, paramIn) => {
  const opParams = specJsonWithResolvedSubtrees(state).getIn(["paths", ...pathMethod, "parameters"], OrderedMap())
  const currentParam = opParams.find(param => param.get("in") === paramIn && param.get("name") === paramName, OrderedMap())

  return parameterWithMetaByIdentity(state, pathMethod, currentParam)
}

export const operationWithMeta = (state, path, method) => {
  const op = specJsonWithResolvedSubtrees(state).getIn(["paths", path, method], OrderedMap())
  const meta = state.getIn(["meta", "paths", path, method], OrderedMap())

  const mergedParams = op.get("parameters", List()).map((param) => {
    return parameterWithMetaByIdentity(state, [path, method], param)
  })

  return OrderedMap()
    .merge(op, meta)
    .set("parameters", mergedParams)
}

// Get the parameter value by parameter name
export function getParameter(state, pathMethod, name, inType) {
  pathMethod = pathMethod || []
  let params = state.getIn(["meta", "paths", ...pathMethod, "parameters"], fromJS([]))
  return params.find( (p) => {
    return Map.isMap(p) && p.get("name") === name && p.get("in") === inType
  }) || Map() // Always return a map
}

export const hasHost = createSelector(
  spec,
  spec => {
    const host = spec.get("host")
    return typeof host === "string" && host.l