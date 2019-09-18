# As found on https://petstore.swagger.io, August 2018
---
swagger: '2.0'
info:
  description: 'This is a sample server Petstore server.  You can find out more about
    Swagger at [http://swagger.io](http://swagger.io) or on [irc.freenode.net, #swagger](http://swagger.io/irc/).  For
    this sample, you can use the api key `special-key` to test the authorization filters.'
  version: 1.0.0
  title: Swagger Petstore
  termsOfService: http://swagger.io/terms/
  contact:
    email: apiteam@swagger.io
  license:
    name: Apache 2.0
    url: http://www.apache.org/licenses/LICENSE-2.0.html
host: petstore.swagger.io
basePath: "/v2"
tags:
- name: pet
  description: Everything about your Pets
  externalDocs:
    description: Find out more
    url: http://swagger.io
- name: store
  description: Access to Petstore orders
- name: user
  description: Operations about user
  externalDocs:
    description: Find out more about our store
    url: http://swagger.io
schemes:
- https
- http
paths:
  "/pet":
    post:
      tags:
      - pet
      summary: Add a new pet to the store
      description: ''
      operationId: addPet
      consumes:
      - application/json
      - application/xml
      produces:
      - application/xml
      - application/json
      parameters:
      - in: body
        name: body
        description: Pet object that needs to be added to the store
        required: true
        schema:
          "$ref": "#/definitions/Pet"
      responses:
        '405':
          description: Invalid input
      security:
      - petstore_auth:
        - write:pets
        - read:pets
    put:
      tags:
      - pet
      summary: Update an existing pet
      description: ''
      operationId: updatePet
      consumes:
      - application/json
      - application/xml
      produces:
      - application/xml
      - application/json
      parameters:
      - in: body
        name: body
        description: Pet object that needs to be added to the store
        required: true
        schema:
          "$ref": "#/definitions/Pet"
      responses:
        '400':
          description: Invalid ID supplied
        '404':
          description: Pet not found
        '405':
          description: Validation exception
      security:
      - petstore_auth:
        - write:pets
        - read:pets
  "/pet/findByStatus":
    get:
      tags:
      - pet
      summary: Finds Pets by status
      description: Multiple status values can be provided with comma separated strings
      operationId: findPetsByStatus
      produces:
      - application/xml
      - application/json
      parameters:
      - name: status
        in: query
        description: Status values that need to be considered for filter
        required: true
        type: array
        items:
          type: string
          enum:
          - available
          - pending
          - sold
          default: available
        collectionFormat: multi
      responses:
        '200':
          description: successful operation
          schema:
            type: array
            items:
              "$ref": "#/definitions/Pet"
        '400':
          description: Invalid status value
      security:
      - petstore_auth:
        - write:pets
        - rea