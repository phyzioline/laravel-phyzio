<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

/**
 * Order Status State Machine Service
 * 
 * Enforces valid status transitions for orders:
 * pending → processing → shipped → delivered → completed
 * pending → cancelled (at any time)
 * 
 * Invalid transitions are rejected with clear error messages.
 */
class OrderStatusStateMachine
{
    /**
     * Valid status transitions map
     * Key: current status, Value: array of allowed next statuses
     */
    protected $validTransitions = [
        'pending' => ['processing', 'cancelled', 'pending_payment'],
        'pending_payment' => ['processing', 'cancelled', 'pending'],
        'processing' => ['shipped', 'cancelled'],
        'shipped' => ['delivered', 'cancelled'],
        'delivered' => ['completed'],
        'completed' => [], // Terminal state - no transitions allowed
        'cancelled' => [], // Terminal state - no transitions allowed
    ];

    /**
     * Check if a status transition is valid.
     * 
     * @param string $currentStatus
     * @param string $newStatus
     * @return bool
     */
    public function isValidTransition(string $currentStatus, string $newStatus): bool
    {
        // Same status is always valid (no-op)
        if ($currentStatus === $newStatus) {
            return true;
        }

        // Check if transition is in allowed list
        $allowedTransitions = $this->validTransitions[$currentStatus] ?? [];
        
        return in_array($newStatus, $allowedTransitions);
    }

    /**
     * Get allowed next statuses for current status.
     * 
     * @param string $currentStatus
     * @return array
     */
    public function getAllowedTransitions(string $currentStatus): array
    {
        return $this->validTransitions[$currentStatus] ?? [];
    }

    /**
     * Validate and update order status.
     * 
     * @param Order $order
     * @param string $newStatus
     * @param array $additionalData Additional data to update with status
     * @return bool Success status
     * @throws \Exception If transition is invalid
     */
    public function transition(Order $order, string $newStatus, array $additionalData = []): bool
    {
        $currentStatus = $order->status;

        if (!$this->isValidTransition($currentStatus, $newStatus)) {
            $allowed = $this->getAllowedTransitions($currentStatus);
            $allowedList = empty($allowed) ? 'none (terminal state)' : implode(', ', $allowed);
            
            Log::warning('Invalid order status transition attempted', [
                'order_id' => $order->id,
                'current_status' => $currentStatus,
                'attempted_status' => $newStatus,
                'allowed_transitions' => $allowed
            ]);

            throw new \Exception(
                "Invalid status transition: Cannot change order status from '{$currentStatus}' to '{$newStatus}'. " .
                "Allowed transitions: {$allowedList}"
            );
        }

        // Update order with new status and additional data
        $updateData = array_merge(['status' => $newStatus], $additionalData);
        $order->update($updateData);

        // Log the transition
        Log::info('Order status transitioned', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'from' => $currentStatus,
            'to' => $newStatus,
            'updated_by' => auth()->id() ?? 'system'
        ]);

        // Handle status-specific side effects
        $this->handleStatusSideEffects($order, $newStatus, $currentStatus);

        return true;
    }

    /**
     * Handle side effects when status changes.
     * 
     * @param Order $order
     * @param string $newStatus
     * @param string $oldStatus
     * @return void
     */
    protected function handleStatusSideEffects(Order $order, string $newStatus, string $oldStatus): void
    {
        switch ($newStatus) {
            case 'processing':
                // Order is being prepared
                // Could send notification to vendor here
                break;

            case 'shipped':
                // Order has been shipped
                // Update shipment status if exists
                if ($order->shipments()->exists()) {
                    $order->shipments()->latest()->first()->update([
                        'shipment_status' => 'shipped',
                        'shipped_at' => now()
                    ]);
                }
                break;

            case 'delivered':
                // Order has been delivered
                // Update shipment status
                if ($order->shipments()->exists()) {
                    $order->shipments()->latest()->first()->update([
                        'shipment_status' => 'delivered',
                        'delivered_at' => now()
                    ]);
                }
                break;

            case 'completed':
                // Order is fully completed
                // Ensure payment status is paid
                if ($order->payment_status !== 'paid') {
                    $order->update(['payment_status' => 'paid']);
                }
                break;

            case 'cancelled':
                // Order is cancelled
                // Release stock reservations
                $stockService = app(\App\Services\StockReservationService::class);
                $stockService->releaseReservation($order);
                
                // Update payment status
                if ($order->payment_status === 'pending') {
                    $order->update(['payment_status' => 'failed']);
                }
                break;
        }
    }

    /**
     * Get human-readable status name.
     * 
     * @param string $status
     * @return string
     */
    public function getStatusLabel(string $status): string
    {
        $labels = [
            'pending' => 'Pending',
            'pending_payment' => 'Pending Payment',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        return $labels[$status] ?? ucfirst($status);
    }

    /**
     * Check if status is terminal (no further transitions allowed).
     * 
     * @param string $status
     * @return bool
     */
    public function isTerminalStatus(string $status): bool
    {
        return empty($this->validTransitions[$status] ?? []);
    }
}

