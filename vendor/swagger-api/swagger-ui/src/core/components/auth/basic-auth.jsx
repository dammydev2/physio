import YAML from "@kyleshockey/js-yaml"
import { Map } from "immutable"
import parseUrl from "url-parse"
import serializeError from "serialize-error"
import isString from "lodash/isString"
import debounce from "lodash/debounce"
import set from "lodash/set"
import { isJSONObject, paramToValue } from "core/utils"

// Actions conform to FSA (flux-standard-actions)
// {type: string,payload: Any|Error, meta: obj, error: bool}

export const UPDATE_SPEC = "spec_update_spec"
export const UPDATE_URL = "spec_update_url"
export const UPDATE_JSON = "spec_update_json"
export const UPDATE_PARAM = "spec_update_param"
export const UPDATE_EMPTY_PARAM_INCLUSION = "spec_update_empty_param_inclusion"
export const VALIDATE_PARAMS = "spec_validate_param"
export const SET_RESPONSE = "spec_set_response"
export const SET_REQUEST = "spec_set_request"
export const SET_MUTATED_REQUEST = "spec_set_mutated_request"
export const LOG_REQUEST = "spec_log_request"
export const CLEAR_RESPONSE = "spec_clear_response"
export const CLEAR_REQUEST = "spec_clear_request"
export const CLEAR_VALIDATE_PARAMS = "spec_clear_validate_param"
export const UPDATE_OPERATION_META_VALUE = "spec_update_operation_meta_value"
export const UPDATE_RESOLVED = "spec_update_resolved"
export const UPDATE_RESOLVED_SUBTREE = "spec_update_resolved_subtree"
export const SET_SCHEME = "set_scheme"

const toStr = (str) => isString(str) ? str : ""

export function updateSpec(spec) {
  const cleanSpec = (toStr(spec)).replace(/\t/g, "  ")
  if(typeof spec === "string") {
    return {
      type: UPDATE_SPEC,
      payload: cleanSpec
    }
  }
}

export function updateResolved(spec) {
  return {
    type: UPDATE_RESOLVED,
    payload: spec
  }
}

export function updateUrl(url) {
  return {type: UPDATE_URL, payload: url}
}

export function updateJsonSpec(json) {
  return {type: UPDATE_JSON, payload: json}
}

export const parseToJson = (str) => ({specActions, specSelectors, errActions}) => {
  let { specStr } = specSelectors

  let json = null
  try {
    str = str || specStr()
    errActions.clear({ source: "parser" })
    json = YAML.safeLoad(str)
  } catch(e) {
    // TODO: push error to state
    console.error(e)
    return errActions.newSpecErr({
      source: "parser",
      level: "error",
      message: e.reason,
      line: e.mark && e.mark.line ? e.mark.line + 1 : undefined
    })
  }
  if(json && typeof json === "object") {
    return specActions.updateJsonSpec(json)
  }
  return {}
}

let hasWarnedAboutResolveSpecDeprecation = false

export const resolveSpec = (json, url) => ({specActions, specSelectors, errActions, fn: { fetch, resolve, AST = {} }, getConfigs}) => {
  if(!hasWarnedAboutResolveSpecDeprecation) {
    console.warn(`specActions.resolveSpec is deprecated since v3.10.0 and will be removed in v4.0.0; use requestResolvedSubtree instead!`)
    hasWarnedAboutResolveSpecDeprecation = 