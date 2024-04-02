# Food delivery restaurant

Food delivery restaurant is a Symfony application that from a few input parameters (selected food, amount of money, number of drinks, is delivery) is capable to order food and return a response message.

## How to start.

1. Build the backend image running the `make build` command in the root folder.
2. Start the backend dockerized service running  the `make start` command in the root folder.
3. Enter in console to run your commands running  the `make backend/console` command in the root folder. 

## Commands.
- Retrieve all delivery orders running the`./bin/console app:order:list:delivery` command inside the backend console.

- Create a new order running the `./bin/console app:order:register` command inside the backend console.

Arguments:

|Name|Type|Required|Description| Values                         |Default|
|---|---|---|---|--------------------------------|---|
|selectedFood|string|true|Type of food| burger, sushi, pizza, [*]kebap, [*]nuggets |
|money|float|true|Amount of money given by the user||
|isDelivery|bool|true|Is delivered or user must get the food from the restaurant|||
|drinks|int|false|Number of drinks| 0, 1, 2                        |0|

> [*] selectedFood not valid for create menu order.

## Others.

1. Run test with `make tests/backend`  command in the root folder.