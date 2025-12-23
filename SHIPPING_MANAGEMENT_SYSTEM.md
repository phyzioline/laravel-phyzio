# Shipping Management System Documentation

## Overview
A comprehensive Bosta-like shipping management system for managing multi-vendor orders shipping from vendor locations to customer locations.

## Features

### 1. Enhanced Shipment Tracking
- **Vendor Pickup Location**: Stores vendor name, phone, address, city, governorate, and coordinates
- **Customer Delivery Location**: Stores customer name, phone, address, city, governorate, and coordinates
- **Package Details**: Weight, dimensions (length, width, height), and description
- **Shipping Provider Integration**: Support for Bosta, Aramex, DHL, and manual shipping
- **Real-time Status Updates**: Multiple status stages (pending, ready_to_ship, picked_up, in_transit, out_for_delivery, delivered, exception, returned, cancelled)

### 2. Shipping Provider Integration
- **Bosta API**: Full integration support
- **Aramex API**: Full integration support
- **DHL API**: Full integration support
- **Manual Shipping**: For vendors who handle shipping themselves
- **Webhook Support**: Automatic status updates from shipping providers

### 3. Shipping Management Dashboard
- **Statistics Overview**: Pending, ready to ship, in transit, delivered today/month
- **Advanced Filtering**: By status, vendor, city, provider, and search
- **Bulk Operations**: Create multiple shipments at once
- **Shipping Cost Estimation**: Calculate shipping costs before creating shipment

### 4. Shipping Labels
- **Printable Labels**: Professional shipping labels with all necessary information
- **Barcode Support**: Tracking number barcode for easy scanning
- **Download/Print**: Download PDF or print directly

### 5. Tracking History
- **Complete Timeline**: Full tracking history with timestamps
- **Location Updates**: Track shipment location at each stage
- **Source Tracking**: Distinguish between API, manual, and system updates

## Database Schema

### Enhanced Shipments Table
The `shipments` table has been enhanced with the following fields:

**Vendor Information:**
- `vendor_name`, `vendor_phone`, `vendor_address`
- `vendor_city`, `vendor_governorate`
- `vendor_lat`, `vendor_lng`

**Customer Information:**
- `customer_name`, `customer_phone`, `customer_address`
- `customer_city`, `customer_governorate`
- `customer_lat`, `customer_lng`

**Shipping Provider:**
- `shipping_provider` (bosta, aramex, dhl, manual)
- `shipping_provider_id` (Provider's shipment ID)
- `shipping_label_url`
- `shipping_cost`
- `shipping_method` (express, standard, economy)

**Package Details:**
- `package_weight` (grams)
- `package_length`, `package_width`, `package_height` (cm)
- `package_description`

**Status Timestamps:**
- `ready_to_ship_at`, `picked_up_at`, `in_transit_at`
- `out_for_delivery_at`, `exception_at`
- `delivered_to`, `delivery_notes`, `exception_reason`

## Installation

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Configure Shipping Providers
Add to your `.env` file:
```env
# Bosta Configuration
BOSTA_ENABLED=true
BOSTA_API_KEY=your_bosta_api_key
BOSTA_BASE_URL=https://api.bosta.co/api/v2
BOSTA_WEBHOOK_SECRET=your_webhook_secret

# Aramex Configuration
ARAMEX_ENABLED=false
ARAMEX_API_KEY=your_aramex_api_key
ARAMEX_USERNAME=your_username
ARAMEX_PASSWORD=your_password

# DHL Configuration
DHL_ENABLED=false
DHL_API_KEY=your_dhl_api_key
```

### 3. Set Up Webhooks
Configure webhook URLs in your shipping provider dashboards:
- Bosta: `https://yourdomain.com/webhooks/shipping/bosta`
- Aramex: `https://yourdomain.com/webhooks/shipping/aramex`
- DHL: `https://yourdomain.com/webhooks/shipping/dhl`

## Usage

### Creating a Shipment

#### From Order (Admin)
1. Navigate to **Multi-Vendor > Shipping Management**
2. Click **Create Shipment** or go to an order
3. Select vendor and items
4. System automatically populates vendor and customer addresses
5. Choose shipping provider and method
6. Create shipment

#### Programmatically
```php
use App\Services\ShippingManagementService;

$shippingService = app(ShippingManagementService::class);

// Create shipment
$shipment = $shippingService->createShipment(
    $orderId,
    $vendorId,
    $itemIds // optional array of item IDs
);

// Create with provider
$shipment = $shippingService->createShipmentWithProvider(
    $shipmentId,
    'bosta',
    ['method' => 'standard']
);
```

### Updating Shipment Status

#### Manual Update
1. Go to shipment details page
2. Use **Update Status** form
3. Select new status and add notes
4. Save

#### Via Webhook (Automatic)
Shipping providers will automatically update shipment status via webhooks.

### Tracking Shipments

1. Navigate to **Shipping Management**
2. Use filters to find specific shipments
3. Click on shipment to view full details and tracking history

### Printing Shipping Labels

1. Open shipment details
2. Click **Download Label** or **Print Label**
3. Print the label and attach to package

## API Endpoints

### Webhook Endpoint
```
POST /webhooks/shipping/{provider}
```
Handles status updates from shipping providers.

### Cost Estimation
```
GET /dashboard/shipping-management/estimate-cost
```
Parameters:
- `from_city`: Origin city
- `to_city`: Destination city
- `weight`: Package weight in grams
- `method`: Shipping method (express, standard, economy)

## Service Methods

### ShippingManagementService

#### `createShipment($orderId, $vendorId, $itemIds = [])`
Creates a new shipment with vendor and customer addresses.

#### `createShipmentWithProvider($shipmentId, $provider, $options = [])`
Creates shipment via shipping provider API.

#### `updateStatusFromProvider($providerId, $status, $data = [])`
Updates shipment status from provider webhook.

#### `estimateShippingCost($fromCity, $toCity, $weight, $method = 'standard')`
Estimates shipping cost.

#### `bulkCreateShipments($orderIds, $vendorId = null)`
Creates multiple shipments at once.

#### `getShippingStatistics($vendorId = null)`
Returns shipping statistics for dashboard.

## Routes

### Dashboard Routes
- `GET /dashboard/shipping-management` - List all shipments
- `GET /dashboard/shipping-management/{id}` - View shipment details
- `POST /dashboard/shipping-management/create` - Create shipment
- `POST /dashboard/shipping-management/bulk-create` - Bulk create shipments
- `POST /dashboard/shipping-management/{id}/create-with-provider` - Create with provider
- `POST /dashboard/shipping-management/{id}/update-status` - Update status
- `GET /dashboard/shipping-management/{id}/download-label` - Download label
- `GET /dashboard/shipping-management/{id}/print-label` - Print label
- `GET /dashboard/shipping-management/estimate-cost` - Estimate cost

### Webhook Routes
- `POST /webhooks/shipping/{provider}` - Provider webhook handler

## Views

### Main Views
- `resources/views/dashboard/pages/shipping-management/index.blade.php` - Shipments list
- `resources/views/dashboard/pages/shipping-management/show.blade.php` - Shipment details
- `resources/views/dashboard/pages/shipping-management/label.blade.php` - Shipping label

## Navigation

The shipping management system is accessible from:
- **Dashboard Sidebar > Multi-Vendor > Shipping Management**

## Status Flow

1. **pending** - Shipment created, awaiting processing
2. **ready_to_ship** - Shipment ready, label generated
3. **picked_up** - Package picked up by courier
4. **in_transit** - Package in transit
5. **out_for_delivery** - Out for delivery
6. **delivered** - Successfully delivered
7. **exception** - Delivery exception occurred
8. **returned** - Package returned
9. **cancelled** - Shipment cancelled

## Troubleshooting

### Shipment Not Created
- Check if order has items
- Verify vendor ID is correct
- Ensure items are not already assigned to another shipment

### Provider API Not Working
- Verify API credentials in `.env`
- Check API endpoint URLs
- Review logs for error messages

### Webhook Not Receiving Updates
- Verify webhook URL is correctly configured in provider dashboard
- Check webhook secret matches
- Review webhook logs

### Address Not Populated
- Vendor address: Currently uses default values. Can be enhanced to pull from vendor profile.
- Customer address: Pulled from order address field.

## Future Enhancements

1. **Vendor Address Management**: Add vendor warehouse address management
2. **Geocoding**: Automatic lat/lng from addresses
3. **Route Optimization**: Optimize delivery routes
4. **SMS Notifications**: Send SMS updates to customers
5. **Email Tracking**: Email tracking updates
6. **Analytics Dashboard**: Advanced shipping analytics
7. **Multi-currency Support**: Support for different currencies
8. **International Shipping**: Support for international shipments

## Support

For issues or questions, please contact the development team.

