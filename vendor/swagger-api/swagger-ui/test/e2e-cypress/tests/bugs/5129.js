:"",
            "operationId":"updatePetWithForm",
            "consumes":[
               "application/x-www-form-urlencoded"
            ],
            "produces":[
               "application/xml",
               "application/json"
            ],
            "parameters":[
               {
                  "name":"petId",
                  "in":"path",
                  "description":"ID of pet that needs to be updated",
                  "required":true,
                  "type":"integer",
                  "format":"int64"
               },
               {
                  "name":"name",
                  "in":"formData",
                  "description":"Updated name of the pet",
                  "required":false,
                  "type":"string"
               },
               {
                  "name":"status",
                  "in":"formData",
                  "description":"Updated status of the pet",
                  "required":false,
                  "type":"string"
               }
            ],
            "responses":{
               "405":{
                  "description":"Invalid input"
               }
            },
            "security":[
               {
                  "petstore_auth":[
                     "write:pets",
                     "read:pets"
                  ]
               }
            ]
         },
         "delete":{
            "tags":[
               "pet"
            ],
            "summary":"Deletes a pet",
            "description":"",
            "operationId":"deletePet",
            "produces":[
               "application/xml",
               "application/json"
            ],
            "parameters":[
               {
                  "name":"api_key",
                  "in":"header",
                  "required":false,
                  "type":"string"
               },
               {
                  "name":"petId",
                  "in":"path",
                  "description":"Pet id to delete",
                  "required":true,
                  "type":"integer",
                  "format":"int64"
               }
            ],
            "responses":{
               "400":{
                  "description":"Invalid ID supplied"
               },
               "404":{
                  "description":"Pet not found"
               }
            },
            "security":[
               {
                  "petstore_auth":[
                     "write:pets",
                     "read:pets"
                  ]
               }
            ]
         }
      },
      "/pet/{petId}/uploadImage":{
         "post":{
            "tags":[
               "pet"
            ],
            "summary":"uploads an image",
            "description":"",
            "operationId":"uploadFile",
            "consumes":[
               "multipart/form-data"
            ],
            "produces":[
               "application/json"
            ],
            "parameters":[
               {
                  "name":"petId",
                  "in":"path",
                  "description":"ID of pet to update",
                  "required":true,
                  "type":"integer",
                  "format":"int64"
               },
               {
                  "name":"additionalMetadata",
                  "in":"formData",
                  "description":"Additional data to pass to server",
                  "required":false,
                  "type":"string"
               },
               {
                  "name":"file",
                  "in":"formData",
                  "description":"file to upload",
                  "required":false,
                  "type":"file"
               }
            ],
            "responses":{
               "200":{
                  "description":"successful operation",
                  "schema":{
                     "$ref":"#/definitions/ApiResponse"