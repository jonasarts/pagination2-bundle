CHANGE LOG
==========

V 4.0.8
-------

- Updated TreeBuilder to support Symfony 5.0

V 4.0.2
-------

- Replacing paginationData (array) with PaginationData (class)
- Removed PaginationHelper class

V 4.0.0
-------

- Release for Symfony 4.x

V 2.0.0
-------

- Release for Symfony 3.x

V 1.3.0
-------

Attention! Breaking Changes (BC) below!

- Changed getPageIndex()  
  with $key default to 'pageindex'
- Changed resetPageIndex()  
  with $key default to 'pageindex'
- BC: Changed getPageRange()  
  added $key = 'pagerange'  
  now using auto_register
- BC: Changed getPageSize()  
  added $key = 'pagesize'  
  now using auto_register
- Added getPageSizeSelector()  
  Method to output the page-size-selector html

V 1.2.0
-------

- Added resetPageIndex() method

V 1.1.0
-------

- Added configuration
- Reorganization

V 1.0.0
-------

- Initial
