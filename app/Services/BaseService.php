<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;

class BaseService
{
    /**
     * Common Validation for all Bulk Operations
     */
    protected function validateBulkIds($model, array $ids)
    {
        if (empty($ids)) {
            throw new Exception('Please select valid records!');
        }

        if (count($ids) > 50) {
            throw new Exception('Only 50 selections are processed at a time!');
        }

        $tableName = (new $model)->getTable();
        $validator = Validator::make(['ids' => $ids], [
            'ids.*' => "required|integer|exists:{$tableName},id"
        ]);

        if ($validator->fails()) {
            throw new Exception('Some selected records are invalid or do not exist.');
        }

        return array_unique($ids);
    }

    /**
     * Bulk Status Update 
     */
    public function bulkStatusUpdate($model, array $ids, int $newstatus)
    {
        $ids = $this->validateBulkIds($model, $ids);

        return DB::transaction(function () use ($model, $ids, $newstatus) {
            $query = $model::whereIn('id', $ids)->where('status', '!=', $newstatus);

            if ($newstatus == 1) {
                $query->whereIn('status', [0, 2]);
            } elseif ($newstatus == 2) {
                $query->where('status', 1);
            }

            $hasPending = (clone $query)->where('status', 0)->exists();
            $hasInactive = (clone $query)->where('status', 2)->exists();

            $updateCount = $query->update(['status' => $newstatus]);

            return [
                'count' => $updateCount,
                'hasPending' => $hasPending,
                'hasInactive' => $hasInactive
            ];
        });
    }

    /**
     * Bulk Delete
     */
    public function bulkDelete($model, array $ids)
    {
        $ids = $this->validateBulkIds($model, $ids);

        return DB::transaction(function () use ($model, $ids) {
            $userIds = $model::whereIn('id', $ids)
                ->where('status', 0)
                ->pluck('user_id');

            $deleteCount = $userIds->count();

            if ($deleteCount > 0) {

                User::whereIn('id', $userIds)->delete();
            }

            return ['count' => $deleteCount];
        });
    }
    public function smartDelete($record)
    {
        return DB::transaction(function () use ($record) {
            if ($record->status == 1) {
                $record->update(['status' => 2]);
                return [
                    'type' => 'Inactivated',
                    'message' => 'Record successfully moved to Inactive list!'
                ];
            }
            if ($record->status == 0) {
                if ($record->user) {
                    $record->user->delete();
                }
                return [
                    'type' => 'Delete',
                    'message' => 'Registration rejected and Permanently deleted!'
                ];
            }
            throw new Exception('Record is already Inactive or in an invalid state.');
        });
    }
}
