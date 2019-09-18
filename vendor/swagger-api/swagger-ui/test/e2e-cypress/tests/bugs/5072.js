rameters":[
               {
                  "name":"status",
                  "in":"query",
                  "description":"Status values that need to be considered for filter",
                  "required":true,
                  "type":"array",
                  "items":{
                     "type":"string",
                     "enum":[
                        "available",
                        "pending",
                        "sold"
                     ],
                     "default":"available"
                  },
                  "collectionFormat":"multi"
               }
            ],
            "responses":{
               "200":{
                  "description":"successful operation",
                  "schema":{
                     "type":"array"