# MAGO API PHP by Mercadoni

Simple class to query the Mago API v 0.1 with PHP. This API is to be used by partners registered with an active *Mago Account*. PHP Curl is required!

## 1. Basic Setup

* API tokens are available for Admin users in the My Account section at the Mago Dashboard. There are both sandbox & production tokens.


## 2. Installation

* Include the Mago API Class in your project.
* Set the API token.
* Set the enviroment. Both **sandbox** and **production** enviroments are available.

**sandbox** is a testing environment. It is recommended to set up the API in sandbox enviroment first to check for any custom code side effects and app capability to meet business needs. Switch to **production** enviroment once ready.


```php
include('Mago.php');

$mago = new Mago(); 
$mago->setToken('8zBqL7TI2Um30jn59oW4ihF3','sandbox'); 
```

## 3. Query the MAGO API

### 3.1 Get My Information

**POST**    partner.mercadoni.com/v1.0-api/me 

If token and enviroment were properly set up, access your information by doing so.

```php
$myInfo = $mago->getMe();
```

$myInfo will print:

```json
{
  	"data": {
  		"_id" : "560c852e5b7c963b001fd9db",
	  	"name": "Best Online Store",
		"from": {
			"country": {
				"code": "MEX",
				"name": "México"
			}
		}
	}
}

```

### 3.2 Create Pickup Locations

**POST**    partner.mercadoni.com/v1.0-api/post_locations

In order to create new orders with Mago, pickup locations need to be created first. It is recommended that you set up all your pickup locations first, as they need to be approved befored they can be used. Later on, when creating a new order (see 3.5), the pickup location unique identifier *_id* field is required along other fields.

```php

$location = array(
   	'name' => 'Main WareHouse',
	'address' => 'Monterrey 28, Roma Norte, Cd. de México', 
	'city_code' => 'MEX', // 'MEX' for México City and 'BOG' for Bogotá are available.
	'coordinates' => [-99.169775, 19.378938], // [long, lat]
	'external_id' => '234123'
);

$newLocation = $mago->createLocation($location);

```

$newLocation will print:

```json
{
  	"data": {
  		"_id" : "570ec2531d0452d8434d18b9",
	  	"address": "Monterrey 28, Roma Norte, Cd. de México",
		"name": "Main Warehouse",
		"from": {
			"city": {
				"code": "MEX",
				"name": "Cd. de México"
			}
		},
		"active": true,
		"loc": {
			"coordinates": [-99.169775, 19.378938]
		},
		"external": {
        	"id": "234123"
      	}
	}
}

```

### 3.3 List All Pickup Locations

**POST**    partner.mercadoni.com/v1.0-api/list_locations

All the pickup locations available can be listed like this:


```php
$myLocations = $mago->getLocations();
```

$myLocations will print:

```json
{
  	"data": [{

  		"_id" : "570ec2531d0452d8434d18b9",
	  	"address": "Monterrey 28, Roma Norte, Cd. de México",
		"name": "Main Warehouse",
		"from": {
			"city": {
				"code": "MEX",
				"name": "Cd. de México"
			}
		},
		"active": true,
		"loc": {
			"coordinates": [-99.169775, 19.378938]
		},
		"external": {
        	"id": "234123"
      	}
	},{

		"_id" : "570ec2531d0452d6732x56c7",
	  	"address": "San Luis Potosí 149, Roma Norte, Cd. de México",
		"name": "Second Warehouse",
		"from": {
			"city": {
				"code": "MEX",
				"name": "Cd. de México"
			}
		},
		"active": true,
		"loc": {
			"coordinates": [-99.169651, 19.378765]
		},
		"external": {
        	"id": "234126"
      	}
	}]
}

```

### 3.4 Update Information of a Pickup Location

**POST**    partner.mercadoni.com/v1.0-api/put_locations

An already created pickup location can be edited. To do so, specify the location to edit with the '_id' field along with the fields to be edited. The fields that are available for editing are name, address, city_code and coordinates.

For example, the following code will edit the field coordinates of location with _id '570ec2531d0452d8434d18b9'.

Location's _id can be fetch by listing all locations (see 3.3).

```php
$patch = array(
	'coordinates' => [-99.169775, 19.378676], // [long, lat]
	'_id' => '570ec2531d0452d8434d18b9'
);

$editedLocation = $mago->editLocation($patch);
```

$editedLocation will print:

```json
{
  	"data": {
  		"_id" : "570ec2531d0452d8434d18b9",
	  	"address": "Monterrey 28, Roma Norte, Cd. de México",
		"name": "Main Warehouse",
		"from": {
			"city": {
				"code": "MEX",
				"name": "Cd. de México"
			}
		},
		"active": true,
		"loc": {
			"coordinates": [-99.169775, 19.378676]
		},
		"external": {
        	"id": "234123"
      	}
	}
}

```


### 3.5 Create a new order

**POST**    partner.mercadoni.com/v1.0-api/post_orders

To createa a new order the following information is needed:

 - Custmer's Address Information:
	* **delivery_address:** customer's delivery address.
	* **delivery_address_comments:** customer's delivery address extra comments.
	* **delivery_coordinates:** customer's delivery address coordinates.
	* **city_code:** customer's delivery address city_code. 'MEX' for México City and 'BOG' for Bogotá are available.
 - Delivery times:
	* **delivery_time_from:** UTC time of earliest availability at pickup location.
	* **delivery_time_to:** UTC time of latest arrival to customer.
 - Pickup information:
 	* **pickup_location:** Pickup location's unique identifier (_id). See 3.3 for more information. If not sent, the system will choose closest location to customer.
	* **pickup_vehicle:** Vehicle needed for the delivery. 'car', 'motorbike' and 'van' are availabe.
	* **pickup_comments:** These comments will pop up to the delivery person at pickup time.
 - Customer Information:
 	* **customer_name:** Customer's full name.
	* **customer_phone_number:** Customer's  contact number full name.
 - Payment information:
 	* **payment_charge:** Amount to charge to customer on arrival. By default is set to 0 *which means no charge*. This field is optional.
	* **payment_method:** With which payment method the customer will pay. Both 'cash' and 'dataphone' available. By default is set to cash. This field is optional.
 - Extra information:
    * **internal_order_number:** Internal unique identifier for the new order.
 - Packages 
	* **packages:**: an array of pickup packages
		* qty: qty of packages.
		* name: package's name.
		* sku: package's unique identifier.
		* volume: package's volumetric volume (squared meters).

```php
$order = array(

	'delivery_address'=> 'Calle Tonala 10',
	'delivery_address_comments'=> 'Entre Puebla y Durango',
	'delivery_coordinates'=> [-99.169775, 19.378938], // [long, lat]
	'city_code'=> 'MEX', // 'MEX' for México City and 'BOG' for Bogotá are available.

	'delivery_time_from'=> '2016-04-16T20:00:00.000Z',
	'delivery_time_to'=> '2016-04-18T00:11:00.000Z',

	'pickup_location'=> '570ec2531d0452d8434d18b9',
	'pickup_vehicle'=> 'car',
	'pickup_comments'=> 'El numero secreto es 12345',
	'owner'=> 'mercadoni',
	
	'customer_name'=> 'Alvaro Burgos',
	'customer_phone_number'=> '675135998',

	'payment_charge'=> 399, //optional. 0 means no charge
	'payment_method'=> 'cash', //optional. 'cash' be default.

	'internal_order_number'=> '#12345',
	'packages'=> array(
		0 => array( 
			'qty': 1, 
			'name': 'Coca-Cola 33Cl',
			'sku' : '23WVC233GHCCX',
			'volume' : 0.0033
		),
		1 => array( 
			'qty': 5,
			'name': 'Coca-Cola 2L',
			'sku' : '23QQQ233GHCCX',
			'volume' : 0.0159
		)
	)
);

$newOrder = $mago->createOrder($order);
```
If the creation of a new order is succesful, the server's answer will look like this:
```json
{
  "success": true,
  "error": null,
  "order": "571188a988b4ab1c26099ba5",
  "order_number": "MA3413434242"
}
```


### 3.6 Check Order Status & Information

**POST**    partner.mercadoni.com/v1.0-api/get_order
**POST**    partner.mercadoni.com/v1.0-api/get_order_by_internal

Once a new order created, check the order status & extra information by using Mago's order id.

```php
$orderInfo = $mago->getOrderInfo('571188a988b4ab1c26099ba5');
```

It is also posible to check the order status & extra information by using the internal_order_number expecified when creating a new order.

```php
$orderInfo = $mago->getOrderInfoByInternal('#12345');
```
The API will respond with the following information:

* **order:** Mago's order id.
* **order_number:** Mago's order number.
* **pickup_location:** Pickup location id.
* **internal_order_number:** Internal order unique identifier specified at order creation.
* **internal_order_number:** Internal order unique identifier specified at order creation.
* **status:** Order's current status.
* **status_time:** Order's historical status changes with timestamps.


```json
{
  "data": {
    "order": "57151a0109c4af85264a4c3f",
    "order_number": "MA3413434242",
    "pick_up_location": "561de61856bd7c4000f1572a",
    "internal_order_number": "#0123456789",
    "scheduled_for_delivery_at": "2016-04-18T17:30.000Z",
    "status": "delivered",
    "status_time": [
      {
        "created": "2016-04-18T17:31:53.662Z",
        "status": "processing"
      },
      {
        "created": "2016-04-18T17:32:51.507Z",
        "status": "assigned"
      },
      {
        "created": "2016-04-18T17:32:57.570Z",
        "status": "shopping"
      },
      {
        "created": "2016-04-18T17:33:13.519Z",
        "status": "out_for_delivery"
      },
      {
        "created": "2016-04-18T17:33:19.099Z",
        "status": "delivered"
      }
    ],
    "rating": {
    	value: 5,
    	comment: "Muy buen servicio"
	}
  }
}
```

Status available are:

* on_cart: Order couldn't be process due to errors at creation. Will not be fulfilled.
* processing: Order is ready for fulfilment.
* preassigned: Order is assigned to a delivery person, but not yet fulfilling.
* assigned: Order is assigned to a delivery person and fulfilling.
* shopping: Order is currently being picked at pickup location.
* shopping_done: Order was picked from pickup location but not yet being delivered.
* out_for_delivery: Order is being delivered.
* delivered: Order was succesfully delivered.
* canceled: Order was canceled.



### 3.7 Rate Order

**POST**    partner.mercadoni.com/v1.0-api/rate_order

To rate an existing order use the rateOrder method using the _id given at order creation.

```php
$rating = array(
	'_id' => '57151a0109c4af85264a4c3f',
	'value' => 5,
	'comment' => 'Muy buen servicio'
);

$ratedOrder = $mago->rateOrder($rating);
```

ratedOrder will look like:

```json
{
  "data": {
    "order": "57151a0109c4af85264a4c3f",
    "order_number": "SS61100000",
    "internal_order_number": "#0123456789",
    "rating": {
    	"value": 5,
    	"comment": "Muy buen servicio"
	}
  }
}
```

### 3.8 Cancel Order

**POST**    partner.mercadoni.com/v1.0-api/cancel_order

To rate an existing order use the rateOrder method using the _id given at order creation.

```php
$order = array(
	'_id' => '57151a0109c4af85264a4c3f',
	'reason' => 'El cliente quiere cancelar'
);

$canceledOrder = $mago->cancelOrder($order);

```

canceledOrder will look like:

```json
{
  "success": true,
  "error": null
}
```


