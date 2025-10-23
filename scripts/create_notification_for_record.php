<?php
// Script to create a test StudentNotification for a given SocialContractRecord id
// Usage: php scripts/create_notification_for_record.php <record_id>

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$recordId = isset($argv[1]) ? intval($argv[1]) : 22;

$record = App\Models\SocialContractRecord::with('socialContract')->find($recordId);
if (!$record) {
    echo "Record not found for id: {$recordId}\n";
    exit(1);
}

$studentId = data_get($record, 'socialContract.student_id');
if (!$studentId) {
    echo "No student_id found on the related socialContract for record {$recordId}\n";
    exit(1);
}

$notif = App\Models\StudentNotification::create([
    'user_id' => $studentId,
    'social_contract_record_id' => $recordId,
    'type' => 'rejected',
    'message' => 'Your submission was rejected',
    'rejection_reason' => 'Missing attachments. Please attach the required documents.',
    'is_read' => false,
]);

if ($notif) {
    echo "Created notification (id: {$notif->id}) for user_id: {$studentId}\n";
} else {
    echo "Failed to create notification\n";
}
