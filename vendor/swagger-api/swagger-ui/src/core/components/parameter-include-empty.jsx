import { createSelector } from "reselect"
import { List, Map, fromJS } from "immutable"
import { isOAS3 as isOAS3Helper } from "../helpers"


// Helpers

const state = state => state

function onlyOAS3(selector) {
  return (ori, system) => (state, ...args) => {
    const spec = system.getSystem().specSelectors.specJson()
    if(isOAS3Helper(spec)) {
      return selector(system, ...args)
    } else {
      return ori(...args)
    }
  }
}

export const definitionsToAuthorize = onlyOAS3(createSelector(
    state,
    ({specSelectors}) => specSelectors.securityDefinitions(),
    (system, definitions) => {
      // Coerce our OpenAPI 3.0 definitions into monoflow definitions
      // that look like Swagger2 definitions.
      let list = List()

      if(!definitions) {
        return list
      }

      definitions.entrySeq().fo