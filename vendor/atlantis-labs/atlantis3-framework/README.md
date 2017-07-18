# Atlantis 3 Framework #
This is the core module for Atlantis CMS. Its distributed freely under MIT license - https://opensource.org/licenses/MIT
#### 0.4.13 ####
- shows included modules in edit page from data-pattern-func attributes.
- Tag Helper added.
- page parent document fixed.
- minor improvements.
- bug fixes.
#### 0.4.14 ####
- crop was added for all image sizes (ex. Responsive Gallery/1024x768/640x480xC/320x240/300x300xC).
- the modules repository is only available for admin users.
- minor improvements.
- bug fixes.
#### 0.4.15 ####
- option "excluded scripts" was added in the config panel.
#### 0.5 ####
- Widgets was added in the Dashboard.
#### 0.5.1 ####
- Widgets fixed.
#### 1.0.11 ####
- unexpected media exceptions fixed.
- security improved.
- minor bug fixes.
- media cache added.
#### 1.0.12 ####
- php 7 exceptions fixed.
#### 1.0.13 ####
- pages and patterns expirations fixed.
- styles improved.
- MobileDetect class updated.
#### 1.0.14 ####
- media tools fixed.
#### 1.0.15 ####
- admin password reset fixed.
#### 1.0.16 ####
- Pages and Patterns Default Order - there is a new column now in the Pages and Patterns default lists, that indicates how many versions they have and orders the list based on amount of versions and last edited version
- Page.body event fixed where event won’t execute properly
- New system admin events added:
	- page.created, page.edited, page.deleted, pattern.created, pattern.edited, pattern.deleted
All of those will receive a copy of the page or pattern object respectively at the time of execution.
#### 1.0.17 ####
- command atlantis:create:theme was added
- minor bug fixes.
#### 1.0.18 ####
- added new Comments Interface,Tools Method to list Comment providing modules
- minor bug fixes.

# Migrating Atlantis from Laravel 5.1 to 5.3 (Atlantis 0.* to Atlantis 1.*)

If you want a fresh copy of Atlantis, you can simply get the latest version which would use Laravel 5.3

To install the latest stable version of Atlantis, you can use the following Composer command (this assumes you have Composer set up, if not, visit: https://getcomposer.org/):

`composer create-project atlantis-labs/atlantis3 . --prefer-dist`

This will get you the latest stable version of Atlantis.

If you are looking for the latest development version of Atlantis you can use:
`composer create-project atlantis-labs/atlantis3 . dev-master`

Be careful though, as this version may not be yet stable.
# Updating Atlantis from Laravel 5.1 to 5.3 (Atlantis 0.* to Atlantis 1.*)
Before updating the Atlantis framework to 1.*, you would need to make a few changes to the internal folders.
## Make sure your *config\app.php* looks something like this:
```php
<?php

return [
 ...
      |--------------------------------------------------------------------------
      | Encryption Key
      |--------------------------------------------------------------------------
      |
      | This key is used by the Illuminate encrypter service and should be set
      | to a random, 32 character string, otherwise these encrypted strings
      | will not be safe. Please do this before deploying an application!
      |
     */
    'key' => env('APP_KEY', 'SOMEAPPKEYISHERE'),
    'cipher' => 'AES-128-CBC',
    /*
      |--------------------------------------------------------------------------
      | Logging Configuration
      |--------------------------------------------------------------------------
      |
      | Here you may configure the log settings for your application. Out of
      | the box, Laravel uses the Monolog PHP logging library. This gives
      | you a variety of powerful log handlers / formatters to utilize.
      |
      | Available Settings: "single", "daily", "syslog", "errorlog"
      |
     */
    'log' => 'daily',
    /*
      |--------------------------------------------------------------------------
      | Autoloaded Service Providers
      |--------------------------------------------------------------------------
      |
      | The service providers listed here will be automatically loaded on the
      | request to your application. Feel free to add your own services to
      | this array to grant expanded functionality to your applications.
      |
     */
    'providers' => [
        'Atlantis\Providers\AtlantisServiceProvider'
    ],
...
];
```
The notable change here is the modification of the value of the cipher key (you may also need to regenerate your app key using *php artisan key:generate* )

##  Make sure your config\auth.php file looks like this:
```php

<?php

return [
    /*
      |--------------------------------------------------------------------------
      | Authentication Defaults
      |--------------------------------------------------------------------------
      |
      | This option controls the default authentication "guard" and password
      | reset options for your application. You may change these defaults
      | as required, but they're a perfect start for most applications.
      |
     */
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],
    /*
      |--------------------------------------------------------------------------
      | Authentication Guards
      |--------------------------------------------------------------------------
      |
      | Next, you may define every authentication guard for your application.
      | Of course, a great default configuration has been defined for you
      | here which uses session storage and the Eloquent user provider.
      |
      | All authentication drivers have a user provider. This defines how the
      | users are actually retrieved out of your database or other storage
      | mechanisms used by this application to persist your user's data.
      |
      | Supported: "session", "token"
      |
     */
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],
    ],
    /*
      |--------------------------------------------------------------------------
      | User Providers
      |--------------------------------------------------------------------------
      |
      | All authentication drivers have a user provider. This defines how the
      | users are actually retrieved out of your database or other storage
      | mechanisms used by this application to persist your user's data.
      |
      | If you have multiple user tables or models you may configure multiple
      | sources which represent each model / table. These sources may then
      | be assigned to any extra authentication guards you have defined.
      |
      | Supported: "database", "eloquent"
      |
     */
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \Atlantis\Models\User::class
        ]
    ],
    /*
      |--------------------------------------------------------------------------
      | Resetting Passwords
      |--------------------------------------------------------------------------
      |
      | Here you may set the options for resetting passwords including the view
      | that is your password reset e-mail. You may also set the name of the
      | table that maintains all of the reset tokens for your application.
      |
      | You may specify multiple password reset configurations if you have more
      | than one user table or model in the application and you want to have
      | separate password reset settings based on the specific user types.
      |
      | The expire time is the number of minutes that the reset token should be
      | considered valid. This security feature keeps tokens short-lived so
      | they have less time to be guessed. You may change this as needed.
      |
     */
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'email' => 'emails.password',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],
];


```
The notable changes here is the addition of guards, so you need the defaults key, the guards key and the syntax for the passwords and providers key may have changed so be sure that your providers/passwords match the syntax expressed above.
## Edit app\Providers
-  The **app\Providers\EventServiceProvider**'s boot method no longer supports any arguments. Therefore, you would have to edit the boot method to look like this:
```php

public function boot()
    {
        parent::boot();

        //
    }
```
You would have to do the same for **app\Providers\RouteServiceProvider.php**
## Edit config/compile.php
- You would have to remove two entries/lines from the file. Namely, `realpath(__DIR__.'/../app/Providers/BusServiceProvider.php'),` and `realpath(__DIR__.'/../app/Providers/ConfigServiceProvider.php'),`

## Edit app/Http/Controllers/Controller.php

Your file should resemble the following:

```
<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use DispatchesJobs, ValidatesRequests;

}


```

The change to note here is that DispatchesCommands has been replaced by DispatchesJobs so you have to change both use declarations to reflect the new DispatchesJobs class.

## Updating Atlantis

Thereafter, you may update Atlantis by changing the `composer.json` file in your root directory to require Atlantis 1.* version:
  ```
  "require": {
    "atlantis-labs/atlantis3-framework": "1.*"
  },
```
Finally, all you have to do is run `composer update` for your project.