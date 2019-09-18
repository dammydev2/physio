import React from "react"
import PropTypes from "prop-types"
import formatXml from "xml-but-prettier"
import toLower from "lodash/toLower"
import { extractFileNameFromContentDispositionHeader } from "core/utils"
import win from "core/window"

export default class ResponseBody extends React.PureComponent {
  state = {
    parsedContent: null
  }

  static propTypes = {
    content: PropTypes.any.isRequired,
    contentType: PropTypes.string,
    getComponent: PropTypes.func.isRequired,
    headers: PropTypes.object,
    url: PropTypes.string
  }

  updateParsedContent = (prevContent) => {
    const { content } = this.props

    if(prevContent === content) {
      return
    }

    if(content && content instanceof Blob) {
      var reader = new FileReader()
      reader.onload = () => {
        this.setState({
          parsedContent: reader.result
        })
      }
      reader.readAsText(content)
    } else {
      this.setState({
        parsedContent: content.toString()
      })
    }
  }

  componentDidMount() {
   