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
        echo $instance->login() ? "Login successfully\n" : "Failed to login\n";

        // Groups test
        $groups = new MinIOGroups();
        $response = $groups->list();
        echo is_array($response) && isset($response['groups']) && is_array($response['groups']) ? "Get groups list successfully\n" : "Failed to get groups list\n";
        $groups->create(name: 'new-group', members: ['user1', 'user2']);
        $groups->update(name: 'new-group', enable: true, members: ['user1', 'user2', 'user3']);
        $groups->delete(name: 'new-group');
        $groups->update_policies(name: 'new-group', policies: ['policy1', 'policy2']);

        // Policies test
        $policies = new MinIOPolicies();
        $response = $policies->list();
        echo is_array($response) && isset($response['policies']) && is_array($response['policies']) ? "Get policies list successfully\n" : "Failed to get policies list\n";
        $policy = [
            "Version" => "2012-10-17",
            "Statement" => [
                [
                    "Effect" => "Allow",
                    "Action" => [
                        "s3:GetObject",
                        "s3:ListBucketMultipartUploads",
                        "s3:PutObject",
                        "s3:AbortMultipartUpload",
                        "s3:DeleteObject"
                    ],
                    "Resource" => [
                        "arn:aws:s3:::$[aws:username]/*"
                    ]
                ],
                [
                    "Effect" => "Allow",
                    "Action" => [
                        "s3:ListBucket"
                    ],
                    "Resource" => [
                        "arn:aws:s3:::$[aws:username]"
                    ],
                    "Condition" => [
                        "StringLike" => [
                            "s3:prefix" => [
                                "",
                                "*"
                            ]
                        ]
                    ]
                ]
            ]
        ]; // This rule will allow user to access read/write for bucket which has the same name as username only
        $policies->create(name: 'new-policy', rule: json_encode($policy));
        $new_policy = [];
        $policies->update(name: 'new-policy', rule: json_encode($new_policy));
        $policies->delete(name: 'new-policy');

        // Buckets test
        $buckets = new MinIOBuckets();
        $response = $buckets->list();
        echo is_array($response) && isset($response['buckets']) && is_array($response['buckets']) ? "Get buckets list successfully\n" : "Failed to get buckets list\n";
        $buckets->create(name: 'new-bucket', quota: 1099511627776, retention: 30, retention_mode: 'compliance', locking: true); // Create 1Tb bucket for 30 days with object locking
        $buckets->update(name: 'new-bucket', attribute: 'quota', data: ['quota' => 1099511627776]);
        $buckets->delete(name: 'new-bucket');

        // Users test
        $users = new MinIOUsers();
        $response = $users->list();
        echo is_array($response) && isset($response['users']) && is_array($response['users']) ? "Get users list successfully\n" : "Failed to get users list\n";
        $users->create(access_key: 'access-key', secret_key: 'secret-key', groups: ['group1', 'group2'], policies: ['policy1', 'policy2']);
        $users->update(access_key: 'access-key', secret_key: 'secret-key', enable: true, groups: ['group1'], policies: ['policy1', 'policy2', 'policy3']);
        $users->update_password(user: 'access-key', new_secret_key: 'new-secret-key');
        $users->delete(access_key: 'access-key');
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
