openapi: 3.0.0
servers:
  - url: http://example.com/v1
    description: Production server version 1
  - url: http://staging-api.example.com
    description: Staging server
info:
  description: |
    This is an API documentation of example.
  version: "0.1.0"
  title: Example
  termsOfService: 'http://www.example.com/terms/'
  contact:
    email: developer@example.com
  license:
    name: Proprietary license
    url: 'http://www.example.com/license/'
tags:
  - name: agent
    description: Access to example
paths:
  /agents/{agentId}:
    put:
      tags:
        - agent
      summary: Edit agent
      operationId: editAgent
      parameters:
        - in: path
          name: agentId
          schema:
            type: integer
          example: 12345
          required: true
          description: Numeric ID of the paper agent to edit
      requestBody:
        required: true
        content:
          application/json_media-type-level:
            schema:
              type: object
              properties:
                code:
                  type: string
                name:
                  type: string
            example:
              code: AE1
              name: Andrew
          application/json_schema-level:
            schema:
              type: object
              properties:
                code:
                  type: string
                name:
                  type: string
              example:
                code: AE1
                name: Andrew
          application/json_property-level:
            schema:
              type: object
              properties:
                code:
              