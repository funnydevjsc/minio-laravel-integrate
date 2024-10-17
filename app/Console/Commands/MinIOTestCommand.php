<?php

namespace App\Console\Commands;

use FunnyDev\MinIO\MinIOSdk;
use FunnyDev\MinIO\MinIOGroups;
use FunnyDev\MinIO\MinIOPolicies;
use FunnyDev\MinIO\MinIOUsers;
use FunnyDev\MinIO\MinIOBuckets;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class MinIOTestCommand extends Command
{
    protected $signature = 'minio:test';

    protected $description = 'Test MinIO SDK';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws GuzzleException
     */
    public function handle(): void
    {
        // SDK test
        $instance = new MinIOSdk();
        $logged_in = $instance->login();
        echo $logged_in ? "Login successfully\n" : "Failed to login\n";

        if ($logged_in) {// Groups test
            // Users test
            $users = new MinIOUsers();
            $response = $users->list();
            echo is_array($response) && isset($response['users']) && is_array($response['users']) ? "Get users list successfully\n" : "Failed to get users list\n";
            $users->create(access_key: 'access-key', secret_key: 'secret-key', groups: ['group1', 'group2'], policies: ['policy1', 'policy2']);
            $users->update(access_key: 'access-key', secret_key: 'secret-key', enable: true, groups: ['group1'], policies: ['policy1', 'policy2', 'policy3']);
            $users->update_password(user: 'access-key', new_secret_key: 'new-secret-key');

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

            $groups = new MinIOGroups();
            $response = $groups->list();
            echo is_array($response) && isset($response['groups']) && is_array($response['groups']) ? "Get groups list successfully\n" : "Failed to get groups list\n";
            $groups->create(name: 'new-group', members: ['access-key']);
            $groups->update(name: 'new-group', enable: true, members: []);
            $groups->update_policies(name: 'new-group', policies: ['policy1', 'policy2']);

            // Buckets test
            $buckets = new MinIOBuckets();
            $response = $buckets->list();
            echo is_array($response) && isset($response['buckets']) && is_array($response['buckets']) ? "Get buckets list successfully\n" : "Failed to get buckets list\n";
            $buckets->create(name: 'new-bucket', quota: 1099511627776, retention: 30, retention_mode: 'compliance', locking: true); // Create 1Tb bucket for 30 days with object locking
            $buckets->update(name: 'new-bucket', attribute: 'quota', data: ['quota' => 1099511627776]);

            // Undo test
            $buckets->delete(name: 'new-bucket');
            $users->delete(name: 'access-key');
            $policies->delete(name: 'new-policy');
            $groups->delete(name: 'new-group');
        }
    }
}
