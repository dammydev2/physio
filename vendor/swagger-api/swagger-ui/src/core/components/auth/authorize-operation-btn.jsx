ssName="parameters">
                <thead>
                  <tr>
                    <th className="col col_header parameters-col_name">Name</th>
                    <th className="col col_header parameters-col_description">Description</th>
                  </tr>
                </thead>
                <tbody>
                  {
                    eachMap(parameters, (parameter, i) => (
                      <ParameterRow fn={ fn }
                        getComponent={ getComponent }
                        specPath={specPath.push(i)}
                        getConfigs={ getConfigs }
                        rawParam={ parameter }
                        param={ specSelectors.parameterWithMetaByIdentity(pathMethod, parameter) }
                        key={ parameter.get( "name" ) }
                        onChange={ this.onChange }
         