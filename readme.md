# Framework agnostic Pagination

This library is a framework agnostic way of paginating through data sets. The only dependency is psr/http-message.

It gets the information from the request and turns it into a data transfer object that is passed to a mapper that will map it to the data layer implementation.
 
**This is work on progress! Don't use it yet!**

## How to use it

```php
// Do this or use your favorite DI container to build the service
$service = new PaginationService(
    new PaginationParamsFactory(),
    new PaginationToCakeOrmMapper()
);

// Get the pagination data transfer object
$paginationParams = $service->getPaginationParams($request);

// Calls getPaginationParams() internally and passes it to the mapper
$resultSet = $service->paginate(
    $request,
    $myRepositoryObject
);
```

## Copyright & License

Licensed under the [MIT license](LICENSE.txt).

* Copyright (c) Florian Krämer
* Copyright (c) [Phauthentic](https://github.com/Phauthentic)
