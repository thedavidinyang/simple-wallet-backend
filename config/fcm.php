<?php

return [
    'project_id' => env('FCM_PROJECT_ID'), 
    'credentials_file' => storage_path('app/json/'.env('FCM_CREDENTIAL_FILE')), 
    'api_url' => env('FCM_API_URL', 'https://fcm.googleapis.com/v1'), 
];
