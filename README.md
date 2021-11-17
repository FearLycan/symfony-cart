# README
## How to build app
1. run  `docker-compose up -d --build` in main catalog of app
2. run `docker exec -it php bash`
3. To prepare app run `make init`
4. Tests
    - The first time run `make tests` to build test database and run all tests
    - To run all tests `symfony php bin/phpunit`
    - To run single test `symfony php bin/phpunit tests/controller/ProductControllerTest.php`
    - To rebuild test database run again `make tests`

## How to use API
Example: http://localhost:8080/api/products
### Product

**Get Product list**


`GET /api/products`

`GET /api/products/page/1`

`GET /api/products/page/3`

example response
```json
[
    {
        "id": 1,
        "name": "The Godfather"
    },
    {   
        "id": 2,
        "name": "Steve Jobs"
    },
    {
        "id": 3,
        "name": "The Return of Sherlock Holmes"       
    }
]
```

---
**Create New Product**

`POST /api/products` with data
```json
{
    "name": "New product",
    "price": 33.33,
    "currency": "PLN"
}
```

example response
```json
{
    "id": 7,
    "name": "New product",
    "price": 33.33,
    "currency": "PLN",
    "createdAt": "createdAt"
}
```
---

**Update Product**

`PATCH /api/products/{id}` where `id` is product_id

with data
```json
{
    "name": "New product TEST",
    "price": 44.45,
    "currency": "PLN"
}
```
example response
```json
{
    "id": 7,
    "name": "New product TEST",
    "price": 44.45,
    "currency": "PLN",
    "createdAt": "createdAt"
}
```
---
**Delete Product**

`DELETE /api/products/{id}` where `id` is product_id

---
### Cart

---
**Create cart**

`POST /api/cart` with data
```json
{
    "product_id": 3
}
```
example response
```json
{
    "cart_id": "01FMPSMMGSTS88EW6N6FKDHJBK",
    "items": [
        {
            "id": 3,
            "name": "The Return of Sherlock Holmes",
            "price": 39.99,
            "currency": "PLN",
            "createdAt": "2021-11-16T22:06:59+00:00"
        }
    ],
    "summary": {
        "items": 1,
        "total_price": 39.99
    }
}
```
---

**Get cart**
`GET /api/cart/{id}` where `id` is `cart_id`
example response
```json
{
    "cart_id": "01FMQ5Y48FH5Q8NEE2SV6AMEYS",
    "items": [
        {
            "id": 2,
            "name": "Steve Jobs",
            "price": 49.95,
            "currency": "PLN",
            "createdAt": "2021-11-17T13:45:26+00:00"
        }
    ],
    "summary": {
        "items": 1,
        "total_price": 49.95
    }
}
```
---
**Add product to cart**

`POST /api/cart/{id}` where `id` is `cart_id`

with data
```json
{
    "product_id": 2
}
```
example response
```json
{
    "cart_id": "017d2e5f-110f-896e-8ab9-c2ceccaa3bd9",
    "items": [
        {
            "id": 2,
            "name": "Steve Jobs",
            "price": 49.95,
            "currency": "PLN",
            "createdAt": "2021-11-17T13:45:26+00:00"
        },
        {
            "id": 3,
            "name": "The Return of Sherlock Holmes",
            "price": 39.99,
            "currency": "PLN",
            "createdAt": "2021-11-17T13:45:26+00:00"
        }
    ],
    "summary": {
        "items": 2,
        "total_price": 89.94
    }
}
```
---
**Remove product from cart**

`DELETE /api/cart/{id}` where `id` is `cart_id`

with data
```json
{
    "product_id": 3
}
```