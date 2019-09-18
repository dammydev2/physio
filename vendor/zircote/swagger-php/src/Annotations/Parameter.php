       "consumes": ["application/x-www-form-urlencoded"],
                "produces": ["application/xml", "application/json"],
                "parameters": [{
                        "name": "petId",
                        "in": "path",
                        "description": "ID of pet that needs to be updated",
                        "required": true,
                        "type": "integer",
                        "format": "int64"
                    }, {
                        "name": "name",
                        "in": "formData",
                        "description": "Updated name of the pet",
                        "required": false,
                        "type": "string"
                    }, {
                        "name": "status",
                        "in": "formData",
                        "description": "Updated status of the pet",
                        "required": false,
                        "type": "string"
                    }],
                "responses": {
                    "405": {
                        "description": "Invalid input"
                    }
                },
                "security": [{
                        "petstore_auth": ["write:pets", "read:pets"]
                    }]
            },
            "delete": {
                "tags": ["pet"],
                "summary": "Deletes a pet",
                "description": "",
                "operationId": "deletePet",
                "produces": ["application/xml", "application/json"],
                "parameters": [{
                        "name": "api_key",
                        "in": "header",
                        "required": false,
                        "type": "string"
                    }, {
                        "name": "petId",
                        "in": "path",
                        "description": "Pet id to delete",
                        "required": true,
                        "type": "integer",
                        "format": "int64"
                    }],
                "responses": {
                    "400": {
                        "description": "Invalid ID supplied"
                    },
                    "404": {
                        "description": "Pet not found"
                    }
                },
                "security": [{
                        "petstore_auth": ["write:pets", "read:pets"]
                    }]
            }
        },
        "/pet/{petId}/uploadImage": {
            "post": {
                "tags": ["pet"],
                "summary": "uploads an image",
                "description": "",
                "operationId": "uploadFile",
                "consumes": ["multipart/form-data"],
                "produces": ["application/json"],
                "parameters": [{
                        "name": "petId",
                        "in": "path",
                        "description": "ID of pet to update",
                        "required": true,
                        "type": "integer",
                        "format": "int64"
                    }, {
                        "name": "additionalMetadata",
                        "in": "formData",
                        "description": "Additional data to pass to server",
                        "required": false,
                        "type": "string"
                    }, {
                        "name": "file",
                        "in": "formData",
                        "description": "file to upload",
                        "required": false,
                        "type": "file"
                    }],
                "responses": {
                    "200": {
                        "description": "successful operation",
                        "schema": {
                            "$ref": "#/definitions/ApiResponse"
                        }
                    }
                },
                "security": [{
                        "petstore_auth": ["write:pets", "read:pets"]
                    }]
            }
        },
        "/store/inventory": {
            "get": {
                "tags": ["store"],
                "summary": "Returns pet inventories by status",
                "description": "Returns a map of status codes to quantities",
                "operationId": "getInventory",
                "produces": ["application/json"],
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
                "security": [{
                        "api_key": []
                    }]
            }
        },
        "/store/order": {
            "post": {
                "tags": ["store"],
                "summary": "Place an order for a pet",
                "description": "",
                "operationId": "placeOrder",
                "produces": ["application/xml", "application/json"],
                "parameters": [{
                        "in": "body",
                        "name": "body",
                        "description": "order placed for purchasing the pet",
                        "required": true,
                        "schema": {
                            "$ref": "#/definitions/Order"
                        }
                    }],
                "responses": {
                    "200": {
                        "description": "successful operation",
                        "schema": {
                            "$ref": "#/definitions/Order"
                        }
                    },
                    "400": {
                        "description": "Invalid Order"
                    }
                }
            }
        },
        "/store/order/{orderId}": {
            "get": {
                "tags": ["store"],
                "summary": "Find purchase order by ID",
                "description": "For valid response try integer IDs with value >= 1 and <= 10. Other values will generated exceptions",
                "operationId": "getOrderById",
                "produces": ["application/xml", "application/json"],
                "parameters": [{
                        "name": "orderId",
                        "in": "path",
                        "description": "ID of pet that needs to be fetched",
                        "required": true,
                        "type": "integer",
                        "maximum": 10.0,
                        "minimum": 1.0,
                        "format": "int64"
                    }],
                "responses": {
                    "200": {
                        "description": "successful operation",
                        "schema": {
                            "$ref": "#/definitions/Order"
                        }
                    },
                    "400": {
                        "description": "Invalid ID supplied"
                    },
                    "404": {
                        "description": "Order not found"
                    }
                }
            },
            "delete": {
                "tags": ["store"],
                "summary": "Delete purchase order by ID",
                "description": "For valid response try integer IDs with positive integer value. Negative or non-integer values will generate API errors",
                "operationId": "deleteOrder",
                "produces": ["application/xml", "application/json"],
                "parameters": [{
                        "name": "orderId",
                        "in": "path",
                        "description": "ID of the order that needs to be deleted",
                        "required": true,
                        "type": "integer",
                        "minimum": 1.0,
                        "format": "int64"
                    }],
                "responses": {
                    "400": {
                        "description": "Invalid ID supplied"
                    },
                    "404": {
                        "description": "Order not found"
                   