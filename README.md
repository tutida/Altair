# Altair plugin is  for CakePHP3
Auto converting special characters of variables to HTML entities

## Requirements ##

* PHP >=5.4.16
* CakePHP >= ~3.0

## Usage

```php
<?php
    class AppController extends Controller
    {

        public function initialize()
        {
            $this->loadComponent('Altair.Altair');
        }
        ...
    }
```

By doing above,
You do not have to write the following(h()) every time.
```
<?= h($variable); ?>
```

If you do not want to escape `$object`, use `$object->escape` property.

```php
<?php
    class UsersController extends AppController
    {

        public function add()
        {
            $user = $this->Users->newEntity();
            ...
            $user->escape = false;
            $this->set('user', $user);
        }
        ...
    }
```

If you do not want to escape in the action, use `$this->Altair->escape()` method.

```php
<?php
    class UsersController extends AppController
    {

        public function add()
        {
            $user = $this->Users->newEntity();
            ...
            // Not escape $viewVars in this action.
            $this->Altair->escape(false);
            $this->set('user', $user);
        }
        ...
    }
```


