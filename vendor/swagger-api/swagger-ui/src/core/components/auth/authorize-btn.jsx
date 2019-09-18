import React, { Component } from "react"
import PropTypes from "prop-types"
import Im, { Map, List } from "immutable"
import ImPropTypes from "react-immutable-proptypes"
import { OAS3ComponentWrapFactory } from "../helpers"

// More readable, just iterate over maps, only
const eachMap = (iterable, fn) => iterable.valueSeq().filter(Im.Map.isMap).map(fn)

class Parameters extends Component {

  constructor(props) {
   super(props)
   this.state = {
     callbackVisible: false,
     parametersVisible: true
   }
 }

  static propTypes = {
    parameters: ImPropTypes.list.isRequired,
    specActions: PropTypes.object.isRequired,
    operation: PropTypes.object.isRequired,
    getComponent: PropTypes.func.isRequired,
    getConfigs: PropTypes.func.isRequired,
    specSelectors: PropTypes.object.isRequired,
    oas3Actions: PropTypes.object.isRequired,
    oas3Selectors: PropTypes.object.isRequired,
    fn: PropTypes.object.isRequired,
    tryIt