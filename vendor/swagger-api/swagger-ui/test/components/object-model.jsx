pplication/json"
				],
				"parameters": [
					{
						"in": "body",
						"name": "body",
						"description": "List of user object",
						"required": true,
						"schema": {
							"type": "array",
							"items": {
								"$ref": "#/definitions/User"
							}
						}
					}
				],
				"responses": {
					"default": {
						"description": "successful operation"
					}
				}
			}
		},
		"/user/createWithList": {
			"post": {
				"tags": [
					"user"
				],
				"summary": "Creates list of users with given input array",
				"description": "",
				"operationId": "createUsersWithListInput",
				"produces": [
					"application/xml",
					"application/json"
				],
				"parameters": [
					{
						"in": "body",
						"name": "body",
						"description": "List of user object",
						"required": true,
						"schema": {
							"type": "array",
							"items": {
								"$ref": "#/definitions/User"
							}
						}
					}
				],
				"responses": {
					"default": {
						"description": "successful operation"
					}
				}
			}
		},
		"/user/login": {
			"get": {
				"tags": [
					"user"
				],
				"summary": "Logs user into the system",
				"description": "",
				"operationId": "loginUser",
				"produces": [
					"application/xml",
					"application/json"
				],
				"parameters": [
					{
						"name": "username",
						"in": "query",
						"description": "The user name for login",
						"required": true,
						"type": "string"
					},
					{
						"name": "password",
						"in": "query",
						"description": "The password for login in clear text",
						"required": true,
						"type": "string"
					}
				],
				"responses": {
					"200": {
						"description": "successful operation",
						"schema": {
							"type": "string"
						},
						"headers": {
							"X-Rate-Limit": {
								"type": "integer",
								"format": "int32",
								"description": "calls per hour allowed by the user"
							},
							"X-Expires-After": {
								"type": "string",
								"format": "date-time",
								"description": "date in UTC when to