":{
               "400":{
                  "description":"Invalid ID supplied"
               },
               "404":{
                  "description":"Order not found"
               }
            }
         }
      },
      "/user":{
         "post":{
            "tags":[
               "user"
            ],
            "summary":"Create user",
            "description":"This can only be done by the logged in user.",
            "operationId":"createUser",
            "produces":[
               "application/xml",
               "application/json"
            ],
            "parameters":[
               {
                  "in":"body",
                  "name":"body",
                  "description":"Created user object",
                  "required":true,
                  "schema":{
                     "$ref":"#/definitions/User"
                  }
               }
            ],
            "responses":{
               "default":{
                  "description":"successful operation"
               }
            }
         }
      },
      "/user/createWithArray":{
         "post":{
        