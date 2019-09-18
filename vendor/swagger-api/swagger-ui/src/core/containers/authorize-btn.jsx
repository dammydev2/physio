export const schemes = createSelector(
    spec,
    spec => spec.get("schemes", Map())
)

export const operationsWithRootInherited = createSelector(
  operations,
  consumes,
  produces,
  (operations, consumes, produces) => {
    return operations.map( ops => ops.update("operation", op => {
      if(op) {
        if(!Map.isMap(op)) { return }
        return op.withMutations( op => {
          if ( !op.get("consumes") ) {
            op.update("consumes", a => Set(a).merge(consumes))
          }
          if ( !op.get("produces") ) {
            op.update("produces", a => Set(a).merge(produces))
          }
          return op
        })
      } else {
        // return something with Immutable methods
        return Map()
      }

    }))
  }
)

export const tags = createSelector(
  spec,
  json => {
    const tags = json.get("tags", List())
    return List.isList(tags) ? tags.filter(tag => Map.isMap(tag)) : List()
  }
)

export const tagDetails = (state, tag) => {
  let currentTags 