[
    {
        "name": "id",
        "dbType": "increments",
        "htmlType": "",
        "validations": "",
        "searchable": false,
        "fillable": false,
        "primary": true,
        "inForm": false,
        "inIndex": false
    },
    {
        "name": "title",
        "dbType": "string",
        "htmlType": "text",
        "validations": "required",
        "searchable": true
    },
    {
        "name": "post_date",
        "dbType": "dateTime",
        "htmlType": "date",
        "searchable": true
    },
    {
        "name": "body",
        "dbType": "text",
        "htmlType": "textarea"
    },
    {
        "name": "password",
        "dbType": "string",
        "htmlType": "password",
        "searchable": false,
        "inForm": false,
        "inIndex": false
    },
    {
        "name": "token",
        "dbType": "string",
        "htmlType": "hidden",
        "searchable": false,
        "inForm": false,
        "inIndex": false
    },
    {
        "name": "email",
        "dbType": "string",
        "htmlType": "email",
        "searchable": true
    },
    {
        "name": "author_gender",
        "dbType": "integer",
        "htmlType": "radio,Male:1,Female:0"
    },
    {
        "name": "post_type",
        "dbType": "string",
        "htmlType": "radio,Public,Private",
        "searchable": true
    },
    {
        "name": "post_visits",
        "dbType": "integer",
        "htmlType": "number"
    },
    {
        "name": "category",
        "dbType": "string",
        "htmlType": "select,Technology,LifeStyle,Education,Games",
        "searchable": true
    },
    {
        "name": "category_short",
        "dbType": "string",
        "htmlType": "select,Technology:tech,LifeStyle:ls,Education:edu,Games:game"
    },
    {
        "name": "is_private",
        "dbType": "boolean",
        "htmlType": "checkbox,1"
    },
    {
       