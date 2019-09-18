Component={getComponent}
                   fn={fn}
                   param={param}
                   consumes={ specSelectors.consumesOptionsFor(pathMethod) }
                   consumesValue={ specSelectors.contentTypeValues(pathMethod).get("requestContentType") }
                   onChange={this.onChangeWrapper}
                   onChangeConsumes={onChangeConsumes}
                   isExecute={ isExecute }
                   specSelectors={ specSelectors }
                   pathMethod={ pathMethod }
      />

    const ModelExample = getComponent("modelExample")
    const Markdown = getComponent("Markdown")
    const ParameterExt = getComponent("ParameterExt")
    const ParameterIncludeEmpty = getComponent("ParameterIncludeEmpty")

    let paramWithMeta = specSelectors.parameterWithMetaByIdentity(pathMethod, rawParam)
    let format = param.get("format")
    let schema = isOAS3 && isOAS3() ? param.get("schema") : param
    let type = schema.get("type")
    let isFormData = inType === "formData"
    let isFormDataSupported = "FormData" in win
    let required = param.get("required")
    let itemType = schema.getIn(["items", "type"])

    let value = paramWithMeta ? paramWithMeta.get("value") : ""
    let commonExt = showCommonExtensions ? getCommonExtensions(param) : null
    let extensions = showExtensions ? getExtensions(param) : null

    let paramItems // undefined
    let paramEnum // undefined
    let paramDefaultValue // undefined
    let paramExample // undefined
    let isDisplayParamEnum = false

    if ( param !== undefined ) {
      paramItems = schema.get("items")
    }

    if (paramItems !== undefined) {
      paramEnum = paramItems.get("enum")
      paramDefaultValue = paramItems.get("default")
    } else {
      paramEnum = schema.get("enum")
    }

    if ( paramEnum !== undefined && paramEnum.size > 0) {
      isDisplayParamEnum = true
    }

    // Default and Example Value for readonly doc
    if ( param !== undefined ) {
      paramDefaultValue = schema.get("default")
      paramExample = param.get("example")
      if (paramExample === undefined) {
        paramExample = param.get("x-example")
      }
    }

    return (
      <tr data-param-name={param.get("name")} 