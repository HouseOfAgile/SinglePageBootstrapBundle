Single Page Bootstrap Bundle
############################

Installation
------------

## Install the bundle

Simply run assuming you have installed composer.phar or composer binary:

``` bash
$ php composer.phar require houseofagile/HOASinglePageBootstrapBundle 0.8.*
```

## Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new HOA\Bundle\SinglePageBundle\HOASinglePageBundle()
        
        // if you choose to use HOANotificationBundle for contact notification, make sure that it is registered in your application
        // new HOA\Bundle\NotificationBundle\HOANotificationBundle(),

    );
}
```

Branches
--------

If you wish to use the current master branch, then use the following:


``` json
{
    "require": {
        "houseofagile/single-page-boostrap-bundle": "dev-master"
    }
}
```

if you want to use the current branch

```json
{
    "require": {
        "houseofagile/single-page-boostrap-bundle": "0.8.*@dev"
    }
}
```

Documentation
-------------

Additional Resources:

*  [SinglePageBootstrapBundle](https://github.com/HouseOfAgile/BootstrapSinglePage) - Simple integration for that bundle based on latest symfony standard.

Warning
-------

We tried to make that bundle as generic as possible, but of course there is lots of dependencies, the use of that bundle should be made integrated through our single-page-bootstrap-bundle starter project.

Contribute
----------
If you want to contribute your code to SinglePageBootstrapBundle please be sure that your PR's
are valid to Symfony2.1 Coding Standards. You can automatically fix your code for that
with [PHP-CS-Fixer](http://cs.sensiolabs.org) tool.

You can see who already contributed to this project on [Contributors](https://github.com/HouseOfAgile/HOASinglePageBootstrapBundle/contributors) page

License
-------

This bundle is under the Apache license. For more information, see the complete [LICENCE](LICENCE) file in the bundle.
