					"application/xml",
					"application/json"
				],
				"parameters": [
					{
						"name": "tags",
						"in": "query",
						"description": "Tags to filter by",
						"required": true,
						"type": "array",
						"items": {
							"type": "string"
						},
						"collectionFormat": "multi"
					}
				],
				"responses": {
					"200": {
						"description": "successful operation",
						"schema": {
							"type": "array",
							"items": {
								"$ref": "#/definitions/Pet"
							}
						}
					},
					"400": {
						"description": "Invalid tag value"
					}
				},
				"security": [
					{
						"petstore_auth": [
							"write:pets",
							"read:pets"
						]
					}
				],
				"deprecated": true
			}
		},
		"/pet/{petId}": {
			"get": {
				"tags": [
					"pet"
				],
				"summary": "Find pet by ID",
				"description": "Returns a single pet",
				"operationId": "getPetById",
				"produces": [
					"application/xml",
					"application/json"
				],
				"parameters": [
					{
						"name": "petId",
						"in": "path",
						"description": "ID of pet to return",
						"required": true,
						"type": "integer",
						"format": "int64"
					}
				],
				"responses": {
					"200": {
						"description": "successful operation",
						"schema": {
							"$ref": "#/definitions/Pet"
						}
					},
					"400": {
						"description": "Invalid ID supplied"
					},
					"404": {
						"description": "Pet not found"
					}
				},
				"security": [
					{
						"api_key": []
					}
				]
			},
			"post": {
				"tags": [
					"pet"
				],
				"summary": "Updates a pet in the store with form data",
				"description": "",
				"operationId": "updatePetWithForm",
				"consumes": [
					"application/x-www-form-urlencoded"
				],
				"produces": [
					"application/xml",
					"application/json"
				],
				"parameters": [
					{
						"name": "petId",
						"in": "path",
						"description": "ID of pet that needs to be updated",
						"required": true,
						"type": "integer",
						"format": "int64"
					},
					{
						"name": "name",
						"in": "formData",
						"description": "Updated name of the pet",
						"required": false,
						"type": "string"
					},
					{
						"name": "status",
						"in": "formData",
						"description": "Updated status of the pet",
						"required": false,
						"type": "string"
					}
				],
				"responses": {
					"405": {
						"description": "Invalid input"
					}
				},
				"security": [
					{
						"petstore_auth": [
							"write:pets",
							"read:pets"
						]
					}
				]
			},
			"delete": {
				"tags": [
					"pet"
				],
				"summary": "Deletes a pet",
				"description": "",
				"operationId": "deletePet",
				"produces": [
					"application/xml",
					"application/json"
				],
				"parameters": [
					{
						"name": "api_key",
						"in": "header",
						"required": false,
						"type": "string"
					},
					{
						"name": "petId",
						"in": "path",
						"description": "Pet id to delete",
						"required": true,
						"type": "integer",
						"format": "int64"
					}
				],
				"responses": {
					"400": {
						"description": "Invalid ID supplied"
					},
					"404": {
						"description": "Pet not found"
					}
				},
				"security": [
					{
						"petstore_auth": [
							"write:pets",
							"read:pets"
						]
					}
				]
			}
		},
		"/pet/{petId}/uploadImage": {
			"post": {
				"tags": [
					"pet"
				],
				"summary": "uploads an image",
				"description": "",
				"operationId": "uploadFile",
				"consumes": [
					"multipart/form-data"
				],
				"produces": [
					"application/json"
				],
				"parameters": [
					{
						"name": "petId",
						"in": "path",
						"description": "ID of pet to update",
						"required": true,
						"type": "integer",
						"format": "int64"
					},
					{
						"name": "additionalMetadata",
						"in": "formData",
						"description": "Additional data 