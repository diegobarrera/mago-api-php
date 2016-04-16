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

If token and enviroment were properly set up, access your information by doing so.

```php
$myInfo = $mago->getMe();
```

### 3.2 Create Pickup Locations

In order to create new orders with Mago, pickup locations need to be created first. It is recommended that you set up all your pickup locations first, as they need to be approved befored they can be used. Later on, when creating a new order (see 3.5), the pickup location unique identifier *_id* field is required along other fields.

```php

$location = array(
   	'name' => 'Main WareHouse',
	'address' => 'Monterrey 28, Roma Norte, Cd. de México', 
	'city_code' => 'MEX', // 'MEX' for México City and 'BOG' for Bogotá are available.
	'coordinates' => [-99.169775, 19.378938] // [long, lat]
);

$newLocation = $mago->createLocation($location);

```

### 3.3 List All Pickup Locations

All the pickup locations available can be listed like this:


```php

$myLocations = $mago->getLocations();


```

### 3.4 Update Information of a Pickup Location

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


### 3.5 Create a new order

To createa a new order the following information is needed:

1. Custmer's Address Information:
	* **delivery_address:** customer's delivery address.
	* **delivery_coordinates:** customer's delivery address coordinates.
	* **delivery_address:** customer's delivery address.
2. Delivery times:
	* **delivery_time_from:** UTC time of earliest availability at pickup location.
	* **delivery_time_to:** UTC time of latest arrival to customer.
3. Pickup information:
 	* **pickup_location:** Pickup location's unique identifier (_id). See 3.3 for more information.
	* **pickup_vehicle:** Vehicle needed for the delivery. 'car', 'motorbike' and 'van' are availabe.
4. Payment information:
 	* **payment_charge:** Amount to charge to customer on arrival. By default is set to 0 *which means no charge*. This field is optional.
	* **payment_method:** With which payment method the customer will pay. Both 'cash' are 'dataphone' available. By default is set to cash. This field is optional.
5. Extra information:
	
    * **internal_order_number:** Internal unique identifier for the new order.

```php
$order = array(

	'delivery_address'=> 'Calle Tonala 10',
	'delivery_coordinates'=> [-99.169775, 19.378938], // [long, lat]
	'city_code'=> 'MEX', // 'MEX' for México City and 'BOG' for Bogotá are available.

	'delivery_time_from'=> '2016-04-16T20:00:00.000Z',
	'delivery_time_to'=> '2016-04-18T00:11:00.000Z',

	'pickup_location'=> '570ec2531d0452d8434d18b9',
	'pickup_vehicle'=> 'car',

	'customer_name'=> 'Alvaro Burgos',
	'customer_phone_number'=> '675135998',

	'payment_charge'=> 399, //optional. 0 means no charge
	'payment_method'=> 'cash', //optional. 'cash' be default.

	'internal_order_number'=> '#12345'
	
);

$newOrder = $mago->createOrder($order);
```
If the creation of a new order is succesful, the server's answer will look like this:
```json
{
  "success": true,
  "error": null,
  "order": "571188a988b4ab1c26099ba5"
}
```


### 3.6 Check Order Status & Information

Once a new order created, check the order status & extra information by using Mago's order id.

```php
$orderInfo = $mago->getOrderInfo('571188a988b4ab1c26099ba5');
```

It is also posible to check the order status & extra information by using the internal_order_number expecified when creating a new order.

```php
$orderInfo = $mago->getOrderInfoByInternal('#12345');
```
git remote add origin https://github.com/mercadoni/mago-api-php.git
