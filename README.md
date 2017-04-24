# backend

clone this repository from github and run composer install from inside the root directory.

The main entry point of the application is the ``` index.php ``` file.

Run it from the command line to generate the output like so

``` php index.php ```


 ## Requirements:
PHP 5.6


## Tests
All the tests are in the ``` Test ``` directory. I have used PHPUnit 5.7.19 as the unit testing framework.

from the root directory run the following command to run unit tests.

```php vendor/bin/phpunit src/Test ```

## Output Format
When you run the command ```php index.php ``` from the root directory, you will get an output in the format:
```
Stream1
1::1,0,0,1,0::1,0,0,1,0::0,0,0,0,0
2::2,3,4,5,2::2,0,0,0,2::0,3,4,5,0
...
Stream2
1::1,0,0,1,0::1,0,0,1,0::0,0,0,0,0
...
```
```Stream1``` identifies the stream. Following it is the order output with following format.
There are four parts in each order line
- first part specifies the order number e.g 1
- second part specifies the Order items with the quantities of items A,B,C,D,E in order separated by commas 
- third part specifies the Order fulfillment of items A,B,C,D,E in order
- fourth part specified the backorder of items A,B,C,D,E in order separated by commans

Developed By

Shikhar Subedi
