      summary: Find purchase order by ID
      description: For valid response try integer IDs with value >= 1 and <= 10. Other
        values will generated exceptions
      operationId: getOrderById
      parameters:
      - name: orderId
        in: path
        description: ID of pet that needs to be fetched
        required: true
        schema:
          type: integer
          format: int64
          minimum: 1
          maximum: 10
      responses:
        '200':
          description: successful operation
          content:
            application/xml:
              schema:
                "$ref": "#/components/schemas/Order"
            application/json:
              schema:
                "$ref": "#/components/schemas/Order"
        '400':
          description: Invalid ID supplied
        '404':
          description: Order not found
    delete:
      tags:
      - store
      summary: Delete purchase order by ID
      description: For valid response try integer IDs with positive integer value.
        Negative or non-integer values will generate API errors
      operationId: deleteOrder
      parameters:
      - name: orderId
        in: path
        description: ID of the order that needs to be deleted
        required: true
        schema:
          type: integer
          format: int64
          minimum: 1
      responses:
        '400':
          description: Invalid ID supplied
        '404':
          description: Order not found
  "/user":
    post:
      tags:
      - user
      summary: Create user
      description: This can only be done by the logged in user.
      operationId: createUser
      responses:
        default:
          description: successful operation
      requestBody:
        content:
          application/json:
            schema:
              "$ref": "#/components/schemas/User"
        description: Created user object
        required: true
  "/user/createWithArray":
    post:
      tags:
      - user
      summary: Creates list of users with given input array
      description: ''
      operationId: createUsersWithArrayInput
      responses:
        default:
          description: successful operation
      requestBody:
        "$ref": "#/components/requestBodies/UserArray"
  "/user/createWithList":
    post:
      tags:
      - user
      summary: Creates list of users with given input array
      description: ''
      operationId: createUsersWithListInput
      responses:
        default:
          description: successful operation
      requestBody:
        "$ref": "#/components/requestBodies/UserArray"
  "/user/login":
    get:
      tags:
      - user
      summary: Logs user into the system
      description: ''
      operationId: loginUser
      parameters:
      - name: username
        in: query
        description: The user name for login
        required: true
        schema:
          type: string
      - name: password
        