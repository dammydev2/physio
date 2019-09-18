                        "name": "username",
                        "in": "path",
                        "description": "The name that needs to be deleted",
                        "required": true,
                        "type": "string"
                    }],
                "responses": {
                    "400": {
                        "description": "Invalid username supplied"
                    },
                    "404": {
                        "description": "User not found"
                    }
                }
            }
        }
    },
    "securityDefinitions": {
        "petstore_auth": {
            "type": "oauth2",
            "authorizationUrl": "http://petstore.swagger.io/oauth/dialog",
            "flow": "implicit",
            "scopes": {
                "write:pets": "modify pets in your account",
                "read:pets": "read your pets"
            }
        },
        "api_key": {
            "type": "apiKey",
            "name": "api_key",
            "in": "header"
        }
    },
    "definitions": {
        "Order": {
            "type": "object",
            "properties": {
                "id": {
                    "type": "integer",
                    "format": "int64"
                },
                "petId": {
                    "type": "integer",
                    "format": "int64"
                },
                "quantity": {
                    "type": "integer",
                    "format": "int32"
                },
                "shipDate": {
                    "type": "string",
                    "format": "date-time"
                },
                "status": {
                    "type": "string",
                    "description": "Order Status",
                    "enum": ["placed", "approved", "delivered"]
                },
                "complete": {
                    "type": "boolean",
                    "default": false
                }
            },
            "xml": {
                "name": "Order