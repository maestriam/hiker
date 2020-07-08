<p align="center">
    <img width="256" src="http://www.maestriam.com.br/assets/imgs/hiker.png">
</p>

<p align="center"><b>Create breadcrumbs and menus for your Laravel projects.</b></p>


# 🗻 Maestriam/Hiker

**Maestriam/Hiker** is a package for creating menus and breadcrumbs using [Laravel Routes](https://laravel.com/docs/6.x/routing).  
**Under construction!**

## Requirements

- Laravel 6.*^ 

## Installation

**Install via composer**
``` bash
composer require maestriam/hiker
```

## Getting Started

Create a new menu
``` bash
$menu = Hiker::menu('foo-menu');
```

Now, let's add some routes in it
``` bash
$menu = Hiker::menu('foo-menu')
             ->push('blog.index')
             ->push('blog.view', ['id' => 1]);
```

Into your php class, we can dump our menu this way
``` bash
$menu = Hiker::menu('foo-menu');

foreach($menu->collection as $route) {
    dump($route->url);
}
```

We can render into blade file
``` bash
@foreach($menu->collection as $route)
    {{ $route->url }}
@endforeach
```

<br></br>  
Created by [Giuliano Sampaio](https://github.com/giusampaio) with ❤️ and 🍺!
