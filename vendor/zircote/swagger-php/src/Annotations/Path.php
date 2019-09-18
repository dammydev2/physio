uccessful operation",
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
                                "description": "date in UTC when token expires"
                            }
                        }
                    },
                    "400": {
                        "description": "Invalid username/password supplied"
                    }
                }
            }
        },
        "/user/logout": {
            "get": {
                "tags": ["user"],
                "summary": "Logs out current logged in user session",
                "description": "",
                "operationId": "logoutUser",
                "produces": ["application/xml", "application/json"],
                "parameters": [],
                "responses": {
                    "default": {
                        "description": "successful operation"
                    }
                }
            }
        },
        "/user/{username}": {
            "get": {
                "tags": ["user"],
                "summary": "Get user by user name",
                "description": "",
                "operationId": "getUserByName",
                "produces": ["application/xml", "application/json"],
                "parameters": [{
                        "name": "username",
                        "in": "path",
                        "description": "The name that needs to be fetched. Use user1 for testing. ",
                        "required": true,
                        "type": "string"
                    }],
                "responses": {
                    "200": {
                        "description": "successful operation",
                        "schema": {
                            "$ref": "#/definitions/User"
                        }
                    },
                    "400": {
                        "description": "Invalid username supplied"
                    },
                    "404": {
                        "description": "User not found"
                    }
                }
            },
            "put": {
                "tags": ["user"],
                "summary": "Updated user"