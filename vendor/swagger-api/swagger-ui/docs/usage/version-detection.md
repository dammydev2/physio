import React, { Component } from "react"
import { Map } from "immutable"
import PropTypes from "prop-types"
import ImPropTypes from "react-immutable-proptypes"
import win from "core/window"
import { getExtensions, getCommonExtensions, numberToString } from "core/utils"

export default class ParameterRow extends Component {
  static propTypes = {
    onChange: PropTypes.func.isRequired,
    param: PropTypes.object.isRequired,
    rawParam: PropTypes.object.isRequired,
    getComponent: PropTypes.func.isRequired,
    fn: PropTypes.object.isRequired,
    isExecute: PropTypes.bool,
    onChangeConsumes: PropTypes.func.isRequired,
    specSelectors: PropTypes.object.isRequired,
    specActions: PropTypes.object.isRequired,
    pathMethod: PropTypes.array.isRequired,
    getConfigs: PropTypes.func.isRequired,
    specPath: ImPropTypes.list.isRequired
  }

  constructor(props, context) {
    super(props, context)

    this.setDefaultValue()
  }

  componentWillReceiveProps(props) {
    let { specSelectors, pathMethod, rawParam } = props
    let { isOAS3 } = specSelectors

    let parameterWithMeta = specSelectors.parameterWithMetaByIdentity(pathMethod, rawParam) || new Map()
    // fallback, if the meta lookup fails
    parameterWithMeta = parameterWithMeta.isEmpty() ? rawParam : parameterWithMeta

    let enumValue

    if(isOAS3()) {
      let schema = parameterWithMeta.get("schema") || Map()
      enumValue = schema.get("enum")
    } else {
      enumValue = parameterWithMeta ? parameterWithMeta.get("enum") : undefined
    }
    let paramValue = parameterWithMeta ? parameterWithMeta.get("value") : undefined

    let value

    if ( paramValue !== undefined ) {
      value = paramValue
    } else if ( rawParam.get("required") && enumValue && enumValue.size ) {
      value = enumValue.first()
    }

    if ( value !== undefined && value !== paramValue ) {
      this.onChangeWrapper(numberToString(value))
    }

    this.setDefaultValue()
  }

  onChangeWrapper = (value, isXml = false) => {
    let { onChange, rawParam } = this.props
    let valueForUpstream
    
    // Coerce empty strings and empty Immutable objects to null
    if(value === "" || (value && value.size === 0)) {
      valueForUpstream = null
    } else {
      valueForUpstream = value
    }

    return onChange(rawParam, valueForUpstream, isXml)
  }

  onChangeIncludeEmpty = (newValue) => {
    let { specActions, param, pathMethod } = this.props
    const paramName = param.get("name")
    const paramIn = param.get("in")
    return specActions.updateEmptyParamInclusion(pathMetho