# Altair plugin is  for CakePHP3
Auto converting special characters of variables to HTML entities

## Requirements ##

* PHP >=5.4.16
* CakePHP >= ~3.0

## uses

```
    class AppController extends Controller
    {

        public function initialize()
        {
            $this->loadComponent('Altair.Altair');
        }
```

By doing above,
You do not have to write the following(h()) every time.
```
<?= h($variable); ?>
```
