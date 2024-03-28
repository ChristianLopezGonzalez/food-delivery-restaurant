# Food delivery restaurant

Food delivery restaurant is a Symfony application that from a few input parameters (selected food, amount of money, number of drinks, is delivery) is capable to order food and return a response message.

## How it works


Arguments

|Name|Type|Required|Description|Values|Default|
|---|---|---|---|---|---|
|selectedFood|string|true|Type of food|burger, sushi, pizza|
|money|float|true|Amount of money given by the user||
|isDelivery|bool|true|Is delivered or user must get the food from the restaurant|||
|drinks|int|false|Number of drinks|0, 1, 2|0|


Prices

|Drink|Price|
|---|---|
|Pizza|12.5|
|Burger|9|
|Sushi|24|
|Drink|2|
|Delivery cost|1.5|

Validations
* If the selected food is not *burger*, *pizza* or *sushi*, it shows the following message:
```
Selected food must be pizza, burger or sushi.
```
* If is not delivery and money does not reach order total amount, it shows the following message:
```
Money does not reach the order amount.
```
* If the number of drinks is not between 0 and 2, it shows the following message:
```
Number of drinks should be between 0 and 2.
```
* If delivery is true and money is not equal to the order total amount, it shows the following message:
```
Money must be the exact order amount on delivery orders.
```
* If the order with drinks has been registered with success, it shows the following message:
```
Your order with drinks included has been registered.
```
* If the order without drinks has been registered with success, it shows the following message:
```
Your order has been registered.
```

## What you have to do?
First of all, the application must be dockerized.

Right now all the application logic is in `OrderCommand` and we would like to have a reusable, maintainable and testable code, so we want to refactor
this project following these principles:

* Clean code
* SOLID principles
* Decoupling
* Design patterns
* Input validation
* Error handling
* Testing
* DDD
* Hexagonal architecture

We also need another command to obtain all the registered orders with delivery. There is no need to save them in
a DB, you could store them in a file.