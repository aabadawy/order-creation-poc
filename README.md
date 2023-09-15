## Order Create Task

### Getting started
- requirements
  - PHP 8.1
  - Mysql 8
- run the next commands
  - ``cp .env.example .env`` 
  - ``composer install``
  - set your db configuration in the .env
  - ``php artisan migrate --seed``
  - ``php artisan serve``
  - open [API doc](https://documenter.getpostman.com/view/26549647/2s9YC5xsMm)
---

### todos 
- [ ] install laravel sail, and configure docker compose file
- [ ] document project running using laravel sail
- [x] add postman collection link
---
### Issue
#### 1.  Order Creation
 - Then main Challenge to ensure Order had been created, <br/> with order products and ingredients attached to it.
 - The other Challenge is to ensure every single ingredient quantity **mass** is up-to-date. <br/> for example two orders created on the same time and use the same ingredient, **tomato for example**. <br/> it should ensure the quantity of tomato in the **second order** creating order equals the latest quantity after subtracted from **first order** .
 - Another thing is to handle large scale of data to **save the memory**,fo example <br/> it maybe a single product use **(n) maybe 100 ingredient** which not that huge number but maybe more, which will lead to memory issue.
#### 2. Notify Marcher
- First challenge to ensure only one mail sent ber ingredient when **quantity below the 50%**.
- Secondly to load the needed ingredients without face memory issue
---
### Solution
#### 1.  Order Creation
-  Ensure the **order creating process** is **wrapped** with **DB transaction**. <br/> so if any exception or error thrown _like ingredient quantity in negative_ then all the transaction will be rollback.
-  attach **products ingredients** to **Created Order** with fetching the product ingredients in **lazy Collection** with **100 ber chunk**, so avoid **memory issue** when interacting with **large data**.
-  load each single ingredient one by one, to ensure the current quantity is the latest exists quantity in the DB, to increase the **reality**
#### 2. Notify Marcher
- Register Worker which works every **5 minutes** to fetch all ingredients quantities below the **50%**, then start to-
  - send mail to marcher
  - update ingredient `quantity_below_email_sent_at` to mark the ingredient email as sent -as simple as that-.
- Fetching the ingredients also in **lazy Collection** also to avoid **memory issue**.
- Always Trigger  the **ingredients** used after **create new order** (with dispatching [Order Created Event](app/Events/Order/OrderCreatedEvent.php)), to increase the **reality**.

## Project Structure.
In General, I prefer to use Domain Driven Design and specially with the recommended structure by **Spatie** [Laravel Beyond CRUD](https://spatie.be/products/laravel-beyond-crud).<br/>
**Here** I'm using **Laravel MVC** but **take in consideration** the importance of **using:** 
- Command (action) Design Pattern, to increase Readability and ensure single business logic for single command
- DTO,for encapsulating and centralizing data handling, enhancing data validation, security, and flexibility. 
- Value Object, to ensure the **quantity (mass)** always valid after creating the value object.

## Creating Order Life Cycle
- Visit the `/api/orders` which bind with [CreateOrderController](app/Http/Controllers/Order/CreateOrderController.php).
- then the **Controller** will call [CreateOrderCommand](app/Commands/Order/CreateOrderCommand.php) which responsable for creating the order with its own data, _in details:_.
  - create order instance.
  - attach used products with its quantity to the created order.
  - attach used products' ingredients to the created quantity.
  - update (subtract) each ingredient quantity with its used quantity in this created order.
- **Dispatch** [OrderCreatedEvent](app/Events/Order/OrderCreatedEvent.php) which allow any registered listener to handle its on task, and here also, I do register [IngredientLevelNotifier](app/Listeners/Order/IngredientLevelNotifier.php) which responsible for notify the marcher with ingredients' quantity below **50%**.

## Notify Marcher Life Cycle
- Here I notify the Marcher:
  - every **5 minutes** using the **laravel Schedule** (it could be more or less than 5 minutes for sure.)
  - as mentioned before, after creating every single order, [IngredientLevelNotifier](app/Listeners/Order/IngredientLevelNotifier.php) is registered as **Queue Job** **only if the  order ingredients below 50% exists**, which also sent mails ber each ingredient (only the order's ingredients) to the marcher.

## Database ERD
#### tables:
- users (PK: id)
  Attributes: name, email, address, created_at, updated_at

- orders (PK: id, FK: client_id)
  Attributes: created_at, updated_at

- products (PK: id)
  Attributes: name

- ingredients (PK: id)
  Attributes: name, init_quantity, current_quantity, quantity_below_email_sent_at _(timestamp)_, created_at, updated_at

#### Relationships:
- users (1) <-----> (0..N) orders
- orders (1) <-----> (1..N) products
- products (1) <-----> (0..N) ingredients
- orders (1) <-----> (1..N) ingredients

#### Pivot Tables:
- order_products
  Attributes: id, order_id (FK), product_id (FK), quantity

- product_ingredients
  Attributes: product_id (FK), ingredient_id (FK), quantity

- order_product_ingredients
  Attributes: id, order_id (FK), product_id (FK), ingredient_id (FK)

<img src="order creation erd.png" alt="J" width="1000" height="300"/>

