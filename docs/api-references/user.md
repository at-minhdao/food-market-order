## User API

### `GET` User
```
/api/users/me
```
Get information about a current user login.

#### Request header
| Key | Value |
|---|---|
| Accept | application/json |
| Authorization | {token_type} {access_token} |

#### Sample Response
```json
{
  "data": {
    "id": 74,
    "full_name": "dungvan2512",
    "email": "dungvan@test.email.com",
    "birthday": null,
    "gender": 0,
    "address": null,
    "phone_number": null,
    "image": "http://foodmarket.com/images/users/default.jpg",
    "is_admin": 1,
    "is_active": 0,
    "created_at": "2017-09-01 02:22:32",
    "updated_at": "2017-09-01 02:22:32",
    "deleted_at": null
  },
  "success": true
}
```

### `Post` user
```
/api/users
```
Registry a user

#### Parameters
| Key | Type | Required | Description |
|---|---|---|---|
| full_name | String | required | Name of user |
| email | String | required | email to login |
| password | String | required | password |
| password_confirmation | String | required | password confirmation |

#### Request header
| Key | Value |
|---|---|
| Accept | application/json |
|Content-Type| application/json |

#### Sample Request body
```json
{
  "full_name": "Van Duc Dung",
  "email": "vandung@test.email.com",
  "password": "123456",
  "password_confirmation": "123456"
}
```

#### Sample Response
```json
{
  "data": {
    "id": 74,
    "full_name": "dungvan2512",
    "email": "dungvan@test.email.com",
    "birthday": null,
    "gender": 0,
    "address": null,
    "phone_number": null,
    "image": "http://foodmarket.com/images/users/default.jpg",
    "is_admin": 1,
    "is_active": 0,
    "created_at": "2017-09-01 02:22:32",
    "updated_at": "2017-09-01 02:22:32",
    "deleted_at": null
  },
  "success": true
}
```
### `Put` user
```
/api/users/me
```
Update profile of current user

#### Parameters
| Key | Type | Required | Description |
|---|---|---|---|
| full_name | String | required | Name of user |
| birthday | String | required | email to login |
| gender | Integer | required | Default 0 : female, 1 : male  |
| address | String | required | address of user |
| phone_number | String | required | phone number of user |
| password | String | nullable | password |
| password_confirmation | String | nullable | password confirmation |

#### Request header

| Key | Value |
|---|---|
| Accept | application/json |
|Content-Type| application/json |
| Authorization | {token_type} {access_token} |

#### Sample Request body
```json
{
  "full_name":"dungvan25123",
  "birthday":"1994-03-12",
  "gender":1,
  "address":"DienDuong",
  "phone_number":"123456789"	
}
```

#### Sample Response
```json
{
    "success": true
}
```