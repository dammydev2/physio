import { createSelector } from "reselect"
import { specJsonWithResolvedSubtrees } from "../../spec/selectors"
import { Map } from "immutable"
import { isOAS3 as isOAS3Helper, isSwagger2 as isSwagger2Helper } from "../helpers"


// Helpers

function onlyOAS3(selector) {
  return (ori, system) => (...args) => {
    const spec = system.getSystem().specSelectors.specJson()
    if(isOAS3Helper(spec)) {
      return selector(...args)
    } else {
      return ori(...args)
    }
  }
}

const state = state => {
  return state || Map()
}

const nullSelector = createSelector(() => null)

const OAS3NullSelector = onlyOAS3(nullSelector)

const specJson = createSelector(
  state,
  spec => spec.get("json", Map())
)

const specResolved = createSelector(
  state,
  spec => spec.get("resolved", Map())
)

const spec = state => {
