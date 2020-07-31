<p align="center">
    <img width="256" src="hiker.png">
</p>

<p align="center"><b>Create breadcrumbs and menus for your Laravel projects.</b></p>


# 🗻 Maestriam/Hiker

**Maestriam/Hiker** is a package for creating menus and breadcrumbs using [Laravel Routes](https://laravel.com/docs/6.x/routing).  
**Under construction!**

## Requirements

- Laravel 6.*^ 

## Installation

**Install via composer**
``` php
composer require maestriam/hiker
```

## Getting Started

**Preparing routes**  

Let's start creating some routes normally using Laravel Routes.  
You must declaring `as` and `uses` params. 

``` php
    Route::get('/test/edit/{id}', [
        'as'    => 'test.edit',
        'uses'  => 'TestController@edit'
    ]);
```

**Optional attributes**

Optionally, you can add others params into yout route
``` php
    Route::get('/test', [
        'as'    => 'test.index',
        'uses'  => 'TestController@index'
        'icon'  => 'flag',
        'label' => 'My Route Index',
        'desc'  => 'A common index route' 
    ]);
```

## Menu

**Create a new menu**
``` php
    $menu = Hiker::menu('test-menu');
```

Now, let's add some routes in it
``` php
    $menu = Hiker::menu('test-menu')
                 ->push('test.index')
                 ->push('test.view')
                 ->push('test.edit);
```

Into your php class, we can dump our menu this way
``` php
    $menu = Hiker::menu('test-menu');

    foreach($menu->collection as $route) {
        dump($route->url);
    }
```

The above example will output (if is localhost with `php artisan serve`):
``` php
    http://localhost:8000/test
    http://localhost:8000/view/1
    http://localhost:8000/edit/1
```

We can render into blade file
``` php
    @foreach($menu->collection as $route)
        {{ $route->url }}
    @endforeach
```

## Breadcrumb

**Creating breadcrumb**

Create a new middleware and put into your function.

``` php
    Hiker::breadcrumb('my-breadcrumb')
```

**Creating namesake breadcrumb**

You can create namesake breadcrumbs. Just name it breadcrumb with same name that you route.  
Automatically, the last item of breadcrumb will be the route that your name given

``` php
    Hiker::breadcrumb('my-route')
```

**Adding routes**

To add routes into breadcrumb, just call function `push` passing route name as parameter;  
You can pass others parameters for construction of route

``` php
    Hiker::breadcrumb('my-breadcrumb')
         ->push('test.index')
         ->push('test.index', ['id' => 1]);
```


**Get routes**  

To get added routes, just access `collection` attribute:
``` php
    $breadcrumb = Hiker::breadcrumb('my-breadcrumb');

    $breadcrumb->collection;
```

<br></br>  
Created by [Giuliano Sampaio](https://github.com/giusampaio) with ❤️ and 🍺!
