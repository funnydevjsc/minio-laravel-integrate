# MinIO Admin Laravel

The free Laravel package to help you integrate with your MinIO Standalone without using Minio Client

## Use Cases

- CRUD buckets, policies, users and objects.

## Features

- Dynamic MinIO credentials from config/minio.php
- Easy to manage your MinIO with a few lines of coding

## Requirements

- **PHP**: 8.1 or higher
- **Laravel** 9.0 or higher

## Quick Start

If you prefer to install this package into your own Laravel application, please follow the installation steps below

## Installation

#### Step 1. Install a Laravel project if you don't have one already

https://laravel.com/docs/installation

#### Step 2. Require the current package using composer:

```bash
composer require funnydevjsc/minio-laravel-integrate
```

#### Step 3. Publish the controller file and config file

```bash
php artisan vendor:publish --provider="FunnyDev\MinIO\MinIOServiceProvider" --tag="minio"
```

If publishing files fails, please create corresponding files at the path `config/minio.php` from this package.

#### Step 4. Update the various config settings in the published config file:

After publishing the package assets a configuration file will be located at <code>config/MinIO.php</code>. Please find in MinIO.io to get those values to fill into the config file.

<!--- ## Usage --->

## Testing

``` php
<?php
namespace App\Console\Commands;

use FunnyDev\MiniIO\MinIOSdk;
use FunnyDev\MiniIO\MinIOGroups;
use FunnyDev\MiniIO\MinIOPolicies;
use FunnyDev\MiniIO\MinIOUsers;
use FunnyDev\MiniIO\MinIOBuckets;

class MinIOTestCommand extends Command
{
    protected $signature = 'minio:test';

    protected $description = 'Test MinIO SDK';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        // SDK test
        $instance = new MinIOSdk();
        echo $instance->login() ? 'Login successfully' : 'Failed to login';
        
        // Groups test
        $groups = new MinIOGroups();
        $response = $groups->list();
        echo is_array($response) && isset($response['groups']) && is_array($response['groups']) ? 'Get groups list successfully' : 'Failed to get groups list'
        
        ...
    }
}
```

## Feedback

Respect us in the [Laravel Việt Nam](https://www.facebook.com/groups/167363136987053)

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email contact@funnydev.vn or use the issue tracker.

## Credits

- [Funny Dev., Jsc](https://github.com/funnydevjsc)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
