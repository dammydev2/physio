import { createSelector } from "reselect"
import { sorters } from "core/utils"
import { fromJS, Set, Map, OrderedMap, List } from "immutable"
import { paramToIdentifier } from "../../utils"

const DEFAULT_TAG = "default"

const OPERATION_METHODS = [
  "get", "put", "post", "delete", "options", "head", "patch", "trace"
]

const state = state => {
  return state || Map()
}

export const lastError = createSelector(
  state,
  spec => spec.get("lastError")
)

export const url = createSelector(
  state,
  spec => spec.get("url")
)

export const specStr = createSelector(
  state,
  spec => spec.get("spec") || ""
)

export const specSource = createSelector(
  state,
  spec => spec.get("specSource") || "not-editor"
)

export const specJson = createSelector(
  state,
  spec => spec.get("json", Map())
)

export const specResolved = createSelector(
  state,
  spec => spec.get("resolved", Map())
)

export const specResolvedSubtree = (state, path) => {
  return state.getIn(["resolvedSubtrees", ...path], undefined)
}

const mergerFn = (oldVal, newVal) => {
  if(Map.isMap(oldVal) && Map.isMap(newVal)) {
    if(newVal.get("$$ref")) {
      // resolver artifacts indicated that this key was directly resolved
      // so we should drop the old value entirely
      return newVal
    }

    return OrderedMap().mergeWith(
      mergerFn,
      oldVal,
      newVal
    )
  }

  return newVal
}

export const specJsonWithResolvedSubtrees = createSelector(
  state,
  spec => OrderedMap().mergeWith(
    mergerFn,
    spec.get("json"),
    spec.get("resolvedSubtrees")
  )
)

// Default Spec ( as an object )
export const spec = state => {
  let res = specJson(state)
  return res
}

export const isOAS3 = createSelector(
 