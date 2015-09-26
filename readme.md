## ThunderWorld

Store world location

## Restful API

#### List

```php
  GET /?page=0&per_page=10&level=country
  
  description:
    page      : current page
    per_page  : count of result returned
    level     : level of location (continent, country, province, city, suburb)
 
  return: jsend
```

#### Show

```php
  GET /show/id
  
  description:
    id        : id of location

  return: jsend
```

#### Store
```php
  POST /{id}
  
  description:
    id        : id to edit (optional)
    name      : location name
    level     : level of location
    longitude : double longitude position
    latitude  : double latitude position
    
  return: jsend
```

#### Delete
```php
  Get /delete/id
  
  description:
    id        : id of location to be deleted
  
  return: jsend
```



