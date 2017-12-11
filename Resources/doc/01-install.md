Setting up the bundle
=====================

## Install the bundle

Execute this console command in your project:

``` bash
$ composer require jonasarts/pagination2-bundle
```

## Enable the bundle

Register the bundle in the kernel:

```php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    // ...
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new jonasarts\Bundle\PaginationBundle\PaginationBundle(),
        );

    // ...
    }
}
```

## Configuration options

[Read the bundle configuration options](02-configuration.md)

## Example Twig

[There are some example twig layout files](twig_examples)

## That's it

Check out the docs for information on how to use the bundle! [Return to the index.](index.md)
