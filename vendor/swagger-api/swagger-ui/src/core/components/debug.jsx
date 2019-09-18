Authorized</h6> }

        { ( flow === IMPLICIT || flow === ACCESS_CODE ) && <p>Authorization URL: <code>{ schema.get("authorizationUrl") }</code></p> }
        { ( flow === PASSWORD || flow === ACCESS_CODE || flow === APPLICATION ) && <p>Token URL:<code> { schema.get("tokenUrl") }</code></p> }
        <p className="flow">Flow: <code>{ schema.get("flow") }</code></p>

        {
          flow !== PASSWORD ? null
            : <Row>
              <Row>
                <label htmlFor="oauth_username">username:</label>
                {
                  isAuthorized ? <code> { this.state.username } </code>
                    : <Col tablet={10} desktop={10}>
                      <input id="oauth_username" type="text" data-name="username" onChange={ this.onInputChange }/>
                    </Col>
                }
              </Row>
              {

              }
              <Row>
                <label htmlFor="oauth_password">password:</label>
                {
                  isAuthorized ? <code> ****** </code>
                    : <Col tablet={10} desktop={10}>
             