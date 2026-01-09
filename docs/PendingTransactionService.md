# Pending Transaction Service Documentation

The `PendingTransactionService` is a service class designed to handle all pending transaction operations in a centralized, maintainable, and reusable way.

## Purpose

This service encapsulates the logic for managing pending transactions, making the code more:
- **Maintainable**: All pending transaction logic is centralized
- **Reusable**: Can be used across multiple controllers
- **Testable**: Easy to unit test business logic
- **Consistent**: Ensures consistent handling of pending transactions

## Installation & Usage

### 1. Inject the Service

In your controller constructor, inject the `PendingTransactionService`:

```php
use App\Services\PendingTransactionService;

class YourController extends Controller
{
    protected $pendingTransactionService;

    public function __construct(PendingTransactionService $pendingTransactionService)
    {
        $this->pendingTransactionService = $pendingTransactionService;
    }
}
```

### 2. Basic Usage

#### Creating a Pending Transaction

```php
// Generic way
$pending = $this->pendingTransactionService->createPendingTransaction([
    'transaction' => 'Your Transaction Type',
    'table_name' => 'your_table_name',
    'url' => route('your.route', $id),
    'table_id' => $id,
    'sender_user_id' => $senderUserId,
    'recipient_user_id' => $recipientUserId,
    'approval_step' => 1,
    'status' => 0,
]);

// Using convenience methods (recommended)
$pending = $this->pendingTransactionService->createKwhMeterRequestPending(
    $kwhMeterRequestId,
    $senderUserId,
    $recipientUserId
);
```

#### Updating a Pending Transaction

```php
// Generic update
$this->pendingTransactionService->updatePendingTransaction(
    'kwh_meter_requests',
    $id,
    [
        'url' => route('kwh-meter-request.show', $id),
        'sender_user_id' => $newSenderId,
        'recipient_user_id' => $newRecipientId,
    ]
);

// Using convenience methods
$this->pendingTransactionService->updateKwhMeterRequestPending(
    $kwhMeterRequestId,
    $newSenderId,
    $newRecipientId
);
```

#### Deleting a Pending Transaction

```php
$this->pendingTransactionService->deletePendingTransaction(
    'kwh_meter_requests',
    $kwhMeterRequestId
);
```

## Available Methods

### Core Methods

- `createPendingTransaction(array $data): Pending`
- `updatePendingTransaction(string $tableName, int $tableId, array $data): bool`
- `deletePendingTransaction(string $tableName, int $tableId): bool`
- `getPendingTransaction(string $tableName, int $tableId): ?Pending`

### Convenience Methods

- `createKwhMeterRequestPending(...): Pending`
- `updateKwhMeterRequestPending(...): bool`
- `createChangeMeterRequestPending(...): Pending`
- `createMaterialRequisitionFormPending(...): Pending`

### Query Methods

- `getPendingTransactionsByRecipient(int $recipientUserId)`
- `getPendingTransactionsBySender(int $senderUserId)`
- `pendingTransactionExists(string $tableName, int $tableId): bool`
- `countPendingTransactionsByRecipient(int $recipientUserId, ?int $status = null): int`

### Status Management

- `updatePendingTransactionStatus(string $tableName, int $tableId, int $status): bool`
- `bulkUpdatePendingTransactionStatus(array $pendingIds, int $status): bool`

Status values:
- `0` = Pending
- `1` = Approved  
- `2` = Disapproved

## Examples

### Complete Controller Implementation

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\YourModel;
use App\Services\PendingTransactionService;
use DB;
use Illuminate\Http\Request;

class YourController extends Controller
{
    protected $pendingTransactionService;

    public function __construct(PendingTransactionService $pendingTransactionService)
    {
        $this->pendingTransactionService = $pendingTransactionService;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'approved_by' => 'required|exists:users,id',
            // other validation rules...
        ]);

        DB::beginTransaction();
        try {
            $model = YourModel::create($validatedData);

            // Create pending transaction
            $this->pendingTransactionService->createPendingTransaction([
                'transaction' => 'Your Transaction Type',
                'table_name' => 'your_table_name',
                'url' => route('your.show', $model->id),
                'table_id' => $model->id,
                'sender_user_id' => $validatedData['user_id'],
                'recipient_user_id' => $validatedData['approved_by'],
                'approval_step' => 1,
                'status' => 0,
            ]);

            DB::commit();
            return redirect()->route('your.index')->with('success', 'Created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to create.'])->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'approved_by' => 'required|exists:users,id',
            // other validation rules...
        ]);

        DB::beginTransaction();
        try {
            $model = YourModel::findOrFail($id);

            // Update the pending transaction
            $this->pendingTransactionService->updatePendingTransaction(
                'your_table_name',
                $model->id,
                [
                    'url' => route('your.show', $id),
                    'sender_user_id' => $validatedData['user_id'],
                    'recipient_user_id' => $validatedData['approved_by'],
                ]
            );

            $model->update($validatedData);

            DB::commit();
            return redirect()->route('your.index')->with('success', 'Updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to update.'])->withInput();
        }
    }

    public function destroy(string $id)
    {
        $model = YourModel::findOrFail($id);

        DB::beginTransaction();
        try {
            // Delete pending transaction
            $this->pendingTransactionService->deletePendingTransaction(
                'your_table_name',
                $model->id
            );

            $model->delete();

            DB::commit();
            return redirect()->route('your.index')->with('success', 'Deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('your.index')->withErrors(['error' => 'Failed to delete.']);
        }
    }
}
```

## Adding New Transaction Types

To add support for a new transaction type, add a convenience method to the service:

```php
/**
 * Create a [New Transaction Type] pending transaction (convenience method)
 */
public function createNewTransactionTypePending(
    int $newTransactionId, 
    int $senderUserId, 
    int $recipientUserId, 
    int $approvalStep = 1
): Pending {
    return $this->createPendingTransaction([
        'transaction' => 'New Transaction Type',
        'table_name' => 'new_transaction_table',
        'url' => route('new-transaction.show', $newTransactionId),
        'table_id' => $newTransactionId,
        'sender_user_id' => $senderUserId,
        'recipient_user_id' => $recipientUserId,
        'approval_step' => $approvalStep,
        'status' => 0,
    ]);
}
```

## Error Handling

The service throws exceptions when operations fail. Always wrap service calls in try-catch blocks:

```php
try {
    $this->pendingTransactionService->createPendingTransaction($data);
} catch (\Exception $e) {
    // Handle the error appropriately
    Log::error('Failed to create pending transaction: ' . $e->getMessage());
    return redirect()->back()->withErrors(['error' => 'Operation failed.']);
}
```

## Benefits

1. **Code Reusability**: Use the same methods across different controllers
2. **Consistency**: Ensures all pending transactions are handled the same way
3. **Maintainability**: Changes to pending transaction logic only need to be made in one place
4. **Testability**: Easy to unit test the service independently
5. **Error Handling**: Centralized error handling and exception management
6. **Type Safety**: Proper type hints and return types for better IDE support