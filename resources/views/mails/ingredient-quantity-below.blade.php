<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ingredient Quantity Below</title>
</head>
<body>
<p>Please note that, The Ingredient {{$ingredient->name}},
    currently quantity is {{$ingredient->current_quantity->toKilograms()}} kgs which belows the :  {{\App\Models\Ingredient::LOW_QUANTITY_PERCENTAGE}}.
    Please ensure to update the quantity.
    Thanks,
</p>
</body>
</html>
