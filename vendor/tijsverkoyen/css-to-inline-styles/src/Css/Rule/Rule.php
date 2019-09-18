# Dynamic References

*Dynamic References* import all attributes from the reference object to the specified object. 

It works similar to the `#ref` however allows for customization of properties and attributes.

The dynamic reference uses a `$` instead of `#` for the `ref` attribute.
```
    ref="$/responses/ExampleResponse"
```

### Examples


Define a default object which be our base structure.

In this case it is a response, which will contain a variable `data` property.
```php
<?php
/**
 * @SWG\Response(
 *      response="Json",
 *      description="the basic response",
 *      @SWG\Schema(
 *          @SWG\Property(
 *              type="boolean",
 *              property="success"
 *          ),
 *          @SWG\Property(
 *              property="data"
 *          ),
 *          @SWG\Property(
 *              property="errors",
 *              type="object"
 *          ),
 *          @SWG\Property(
 *              property="token",
 *              type="string"
 *          )
 *      )
 * )
 *
*/
```


Then you can extend the response in this example *POST* request by using the `$` ref.


```php
<?php
/**
 * @SWG\Post(
 *     path="/api/path",
 *     summary="Post to URL",
 *     @SWG\Parameter(
 *          name="body",
 *          in="body",
 *          required=true,
 *          @SWG\Schema(
 *              @SWG\Property(