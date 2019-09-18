Request = ( data ) => ( { fn, getConfigs, authActions, errActions, oas3Selectors, specSelectors, authSelectors } ) => {
  let { body, query={}, headers={}, name, url, auth } = data

  let { additionalQueryStringParams } = authSelectors.getConfigs() || {}

  let parsedUrl

  if (specSelectors.isOAS3()) {
    parsedUrl = parseUrl(url, oas3Selectors.selectedServer(), true)
  } else {
    parsedUrl = parseUrl(url, specSelectors.url(), true)
  }

  if(typeof additionalQueryStringParams === "object") {
    parsedUrl.query = Object.assign({}, parsedUrl.query, additionalQueryStringParams)
  }

  const fetchUrl = parsedUrl.toString()

  let _headers = Object.assign({
    "Accept":"application/json, text/plain, */*",
    "Content-Type": "application/x-www-form-urlencoded",
    "X-Requested-With": "XMLHttpRequest"
  }, headers)

  fn.fetch({
    url: fetchUrl,
    method: "post",
    headers: _headers,
    query: query,
    body: body,
    requestInterceptor: getConfigs().requestInterceptor,
    responseInterceptor: getConfigs().responseInterceptor
  })
  .then(function (response) {
    let token = JSON.parse(response.data)
    let error = token && ( token.error || "" )
    let parseError = token && ( token.parseError || "" )

    if ( !response.ok ) {
      errActions.newAuthErr( {
        authId: name,
        level: "error",
        source: "auth",
        message: response.statusText
      } )
      return
    }

    if ( error || parseError ) {
      errActions.newAuthErr({
        authId: name,
        level: "error",
        source: "auth",
        message: JSON.stringify(token)
      })
      return
    }

    authActions.authorizeOauth2({ auth, token})
  })
  .catch(e => {
    let err = new Error(e)
    let message = err.message
    // swagger-js wraps the response (if available) into the e.response property;
    // investigate to check whether there are more details on why the authorization
    // request failed (according to RFC 6479).
    // See also https://github.com/swagger-api/swagger-ui/issues/4048
    if (e.response && e.response.data) {
      const errData = e.response.data
      try {
        const jsonResponse = typeof errData === "string" ? JSON.parse(errData) : errData
        if (jsonRespo