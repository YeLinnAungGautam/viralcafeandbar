<?php

use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;

if (!function_exists('logActivity')) {

    function logActivity(string $message, $model = null, string $logName = null)
    {
        return activity($logName)
            ->performedOn($model)
            ->causedBy(Auth::user())
            ->log($message);
    }
}

function getDescription(string $eventName, array $originalAttributes, array $changedAttributes, string $modelName, string $fieldName)
{
    $user = Auth::user()->name ?? 'System';
    switch ($eventName) {
        case 'created':
            return "The {$modelName} '{$changedAttributes[$fieldName]}' is created .";
        case 'updated':
            $descriptions = [];
            foreach ($changedAttributes as $field => $newValue) {
                if ($field === 'updated_at' || $field === 'id' || $field === 'created_at' || $field === 'password') {
                    continue;
                }
                $oldValue       = $originalAttributes[$field] ?? 'null';

                if (is_array($oldValue)) {
                    $oldValue = json_encode($oldValue);
                }
                if (is_array($newValue)) {
                    $newValue = json_encode($newValue);
                }
                if ($field === 'order_status' || $field === 'payment_status') {

                    $descriptions[] = "Field '{$field}' of the order code #{$originalAttributes[$fieldName]} was updated the value '{$oldValue}' to '{$newValue}'";
                } else {

                    $descriptions[] = "Field '{$field}' from {$modelName} was updated the value '{$oldValue}' to '{$newValue}'";
                }
            }

            $description = implode('. ', $descriptions);

            return "{$description} .";
        case 'deleted':
            return "The {$modelName} '{$originalAttributes[$fieldName]}' is deleted .";
    }
}
