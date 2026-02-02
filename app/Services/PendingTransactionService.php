<?php

namespace App\Services;

use App\Models\Pending;
use Illuminate\Support\Facades\DB;
use Exception;

class PendingTransactionService
{
    /**
     * Create a new pending transaction
     *
     * @param array $data
     * @return Pending
     * @throws Exception
     */
    public function createPendingTransaction(array $data): Pending
    {
        try {
            return Pending::create([
                'transaction' => $data['transaction'],
                'table_name' => $data['table_name'],
                'url' => $data['url'],
                'table_id' => $data['table_id'],
                'sender_user_id' => $data['sender_user_id'],
                'recipient_user_id' => $data['recipient_user_id'],
                'approval_step' => $data['approval_step'] ?? 1,
                'status' => $data['status'] ?? 0,
            ]);
        } catch (Exception $e) {
            throw new Exception('Failed to create pending transaction: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing pending transaction
     *
     * @param string $tableName
     * @param int $tableId
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updatePendingTransaction(string $tableName, int $tableId, array $data): bool
    {
        try {
            return Pending::where('table_name', $tableName)
                ->where('table_id', $tableId)
                ->update($data);
        } catch (Exception $e) {
            throw new Exception('Failed to update pending transaction: ' . $e->getMessage());
        }
    }

    /**
     * Delete pending transaction(s) by table name and table ID
     *
     * @param string $tableName
     * @param int $tableId
     * @return bool
     * @throws Exception
     */
    public function deletePendingTransaction(string $tableName, int $tableId): bool
    {
        try {
            return Pending::where('table_name', $tableName)
                ->where('table_id', $tableId)
                ->delete();
        } catch (Exception $e) {
            throw new Exception('Failed to delete pending transaction: ' . $e->getMessage());
        }
    }

    /**
     * Get pending transaction by table name and table ID
     *
     * @param string $tableName
     * @param int $tableId
     * @return Pending|null
     */
    public function getPendingTransaction(string $tableName, int $tableId): ?Pending
    {
        return Pending::where('table_name', $tableName)
            ->where('table_id', $tableId)
            ->first();
    }

    /**
     * Get all pending transactions for a specific recipient user
     *
     * @param int $recipientUserId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingTransactionsByRecipient(int $recipientUserId)
    {
        return Pending::where('recipient_user_id', $recipientUserId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all pending transactions for a specific sender user
     *
     * @param int $senderUserId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingTransactionsBySender(int $senderUserId)
    {
        return Pending::where('sender_user_id', $senderUserId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Update the status of a pending transaction
     *
     * @param string $tableName
     * @param int $tableId
     * @param int $status (0 = pending, 1 = approved, 2 = disapproved)
     * @return bool
     * @throws Exception
     */
    public function updatePendingTransactionStatus(string $tableName, int $tableId, int $status): bool
    {
        try {
            return Pending::where('table_name', $tableName)
                ->where('table_id', $tableId)
                ->update(['status' => $status]);
        } catch (Exception $e) {
            throw new Exception('Failed to update pending transaction status: ' . $e->getMessage());
        }
    }

    /**
     * Create a KWH Meter Request pending transaction (convenience method)
     *
     * @param int $kwhMeterRequestId
     * @param int $senderUserId
     * @param int $recipientUserId
     * @param int $approvalStep
     * @return Pending
     * @throws Exception
     */
    public function createKwhMeterRequestPending(
        int $kwhMeterRequestId, 
        int $senderUserId, 
        int $recipientUserId, 
        int $approvalStep = 1
    ): Pending {
        return $this->createPendingTransaction([
            'transaction' => 'KWH Meter Request',
            'table_name' => 'kwh_meter_requests',
            'url' => route('kwh-meter-request.show', $kwhMeterRequestId),
            'table_id' => $kwhMeterRequestId,
            'sender_user_id' => $senderUserId,
            'recipient_user_id' => $recipientUserId,
            'approval_step' => $approvalStep,
            'status' => 0,
        ]);
    }

    /**
     * Update a KWH Meter Request pending transaction (convenience method)
     *
     * @param int $kwhMeterRequestId
     * @param int $senderUserId
     * @param int $recipientUserId
     * @return bool
     * @throws Exception
     */
    public function updateKwhMeterRequestPending(
        int $kwhMeterRequestId, 
        int $senderUserId, 
        int $recipientUserId
    ): bool {
        return $this->updatePendingTransaction('kwh_meter_requests', $kwhMeterRequestId, [
            'url' => route('kwh-meter-request.show', $kwhMeterRequestId),
            'sender_user_id' => $senderUserId,
            'recipient_user_id' => $recipientUserId,
        ]);
    }

    /**
     * Create a Change Meter Request pending transaction (convenience method)
     *
     * @param int $changeMeterRequestId
     * @param int $senderUserId
     * @param int $recipientUserId
     * @param int $approvalStep
     * @return Pending
     * @throws Exception
     */
    public function createChangeMeterRequestPending(
        int $changeMeterRequestId, 
        int $senderUserId, 
        int $recipientUserId, 
        int $approvalStep = 1
    ): Pending {
        return $this->createPendingTransaction([
            'transaction' => 'Change Meter Request',
            'table_name' => 'change_meter_requests',
            'url' => route('change-meter-request.show', $changeMeterRequestId),
            'table_id' => $changeMeterRequestId,
            'sender_user_id' => $senderUserId,
            'recipient_user_id' => $recipientUserId,
            'approval_step' => $approvalStep,
            'status' => 0,
        ]);
    }

    /**
     * Create a Material Requisition Form pending transaction (convenience method)
     *
     * @param int $materialRequisitionFormId
     * @param int $senderUserId
     * @param int $recipientUserId
     * @param int $approvalStep
     * @return Pending
     * @throws Exception
     */
    public function createMaterialRequisitionFormPending(
        int $materialRequisitionFormId, 
        int $senderUserId, 
        int $recipientUserId, 
        int $approvalStep = 1
    ): Pending {
        return $this->createPendingTransaction([
            'transaction' => 'Material Requisition Form',
            'table_name' => 'material_requisition_forms',
            'url' => route('material-requisition-form.show', $materialRequisitionFormId),
            'table_id' => $materialRequisitionFormId,
            'sender_user_id' => $senderUserId,
            'recipient_user_id' => $recipientUserId,
            'approval_step' => $approvalStep,
            'status' => 0,
        ]);
    }

    /**
     * Check if a pending transaction exists for a given table and ID
     *
     * @param string $tableName
     * @param int $tableId
     * @return bool
     */
    public function pendingTransactionExists(string $tableName, int $tableId): bool
    {
        return Pending::where('table_name', $tableName)
            ->where('table_id', $tableId)
            ->exists();
    }

    /**
     * Get count of pending transactions by status for a specific recipient
     *
     * @param int $recipientUserId
     * @param int|null $status (null = all, 0 = pending, 1 = approved, 2 = disapproved)
     * @return int
     */
    public function countPendingTransactionsByRecipient(int $recipientUserId, ?int $status = null): int
    {
        $query = Pending::where('recipient_user_id', $recipientUserId);
        
        if ($status !== null) {
            $query->where('status', $status);
        }
        
        return $query->count();
    }

    /**
     * Bulk update pending transactions status
     *
     * @param array $pendingIds
     * @param int $status
     * @return bool
     * @throws Exception
     */
    public function bulkUpdatePendingTransactionStatus(array $pendingIds, int $status): bool
    {
        try {
            return Pending::whereIn('id', $pendingIds)
                ->update(['status' => $status]);
        } catch (Exception $e) {
            throw new Exception('Failed to bulk update pending transaction status: ' . $e->getMessage());
        }
    }
}