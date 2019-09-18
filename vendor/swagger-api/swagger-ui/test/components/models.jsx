alse,
						"type": "file"
					}
				],
				"responses": {
					"200": {
						"description": "successful operation",
						"schema": {
							"$ref": "#/definitions/ApiResponse"
						}
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
		"/store/inventory": {
			"get": {
				"tags": [
					"store"
				],
				"summary": "Returns pet inventories by status",
				"description": "Returns a map of status codes to quantities",
				"operationId": "getInventory",
				"produces": [
					"application/json"
				],
				"parameters": [],
				"responses": {
					"200": {
						"description": "successful operation",
						"schema": {
							"type": "object",
							"additionalProperties": {
								"type": "integer",
								"format": "int32"
							}
						}
					}
				},
				"security": [
					{
						"api_key": []
					}
				]
			}
		},
		"/store/order": {
			"post": {
				"tags": [
					"store"
				],
				"summary": "Place an order for a pet",
				"description": "",
				"operationId": "placeOrder",
				"produces": [
					"application/xml",
					"application/json"
				],
				"parameters": [
					{
						"in": "body",
						"name": "body",
						"description": "order placed for purchasing the pet",
						"required": true,
						"schema": {
							"$ref": "#/definitio