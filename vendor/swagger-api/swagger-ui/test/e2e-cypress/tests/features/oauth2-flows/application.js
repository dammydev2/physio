ion: ID of pet to return
        required: true
        schema:
          type: integer
          format: int64
      responses:
        '200':
          description: successful operation
          content:
            application/xml:
              schema:
                "$ref": "#/components/schemas/Pet"
            application/json:
              schema:
                "$ref": "#/components/schemas/Pet"
        '400':
          description: Invalid ID supplied
        '404':
          description: Pet not found
      security:
      - api_key: []
    post:
      tags:
      - pet
      summary: Updates a pet in the store with form data
      description: ''
      operationId: updatePetWithForm
      parameters:
      - name: petId
        in: path
        description: ID of pet that needs to be updated
        required: true
        schema:
          type: integer
          format: int64
      responses:
        '405':
          description: Invalid input
      security:
      - petstore_auth:
        - write:pets
        - read:pets
      requestBody:
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                name:
                  description: Updated name of the pet
         