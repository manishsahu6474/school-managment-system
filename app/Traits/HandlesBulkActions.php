<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait HandlesBulkActions
{
    /**
     * Generic method to handle status updates (Approve, Activate, Inactivate).
     */
    protected function performBulkStatusUpdate($ids, $newStatus, $successMsg)
    {
        try {
            $result = $this->service->bulkStatusUpdate($this->model, (array)$ids, $newStatus);

            $updatedCount = $result['count'];
            if ($updatedCount == 0) {
                return response()->json([
                    'status'  => 'info',
                    'message' => 'No changes made. Records are already in the target state.'
                ], 200);
            }

            $finalMsg = $successMsg;
            if ($newStatus == 1) {
                if ($result['hasInactive'] && $result['hasPending']) {
                    $finalMsg = "Processed (Approved & Re-activated) successfully!";
                } elseif ($result['hasInactive']) {
                    $finalMsg = "Re-activated successfully!";
                } elseif ($result['hasPending']) {
                    $finalMsg = "Approved successfully!";
                }
            }

            $label = ($updatedCount > 1) ? Str::plural($this->resourceLabel) : $this->resourceLabel;

            return response()->json([
                'status'  => 'success',
                'message' => "{$updatedCount} {$label} {$finalMsg}"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Common AJAX Toggle Status
     */
    public function toggleStatus(Request $request, $id)
    {
        if (!$request->ajax() && !$request->expectsJson()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Request'], 400);
        }
        return $this->performBulkStatusUpdate([$id], 1, 'Status Changed Successfully!');
    }

    /**
     * Common Bulk Operations
     */
    public function bulkApprove(Request $request)
     { 
        return $this->performBulkStatusUpdate($request->ids, 1, ' Processed Successfully!'); }
    public function bulkActivate(Request $request) { 
        return $this->performBulkStatusUpdate($request->ids, 1, ' Re-activate successfully!'); }
    public function bulkInactivate(Request $request) {
         return $this->performBulkStatusUpdate($request->ids, 2, ' moved to inactive list successfully!'); }

    /**
     * Common Bulk Delete
     */
    public function bulkDelete(Request $request)
    {
        try {
            $result = $this->service->bulkDelete($this->model, $request->ids);
            if ($result['count'] == 0) {
                return response()->json(['status' => 'info', 'message' => 'No records found to delete.'], 200);
            }
            $entity = Str::plural($this->resourceLabel, $result['count']);
            return response()->json(['status' => 'success', 'message' => "{$result['count']} Pending {$entity} deleted permanently."], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }
    public function destroy($id)
    {       
        try {
            $instance = $this->model::findOrFail($id);
            $result = $this->service->smartDelete($instance);
            
            return response()->json([
                'status' => 'success',
                'message' => $result['message']
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}