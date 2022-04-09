Using the bundle
================

The Pagination (aka PaginationManager) is a service to handle all pagination related operations.
This includes some helper methods for sorting and loading/saving a cursor.

The method ``Pagination.getPagination()`` will return a pagination object with following properties/methods:
- pageCount
- pagesInRange
- totalCount
- first
- previous
- current
- next
- last

To render a pagination, just output the pagination object returned by the Pagination service.

```php
    $pm = $this->get('pagination');


```

To register a different pagination twig template:

```php
    $pm = $this->get('pagination');
    $pm->setTemplate('my/custom/path/to/paginationTwigTemplate.html.twig');

    // ...
```

This is how a controller action using a pagination manager may look like:

```php
    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        $pm = $this->get('pagination'); // the pagination service
        $request = $this->getRequest(); // the current request


    }
```

Using the PageSizeSelector
==========================

To generate a pagesizeselector, just call the method on the Pagination service:

```php
    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        $pm = $this->get('pagination');
        $request = $this->getRequest();


    }
```



[Return to the index.](index.md)
