<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    /**
     * Get the Help Center Database based on Locale
     */
    protected function getKnowledgeBase()
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->getArabicContent();
        }

        return $this->getEnglishContent();
    }

    protected function getEnglishContent()
    {
        return [
        'introduction' => [
            'icon' => 'las la-info-circle',
            'title' => 'Introduction to Phyzioline',
            'slug' => 'introduction',
            'description' => 'Platform overview, ecosystem roles, and operational model.',
            'articles' => [
                'what-is-phyzioline' => [
                    'title' => 'What is Phyzioline?',
                    'content' => '
                        <h3>Platform Overview</h3>
                        <p>Phyzioline is a vertically integrated, multi-vendor e-commerce and distribution platform specialized in physical therapy, rehabilitation, medical fitness, and wellness equipment. Unlike generic marketplaces, we enforce strict medical compliance and quality control.</p>
                        
                        <h3>The Ecosystem</h3>
                        <p>The platform connects four key stakeholders:</p>
                        <ul>
                            <li><strong>End Customers:</strong> Clinics, hospitals, physiotherapists, and patients.</li>
                            <li><strong>Vendors & Manufacturers:</strong> Verified local and international suppliers of medical equipment.</li>
                            <li><strong>Logistics Partners:</strong> Specialized handlers for sensitive medical devices (cold chain, fragile).</li>
                            <li><strong>Financial Institutions:</strong> Secure payment processing and B2B financing.</li>
                        </ul>
                        <div class="alert alert-info border-0 shadow-sm">
                            <i class="las la-info-circle mr-2"></i> <strong>Note:</strong> Phyzioline operates as a managed marketplace. All products and vendors undergo rigorous vetting before being live.
                        </div>
                    '
                ],
                'who-uses-phyzioline' => [
                    'title' => 'Who Uses Phyzioline?',
                    'content' => '
                        <h3>User Roles & Capabilities</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Role</th>
                                        <th>Key Activities</th>
                                        <th>Verification Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Retail Customers</strong></td>
                                        <td>Buy personal rehab products, book home visits.</td>
                                        <td>Email/Phone Verification</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Clinics & Hospitals</strong></td>
                                        <td>Bulk procurement, recurring orders, tax invoices.</td>
                                        <td>Trade License + Tax ID</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Vendors / Sellers</strong></td>
                                        <td>List products, manage inventory, fulfillment.</td>
                                        <td>Full KYC (Legal & Banking)</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dropshippers</strong></td>
                                        <td>Sell without inventory via approved suppliers.</td>
                                        <td>Agreement with Suppliers</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    '
                ]
            ]
        ],
        'account-management' => [
            'icon' => 'las la-user-cog',
            'title' => 'Account & User Management',
            'slug' => 'account-management',
            'description' => 'Registration guides, KYC requirements, and strict penalty policies.',
            'articles' => [
                'creating-customer-account' => [
                    'title' => 'Creating a Customer Account',
                    'content' => '
                        <h3>Step-by-Step Registration</h3>
                        <ol>
                            <li>Navigate to <strong>Phyzioline.com</strong> and click <strong>Sign Up</strong>.</li>
                            <li>Select your entity type:
                                <ul>
                                    <li><strong>Individual:</strong> For patients and personal use.</li>
                                    <li><strong>Clinic / Company:</strong> For business procurement (requires Tax ID).</li>
                                </ul>
                            </li>
                            <li>Fill in the mandatory fields: Full Name, Email, Mobile Number (OTP verified), and a strong Password.</li>
                            <li>Complete the OTP verification for both Email and Mobile to activate the account.</li>
                        </ol>

                        <h3 class="text-danger mt-4">Restricted Actions</h3>
                        <ul>
                            <li><strong>Duplicate Accounts:</strong> Creating multiple accounts to abuse welcome offers is strictly forbidden.</li>
                            <li><strong>Fake Credentials:</strong> Using temporary emails or VOIP numbers will trigger an automatic block.</li>
                        </ul>

                        <h3>Common Mistakes</h3>
                        <ul>
                            <li>Entering an incorrect tax number prevents generation of valid B2B invoices.</li>
                            <li>Using a shared generic email (e.g., info@clinic.com) for a personal account.</li>
                        </ul>
                    '
                ],
                'vendor-account-registration' => [
                    'title' => 'Vendor Account Registration & Policies',
                    'content' => '
                         <h3>How to Register Correctly</h3>
                        <ol>
                            <li>Apply via the <strong>Vendor Portal</strong>.</li>
                            <li>Upload the "Golden Four" documents:
                                <ul>
                                    <li><strong>Trade License / Commercial Register:</strong> Must be active for at least 6 months.</li>
                                    <li><strong>Tax Registration / VAT Certificate:</strong> Mandatory for payouts.</li>
                                    <li><strong>Bank Account Letter:</strong> Must match the company legal name.</li>
                                    <li><strong>National ID / Passport:</strong> Of the authorized signatory.</li>
                                </ul>
                            </li>
                            <li>Wait for the <strong>Compliance Review</strong> (3–7 business days).</li>
                        </ol>

                        <h3 class="text-danger mt-4">Exact Penalties & Enforcement</h3>
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Violation</th>
                                    <th>Action / Penalty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Submitting forged documents</td>
                                    <td><strong>Permanent Ban</strong> (Blacklisted ID)</td>
                                </tr>
                                <tr>
                                    <td>Mismatch in Bank Name</td>
                                    <td>Application Rejection (Resubmit allowed)</td>
                                </tr>
                                <tr>
                                    <td>Selling Counterfeit Goods</td>
                                    <td><strong>Permanent Ban</strong> + Legal Action + Fund Hold (180 days)</td>
                                </tr>
                            </tbody>
                        </table>
                    '
                ]
            ]
        ],
        'product-catalog' => [
            'icon' => 'las la-box-open',
            'title' => 'Product Policies & Listing Quality',
            'slug' => 'product-catalog',
            'description' => 'Listing medical devices correctly, avoiding claiming violations, and image rules.',
            'articles' => [
                'creating-listing' => [
                    'title' => 'Listing Guidelines & Quality Score',
                    'content' => '
                        <h3>Listing Quality Standards</h3>
                        <p>To ensure high conversion and medical safety, all listings must meet these standards:</p>
                        <ul>
                            <li><strong>Title:</strong> [Brand] + [Model] + [Key Feature] + [Generic Name]. <br><em>Example: "Omron M3 Comfort Upper Arm Blood Pressure Monitor".</em></li>
                            <li><strong>Images:</strong> Minimum 5 high-res images on pure white background (RGB 255,255,255). No watermarks.</li>
                            <li><strong>Description:</strong> Must clearly state indications, contraindications, and technical specs.</li>
                        </ul>

                        <h3 class="text-danger mt-4">Restricted / Forbidden Actions</h3>
                        <ul>
                            <li><strong>False Medical Claims:</strong> Using words like "Cure", "Miracle", or "Instant Fix" without FDA/CE proof.</li>
                            <li><strong>Keyword Stuffing:</strong> Adding unrelated keywords in title (e.g., selling a brace but adding "wheelchair" in title).</li>
                            <li><strong>Copied Content:</strong> Copy-pasting descriptions directly from Amazon or competitors (SEO Violation).</li>
                        </ul>

                        <h3>System Enforcement</h3>
                        <ul>
                            <li><strong>Search Suppression:</strong> Listings with poor images or short descriptions are removed from search results.</li>
                            <li><strong>Account Flagging:</strong> 3 Policy Violations in 30 days results in a <strong>7-day Account Suspension</strong>.</li>
                        </ul>
                    '
                ],
                'prohibited-items' => [
                     'title' => 'Prohibited & Restricted Items',
                     'content' => '
                        <h3>Prohibited Items</h3>
                        <p>The following items are strictly banned from Phyzioline:</p>
                        <ul>
                            <li>Prescription-only medicines (POM) without specific pharmacy license integration.</li>
                            <li>Used or refurbished hygiene products (e.g., used electrodes, open creams).</li>
                            <li>Devices with expired calibration certificates.</li>
                        </ul>
                        <h3>Restricted Items (Requires Approval)</h3>
                        <ul>
                            <li><strong>Class IIb & III Medical Devices:</strong> Require explicit MOH approval upload.</li>
                            <li><strong>Radioactive / X-Ray Equipment:</strong> Requires special logistics clearance.</li>
                        </ul>
                     '
                ]
            ]
        ],
        'order-management' => [
            'icon' => 'las la-shopping-cart',
            'title' => 'Order Processing & SLA',
            'slug' => 'order-management',
            'description' => 'Strict timelines for order processing to avoid cancellation rates.',
            'articles' => [
                'order-lifecycle-sla' => [
                     'title' => 'Order Lifecycle & SLAs',
                     'content' => '
                        <h3>Standard Operating Procedure (SOP)</h3>
                        <ol>
                            <li><strong>Order Received:</strong> Vendor receives notification immediately.</li>
                            <li><strong>Acceptance (SLA: 4 Hours):</strong> Vendor must "Accept" the order to confirm stock.</li>
                            <li><strong>Packing:</strong> Item must be packed according to medical shipping standards.</li>
                            <li><strong>Ready to Ship (SLA: 24 Hours):</strong> Vendor must mark item as "Ready" and generate AWB.</li>
                            <li><strong>Handover:</strong> Courier picks up the item within the scheduled window.</li>
                        </ol>

                        <h3 class="text-danger mt-4">Performance Metrics & Penalties</h3>
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Metric</th>
                                    <th>Target</th>
                                    <th>Penalty for Failure</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Late Dispatch Rate (LDR)</td>
                                    <td>< 4%</td>
                                    <td>Listing Suppression (Buy Box Loss)</td>
                                </tr>
                                <tr>
                                    <td>Pre-fulfillment Cancel Rate</td>
                                    <td>< 2.5%</td>
                                    <td>Account Suspension Risk</td>
                                </tr>
                                <tr>
                                    <td>Valid Tracking Rate (VTR)</td>
                                    <td>> 95%</td>
                                    <td>Category Restriction</td>
                                </tr>
                            </tbody>
                        </table>
                     '
                ]
            ]
        ],
         'shipping' => [
            'icon' => 'las la-shipping-fast',
            'title' => 'Logistics & Fulfillment Policies',
            'slug' => 'shipping',
            'description' => 'FBV vs FBP rules, lost package liability, and packaging matrix.',
            'articles' => [
                'fulfillment-options' => [
                     'title' => 'Fulfillment Models (Rules)',
                     'content' => '
                        <h3>1. Fulfilled by Vendor (FBV)</h3>
                        <p>You store, pack, and ship. Best for large equipment or low-rotation items.</p>
                        <ul>
                            <li><strong>Role:</strong> Vendor owns the "Last Mile" handover to the aggression partner.</li>
                            <li><strong>Risk:</strong> Vendor is 100% liable for late shipments.</li>
                        </ul>
                        <h3>2. Fulfilled by Phyzioline (FBP)</h3>
                        <p>You send stock to our warehouse. We handle everything.</p>
                        <ul>
                            <li><strong>Benefits:</strong> "Prime" badging, faster delivery, Phyzioline handles customer service.</li>
                            <li><strong>Fees:</strong> Storage Fee + Pick & Pack Fee apply.</li>
                        </ul>
                     '
                ],
                'packaging-guidelines' => [
                     'title' => 'Packaging Matrix & Liability',
                     'content' => '
                         <h3>Packaging Standards</h3>
                         <p>Improper packaging that leads to damage will result in <strong>claim rejection</strong>.</p>
                         <ul>
                             <li><strong>Liquids/Gels:</strong> Must be double-sealed (cap seal + poly bag) to prevent leakage.</li>
                             <li><strong>Electronics:</strong> Must have at least 2 inches of bubble wrap/padding on all sides.</li>
                             <li><strong>Heavy Items (>20kg):</strong> Must be palletized or strapped securely.</li>
                         </ul>
                         
                         <h3>Lost/Damaged Liability Matrix</h3>
                         <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Scenario</th>
                                    <th>Liable Party</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Item damaged due to poor packing</td>
                                    <td><strong>Vendor</strong></td>
                                </tr>
                                <tr>
                                    <td>Item lost by courier (scanned at pickup)</td>
                                    <td><strong>Phyzioline / Carrier</strong></td>
                                </tr>
                                <tr>
                                    <td>Customer claims "Item not received" (POD Signed)</td>
                                    <td><strong>Investigation Required</strong></td>
                                </tr>
                            </tbody>
                        </table>
                     '
                ]
            ]
        ],
        'payments' => [
            'icon' => 'las la-wallet',
            'title' => 'Financial Policies',
            'slug' => 'payments',
            'description' => 'Payout cycles, withholding amounts, and VAT handling.',
            'articles' => [
                'payout-policy' => [
                     'title' => 'Payout Policy & Holds',
                     'content' => '
                        <h3>Payout Cycle</h3>
                        <p>Payouts are generated on a <strong>Weekly</strong> basis (every Thursday) for orders "Completed" (Delivered + Return Window Passed).</p>
                        
                        <h3 class="text-danger">Account Level Reserve (ALR)</h3>
                        <p>To cover potential returns or chargebacks, Phyzioline may apply an ALR:</p>
                        <ul>
                            <li><strong>New Vendors (First 90 days):</strong> 7-day rolling reserve.</li>
                            <li><strong>High Risk Vendors (High Return Rate):</strong> 14-day rolling reserve.</li>
                        </ul>

                        <h3>VAT & Invoicing</h3>
                        <ul>
                            <li>Vendors must issue a tax invoice for every order.</li>
                            <li>Phyzioline deducts commission fees inclusive of VAT.</li>
                            <li>Failure to upload a tax invoice within 48 hours is a <strong>compliance violation</strong>.</li>
                        </ul>
                     '
                ]
            ]
        ],
        'returns' => [
            'icon' => 'las la-undo',
            'title' => 'Returns & Disputes',
            'slug' => 'returns',
            'description' => 'Handling returns, contesting claims, and hygiene exceptions.',
             'articles' => [
                 'return-policy-detailed' => [
                     'title' => 'Return Policy & Exceptions',
                     'content' => '
                        <h3>Standard Return Window</h3>
                        <p>Customers have <strong>14 days</strong> (or 30 days for factory defects) to return items.</p>
                        
                        <h3>Non-Returnable Items (Hygiene Rules)</h3>
                        <p>For health and safety, the following cannot be returned if opened:</p>
                        <ul>
                            <li>Electrodes and Gel pads.</li>
                            <li>Compression garments (worn).</li>
                            <li>Creams, Lotions, and Oils.</li>
                            <li>Respiratory devices (Nebulizers, Spirometers).</li>
                        </ul>
                        
                        <h3>Dispute Resolution</h3>
                        <p>If a vendor receives a return that is damaged or used by the customer:</p>
                        <ol>
                            <li><strong>Do NOT accept</strong> the shipment if visibly damaged.</li>
                            <li><strong>Photo Evidence:</strong> Upload photos of the item within 48 hours of receipt via the dispute portal.</li>
                            <li><strong>Arbitration:</strong> Phyzioline Team will review and may offer a partial refund (Restocking Fee) to the vendor.</li>
                        </ol>
                     '
                ]
            ]
        ],
        'compliance' => [
            'icon' => 'las la-shield-alt',
            'title' => 'Medical Compliance & Safety',
            'slug' => 'compliance',
            'description' => 'MOH registration, device traceability, and recalls.',
             'articles' => [
                 'medical-compliance' => [
                     'title' => 'Medical Device Compliance',
                     'content' => '
                        <h3>Registration Requirements</h3>
                        <p>All medical devices sold must comply with local regulations (MOH / SFDA / DHA).</p>
                        <ul>
                            <li><strong>Import License:</strong> Items manufactured outside the country must have valid import permits.</li>
                            <li><strong>AR (Authorized Representative):</strong> Vendors must be authorized agents for the brands they sell.</li>
                        </ul>

                        <h3 class="text-danger">Prohibited Actions</h3>
                        <ul>
                            <li>Selling devices with <strong>expired calibration</strong>.</li>
                            <li>Selling <strong>"Professional Use Only"</strong> devices to home users without verifying credentials.</li>
                        </ul>

                        <h3>Recall Protocol</h3>
                        <p>In the event of a manufacturer recall:</p>
                        <ol>
                            <li>Vendor must notify Phyzioline Compliance Team immediately (within 4 hours).</li>
                            <li>Phyzioline will freeze all inventory and notify affected customers.</li>
                            <li>Vendor bears all costs for reverse logistics and replacements.</li>
                        </ol>
                     '
                ]
            ]
        ],
         'support' => [
            'icon' => 'las la-headset',
            'title' => 'Help & Support',
            'slug' => 'support',
            'description' => 'Contact channels and SLA targets.',
             'articles' => [
                 'contact-channels' => [
                     'title' => 'Support Channels & SLA',
                     'content' => '
                        <h3>Support Channels</h3>
                        <ul>
                            <li><strong>Help Center:</strong> (This Knowledge Base)</li>
                            <li><strong>Email Support:</strong> support@phyzioline.com</li>
                            <li><strong>Vendor Ticket System:</strong> Inside Vendor Dashboard</li>
                        </ul>
                        <h3>SLA Targets</h3>
                        <ul>
                            <li><strong>General Inquiry:</strong> 24–48 hours</li>
                            <li><strong>Vendor Issues:</strong> 12–24 hours</li>
                            <li><strong>Critical Medical Issues:</strong> Immediate Escalation</li>
                        </ul>
                     '
                ]
            ]
        ],
        'how-to-use' => [
            'icon' => 'las la-book-open',
            'title' => 'How to Use Phyzioline',
            'slug' => 'how-to-use',
            'description' => 'Complete guides for using all features in the Phyzioline clinic management system.',
            'articles' => [
                'staff-status-management' => [
                    'title' => 'Staff Status Management Guide',
                    'content' => '
                        <h3>Overview</h3>
                        <p>The staff status system in Phyzioline manages whether staff members are <strong>Active</strong> or <strong>Inactive</strong> in your clinic. This allows you to temporarily deactivate staff without permanently deleting them.</p>
                        
                        <h3>How Staff Status Works</h3>
                        <h4>Status Types:</h4>
                        <ul>
                            <li>✅ <strong>Active</strong> - Staff member is currently working and can access the system</li>
                            <li>❌ <strong>Inactive</strong> - Staff member is temporarily deactivated (on leave, terminated, etc.)</li>
                        </ul>
                        
                        <h4>Where Status is Stored:</h4>
                        <ul>
                            <li>Status is managed in the <code>clinic_staff</code> table via the <code>is_active</code> field</li>
                            <li>This is separate from the <code>users</code> table to allow multi-clinic scenarios</li>
                        </ul>
                        
                        <h3>Where to Manage Staff Status</h3>
                        <p><strong>Location:</strong> Staff Directory Page<br>
                        <strong>URL:</strong> <code>/clinic/staff</code><br>
                        <strong>Navigation:</strong> Sidebar → Staff</p>
                        
                        <h4>Features Available:</h4>
                        <ol>
                            <li><strong>View All Staff</strong> - See both active and inactive staff members</li>
                            <li><strong>Toggle Status</strong> - Click the status toggle button (🟡 Deactivate / 🟢 Activate)</li>
                            <li><strong>Edit Staff</strong> - Click the Edit (✏️) button to modify staff details</li>
                            <li><strong>Delete Staff</strong> - Click the Delete (🗑️) button to permanently remove staff</li>
                        </ol>
                        
                        <h3>How to Activate/Deactivate Staff</h3>
                        <h4>Method 1: Using Toggle Button (Recommended)</h4>
                        <ol>
                            <li>Go to <strong>Staff Directory</strong> (<code>/clinic/staff</code>)</li>
                            <li>Find the staff member you want to activate/deactivate</li>
                            <li>Click the status button:
                                <ul>
                                    <li><strong>🟡 Yellow button</strong> = Currently Active (click to deactivate)</li>
                                    <li><strong>🟢 Green button</strong> = Currently Inactive (click to activate)</li>
                                </ul>
                            </li>
                            <li>Confirm the action in the dialog</li>
                            <li>Status updates immediately</li>
                        </ol>
                        
                        <h3>What Happens When You Change Status</h3>
                        <h4>When Activating Staff:</h4>
                        <ul>
                            <li>✅ <code>is_active</code> set to <code>true</code></li>
                            <li>✅ <code>terminated_date</code> cleared (set to <code>null</code>)</li>
                            <li>✅ Staff member appears in active staff lists</li>
                            <li>✅ Staff can log in and access the system</li>
                        </ul>
                        
                        <h4>When Deactivating Staff:</h4>
                        <ul>
                            <li>❌ <code>is_active</code> set to <code>false</code></li>
                            <li>❌ <code>terminated_date</code> set to current date</li>
                            <li>❌ Staff member removed from active staff lists</li>
                            <li>❌ Staff cannot log in (if authentication checks <code>is_active</code>)</li>
                        </ul>
                    '
                ],
                'dashboard-overview' => [
                    'title' => 'Dashboard Overview',
                    'content' => '
                        <h3>Overview</h3>
                        <p>The Dashboard is your central command center in Phyzioline. It provides a comprehensive overview of your clinic\'s operations, key metrics, and quick access to important features.</p>
                        
                        <h3>Accessing the Dashboard</h3>
                        <p><strong>URL:</strong> <code>/clinic/dashboard</code><br>
                        <strong>Navigation:</strong> Sidebar → Dashboard (first item)</p>
                        
                        <h3>Dashboard Components</h3>
                        <h4>1. Key Metrics Cards</h4>
                        <ul>
                            <li><strong>Total Patients</strong> - Number of registered patients</li>
                            <li><strong>Today\'s Appointments</strong> - Scheduled appointments for today</li>
                            <li><strong>Active Episodes</strong> - Ongoing treatment episodes</li>
                            <li><strong>Monthly Revenue</strong> - Financial summary for the month</li>
                        </ul>
                        
                        <h4>2. Recent Activity</h4>
                        <ul>
                            <li>Latest appointments scheduled</li>
                            <li>Recent patient registrations</li>
                            <li>New clinical notes created</li>
                            <li>Staff activity updates</li>
                        </ul>
                        
                        <h4>3. Quick Actions</h4>
                        <ul>
                            <li>Create new appointment</li>
                            <li>Register new patient</li>
                            <li>Add clinical note</li>
                            <li>View pending tasks</li>
                        </ul>
                        
                        <h4>4. Charts & Analytics</h4>
                        <ul>
                            <li>Appointment trends</li>
                            <li>Revenue charts</li>
                            <li>Patient demographics</li>
                            <li>Treatment outcomes</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Check dashboard daily for important updates</li>
                            <li>Use quick actions for common tasks</li>
                            <li>Review metrics weekly to track clinic performance</li>
                        </ul>
                    '
                ],
                'specialty-selection' => [
                    'title' => 'Specialty Selection Guide',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Selecting your clinic\'s specialty is the first critical step in setting up your Phyzioline account. Your specialty determines which clinical modules, templates, and features are available to you.</p>
                        
                        <h3>Available Specialties</h3>
                        <ul>
                            <li><strong>Pediatric</strong> - Children\'s physical therapy</li>
                            <li><strong>Neurological</strong> - Neurological rehabilitation</li>
                            <li><strong>Orthopedic</strong> - Musculoskeletal conditions</li>
                            <li><strong>Sports</strong> - Sports injury rehabilitation</li>
                            <li><strong>Geriatric</strong> - Elderly care and rehabilitation</li>
                            <li><strong>Women Health</strong> - Women\'s health physical therapy</li>
                            <li><strong>Cardiopulmonary</strong> - Cardiac and pulmonary rehabilitation</li>
                        </ul>
                        
                        <h3>How to Select Specialty</h3>
                        <ol>
                            <li>Navigate to <strong>Specialty Selection</strong> from the sidebar</li>
                            <li>Review available specialties and their features</li>
                            <li>Select your <strong>Primary Specialty</strong> (required)</li>
                            <li>Optionally select <strong>Additional Specialties</strong> if your clinic offers multiple services</li>
                            <li>Click <strong>Save</strong> to activate your specialty modules</li>
                        </ol>
                        
                        <h3>What Happens After Selection</h3>
                        <ul>
                            <li>✅ Specialty-specific clinical templates become available</li>
                            <li>✅ Relevant assessment forms are activated</li>
                            <li>✅ Treatment protocols for your specialty are enabled</li>
                            <li>✅ Specialty-specific reporting features unlock</li>
                        </ul>
                        
                        <h3>Changing Your Specialty</h3>
                        <p>You can change your specialty at any time from <strong>Profile & Settings</strong>. Note that changing specialty may affect:</p>
                        <ul>
                            <li>Available clinical templates</li>
                            <li>Assessment forms</li>
                            <li>Treatment protocols</li>
                        </ul>
                        
                        <div class="alert alert-warning">
                            <strong>Note:</strong> Historical data (past appointments, notes) will remain intact when changing specialties.
                        </div>
                    '
                ],
                'profile-settings' => [
                    'title' => 'Profile & Settings Management',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Your clinic profile and settings allow you to customize your clinic information, configure system preferences, and manage your account settings.</p>
                        
                        <h3>Accessing Profile & Settings</h3>
                        <p><strong>URL:</strong> <code>/clinic/profile</code><br>
                        <strong>Navigation:</strong> Sidebar → Profile & Settings</p>
                        
                        <h3>Profile Information</h3>
                        <h4>Basic Information</h4>
                        <ul>
                            <li><strong>Clinic Name</strong> - Your clinic\'s official name</li>
                            <li><strong>Address</strong> - Physical location</li>
                            <li><strong>Phone Number</strong> - Contact number</li>
                            <li><strong>Email</strong> - Primary email address</li>
                            <li><strong>Website</strong> - Clinic website (optional)</li>
                        </ul>
                        
                        <h4>Specialty Settings</h4>
                        <ul>
                            <li>Primary specialty selection</li>
                            <li>Additional specialties</li>
                            <li>Specialty-specific configurations</li>
                        </ul>
                        
                        <h3>System Settings</h3>
                        <h4>Appointment Settings</h4>
                        <ul>
                            <li>Default appointment duration</li>
                            <li>Working hours</li>
                            <li>Booking rules</li>
                            <li>Cancellation policies</li>
                        </ul>
                        
                        <h4>Notification Preferences</h4>
                        <ul>
                            <li>Email notifications</li>
                            <li>SMS notifications</li>
                            <li>In-app notifications</li>
                        </ul>
                        
                        <h4>Billing Settings</h4>
                        <ul>
                            <li>Default payment methods</li>
                            <li>Invoice settings</li>
                            <li>Tax configuration</li>
                        </ul>
                        
                        <h3>How to Update Profile</h3>
                        <ol>
                            <li>Go to <strong>Profile & Settings</strong></li>
                            <li>Click <strong>Edit</strong> on the section you want to update</li>
                            <li>Make your changes</li>
                            <li>Click <strong>Save</strong> to apply changes</li>
                        </ol>
                    '
                ],
                'patient-management' => [
                    'title' => 'Patient Management Guide',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Patient Management is the core of your clinic operations. This guide covers how to register, manage, and track your patients in Phyzioline.</p>
                        
                        <h3>Accessing Patient Management</h3>
                        <p><strong>URL:</strong> <code>/clinic/patients</code><br>
                        <strong>Navigation:</strong> Sidebar → Patients</p>
                        
                        <h3>Registering a New Patient</h3>
                        <ol>
                            <li>Click <strong>Add New Patient</strong> button</li>
                            <li>Fill in required information:
                                <ul>
                                    <li>Full Name</li>
                                    <li>Date of Birth</li>
                                    <li>Gender</li>
                                    <li>Phone Number</li>
                                    <li>Email (optional)</li>
                                    <li>Address</li>
                                    <li>Emergency Contact</li>
                                </ul>
                            </li>
                            <li>Add medical history (if available)</li>
                            <li>Upload documents (ID, insurance card, etc.)</li>
                            <li>Click <strong>Save</strong> to create patient record</li>
                        </ol>
                        
                        <h3>Patient List Features</h3>
                        <h4>Search & Filter</h4>
                        <ul>
                            <li>Search by name, phone, or ID</li>
                            <li>Filter by status (Active, Inactive)</li>
                            <li>Filter by specialty</li>
                            <li>Sort by registration date, name, etc.</li>
                        </ul>
                        
                        <h4>Patient Actions</h4>
                        <ul>
                            <li><strong>View</strong> - See full patient profile</li>
                            <li><strong>Edit</strong> - Update patient information</li>
                            <li><strong>Create Appointment</strong> - Schedule appointment directly</li>
                            <li><strong>Create Episode</strong> - Start new treatment episode</li>
                            <li><strong>View History</strong> - See all past appointments and notes</li>
                        </ul>
                        
                        <h3>Patient Profile</h3>
                        <h4>Overview Tab</h4>
                        <ul>
                            <li>Basic information</li>
                            <li>Contact details</li>
                            <li>Insurance information</li>
                            <li>Quick stats (total visits, active episodes)</li>
                        </ul>
                        
                        <h4>Medical History Tab</h4>
                        <ul>
                            <li>Past medical conditions</li>
                            <li>Allergies</li>
                            <li>Medications</li>
                            <li>Previous surgeries</li>
                        </ul>
                        
                        <h4>Appointments Tab</h4>
                        <ul>
                            <li>Upcoming appointments</li>
                            <li>Past appointments</li>
                            <li>Appointment history</li>
                        </ul>
                        
                        <h4>Clinical Notes Tab</h4>
                        <ul>
                            <li>All clinical notes</li>
                            <li>SOAP notes</li>
                            <li>Assessment reports</li>
                            <li>Treatment plans</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Always verify patient information before saving</li>
                            <li>Keep medical history updated</li>
                            <li>Upload important documents for easy access</li>
                            <li>Use search to quickly find patients</li>
                        </ul>
                    '
                ],
                'appointment-scheduling' => [
                    'title' => 'Appointment Scheduling Guide',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Appointment scheduling in Phyzioline allows you to manage your clinic\'s calendar, schedule patient visits, and track appointment statuses.</p>
                        
                        <h3>Accessing Appointments</h3>
                        <p><strong>URL:</strong> <code>/clinic/appointments</code><br>
                        <strong>Navigation:</strong> Sidebar → Appointments</p>
                        
                        <h3>Creating a New Appointment</h3>
                        <ol>
                            <li>Click <strong>Schedule Appointment</strong> button</li>
                            <li>Select or search for a patient</li>
                            <li>Choose a therapist/doctor</li>
                            <li>Select date and time</li>
                            <li>Choose appointment type (Initial, Follow-up, Evaluation, etc.)</li>
                            <li>Select service/treatment</li>
                            <li>Add notes (optional)</li>
                            <li>Click <strong>Save</strong> to create appointment</li>
                        </ol>
                        
                        <h3>Appointment Views</h3>
                        <h4>Calendar View</h4>
                        <ul>
                            <li>Monthly calendar with all appointments</li>
                            <li>Color-coded by status</li>
                            <li>Click on appointment to view details</li>
                            <li>Drag and drop to reschedule</li>
                        </ul>
                        
                        <h4>List View</h4>
                        <ul>
                            <li>Table format with all appointments</li>
                            <li>Sortable columns</li>
                            <li>Filter by date range, status, therapist</li>
                            <li>Quick actions (Edit, Cancel, Complete)</li>
                        </ul>
                        
                        <h3>Appointment Statuses</h3>
                        <ul>
                            <li><strong>Scheduled</strong> - Appointment is confirmed</li>
                            <li><strong>Confirmed</strong> - Patient confirmed attendance</li>
                            <li><strong>In Progress</strong> - Currently happening</li>
                            <li><strong>Completed</strong> - Appointment finished</li>
                            <li><strong>Cancelled</strong> - Appointment cancelled</li>
                            <li><strong>No Show</strong> - Patient didn\'t attend</li>
                        </ul>
                        
                        <h3>Managing Appointments</h3>
                        <h4>Rescheduling</h4>
                        <ol>
                            <li>Click on the appointment</li>
                            <li>Click <strong>Reschedule</strong></li>
                            <li>Select new date and time</li>
                            <li>Save changes</li>
                        </ol>
                        
                        <h4>Cancelling</h4>
                        <ol>
                            <li>Click on the appointment</li>
                            <li>Click <strong>Cancel</strong></li>
                            <li>Select cancellation reason</li>
                            <li>Confirm cancellation</li>
                        </ol>
                        
                        <h4>Completing</h4>
                        <ol>
                            <li>After appointment ends, click <strong>Complete</strong></li>
                            <li>Add completion notes</li>
                            <li>Mark as completed</li>
                            <li>Optionally create clinical note</li>
                        </ol>
                        
                        <h3>Recurring Appointments</h3>
                        <p>You can create recurring appointments for patients who need regular sessions:</p>
                        <ol>
                            <li>When creating appointment, select <strong>Recurring</strong></li>
                            <li>Choose frequency (Daily, Weekly, Bi-weekly, Monthly)</li>
                            <li>Set end date or number of occurrences</li>
                            <li>Save to create series</li>
                        </ol>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Schedule appointments with buffer time between sessions</li>
                            <li>Send reminders to patients before appointments</li>
                            <li>Update status promptly when appointments are completed</li>
                            <li>Review calendar daily for upcoming appointments</li>
                        </ul>
                    '
                ],
                'clinical-notes' => [
                    'title' => 'Clinical Notes & Documentation',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Clinical Notes allow you to document patient visits, assessments, treatments, and outcomes. Phyzioline supports multiple note types including SOAP notes, evaluations, progress notes, and discharge summaries.</p>
                        
                        <h3>Accessing Clinical Notes</h3>
                        <p><strong>URL:</strong> <code>/clinic/clinical-notes</code><br>
                        <strong>Navigation:</strong> Sidebar → Clinical Notes</p>
                        
                        <h3>Creating a Clinical Note</h3>
                        <ol>
                            <li>Click <strong>Create New Note</strong></li>
                            <li>Select patient</li>
                            <li>Choose note type:
                                <ul>
                                    <li><strong>SOAP</strong> - Subjective, Objective, Assessment, Plan</li>
                                    <li><strong>Evaluation</strong> - Initial patient evaluation</li>
                                    <li><strong>Progress</strong> - Progress update</li>
                                    <li><strong>Discharge</strong> - Discharge summary</li>
                                    <li><strong>Re-evaluation</strong> - Follow-up evaluation</li>
                                </ul>
                            </li>
                            <li>Select specialty template (if applicable)</li>
                            <li>Fill in the note sections</li>
                            <li>Add diagnosis codes (ICD-10)</li>
                            <li>Add procedure codes (CPT)</li>
                            <li>Review and save</li>
                        </ol>
                        
                        <h3>SOAP Note Structure</h3>
                        <h4>Subjective (S)</h4>
                        <ul>
                            <li>Patient-reported symptoms</li>
                            <li>Chief complaint</li>
                            <li>History of present illness</li>
                            <li>Patient\'s perspective</li>
                        </ul>
                        
                        <h4>Objective (O)</h4>
                        <ul>
                            <li>Physical examination findings</li>
                            <li>Measurements (ROM, strength, etc.)</li>
                            <li>Objective observations</li>
                            <li>Test results</li>
                        </ul>
                        
                        <h4>Assessment (A)</h4>
                        <ul>
                            <li>Clinical interpretation</li>
                            <li>Diagnosis</li>
                            <li>Progress assessment</li>
                            <li>Clinical impression</li>
                        </ul>
                        
                        <h4>Plan (P)</h4>
                        <ul>
                            <li>Treatment plan</li>
                            <li>Goals</li>
                            <li>Next steps</li>
                            <li>Follow-up instructions</li>
                        </ul>
                        
                        <h3>Voice-to-Text Feature</h3>
                        <p>You can use voice-to-text to quickly dictate notes:</p>
                        <ol>
                            <li>Click the microphone icon</li>
                            <li>Start speaking</li>
                            <li>Text will appear in real-time</li>
                            <li>Review and edit as needed</li>
                        </ol>
                        
                        <h3>Note Statuses</h3>
                        <ul>
                            <li><strong>Draft</strong> - Being written, not finalized</li>
                            <li><strong>In Review</strong> - Under review</li>
                            <li><strong>Signed</strong> - Finalized and signed</li>
                            <li><strong>Locked</strong> - Cannot be edited</li>
                        </ul>
                        
                        <h3>Signing Notes</h3>
                        <ol>
                            <li>Complete the note</li>
                            <li>Review all sections</li>
                            <li>Click <strong>Sign Note</strong></li>
                            <li>Confirm signature</li>
                            <li>Note becomes locked and official</li>
                        </ol>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Write notes immediately after patient visit</li>
                            <li>Be specific and detailed</li>
                            <li>Use proper medical terminology</li>
                            <li>Include all relevant codes</li>
                            <li>Sign notes promptly to finalize</li>
                        </ul>
                    '
                ],
                'episode-management' => [
                    'title' => 'Clinical Episodes Guide',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Clinical Episodes (Episodes of Care) allow you to track a patient\'s treatment journey from initial evaluation through discharge. Each episode represents a distinct period of care for a specific condition or treatment goal.</p>
                        
                        <h3>Accessing Episodes</h3>
                        <p><strong>URL:</strong> <code>/clinic/episodes</code><br>
                        <strong>Navigation:</strong> Sidebar → Clinical Episodes</p>
                        
                        <h3>Creating a New Episode</h3>
                        <ol>
                            <li>Click <strong>Create New Episode</strong></li>
                            <li>Select patient</li>
                            <li>Enter episode details:
                                <ul>
                                    <li>Episode name/description</li>
                                    <li>Primary diagnosis</li>
                                    <li>Start date</li>
                                    <li>Expected duration</li>
                                    <li>Treatment goals</li>
                                </ul>
                            </li>
                            <li>Add initial assessment</li>
                            <li>Create treatment plan</li>
                            <li>Save episode</li>
                        </ol>
                        
                        <h3>Episode Statuses</h3>
                        <ul>
                            <li><strong>Active</strong> - Currently ongoing treatment</li>
                            <li><strong>On Hold</strong> - Temporarily paused</li>
                            <li><strong>Completed</strong> - Successfully finished</li>
                            <li><strong>Discharged</strong> - Patient discharged</li>
                            <li><strong>Cancelled</strong> - Episode cancelled</li>
                        </ul>
                        
                        <h3>Episode Components</h3>
                        <h4>Assessments</h4>
                        <ul>
                            <li>Initial evaluation</li>
                            <li>Progress assessments</li>
                            <li>Re-evaluations</li>
                            <li>Discharge assessment</li>
                        </ul>
                        
                        <h4>Treatment Plan</h4>
                        <ul>
                            <li>Treatment goals</li>
                            <li>Interventions</li>
                            <li>Frequency and duration</li>
                            <li>Expected outcomes</li>
                        </ul>
                        
                        <h4>Appointments</h4>
                        <ul>
                            <li>All appointments linked to episode</li>
                            <li>Treatment sessions</li>
                            <li>Progress tracking</li>
                        </ul>
                        
                        <h4>Clinical Notes</h4>
                        <ul>
                            <li>All notes related to episode</li>
                            <li>SOAP notes</li>
                            <li>Progress documentation</li>
                        </ul>
                        
                        <h3>Closing an Episode</h3>
                        <ol>
                            <li>Go to episode details</li>
                            <li>Click <strong>Close Episode</strong></li>
                            <li>Add discharge summary</li>
                            <li>Select closure reason</li>
                            <li>Add final outcomes</li>
                            <li>Confirm closure</li>
                        </ol>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Create episode at start of new treatment</li>
                            <li>Link all related appointments and notes</li>
                            <li>Update episode status regularly</li>
                            <li>Close episode when treatment goals are met</li>
                        </ul>
                    '
                ],
                'treatment-programs' => [
                    'title' => 'Treatment Programs Guide',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Treatment Programs allow you to create standardized treatment protocols that can be applied to multiple patients. This helps ensure consistency and efficiency in delivering care.</p>
                        
                        <h3>Accessing Treatment Programs</h3>
                        <p><strong>URL:</strong> <code>/clinic/treatment-programs</code><br>
                        <strong>Navigation:</strong> Sidebar → Treatment Programs</p>
                        
                        <h3>Creating a Treatment Program</h3>
                        <ol>
                            <li>Click <strong>Create Program</strong></li>
                            <li>Enter program details:
                                <ul>
                                    <li>Program name</li>
                                    <li>Description</li>
                                    <li>Target condition/diagnosis</li>
                                    <li>Specialty</li>
                                    <li>Duration</li>
                                </ul>
                            </li>
                            <li>Add treatment phases:
                                <ul>
                                    <li>Phase 1: Acute phase</li>
                                    <li>Phase 2: Recovery phase</li>
                                    <li>Phase 3: Maintenance phase</li>
                                </ul>
                            </li>
                            <li>Define exercises and interventions for each phase</li>
                            <li>Set goals and milestones</li>
                            <li>Save program</li>
                        </ol>
                        
                        <h3>Applying Program to Patient</h3>
                        <ol>
                            <li>Go to patient profile</li>
                            <li>Navigate to Treatment Programs</li>
                            <li>Click <strong>Assign Program</strong></li>
                            <li>Select program from list</li>
                            <li>Customize if needed</li>
                            <li>Link to episode</li>
                            <li>Save assignment</li>
                        </ol>
                        
                        <h3>Program Templates</h3>
                        <p>You can create program templates for common conditions:</p>
                        <ul>
                            <li>Post-surgical rehabilitation</li>
                            <li>Sports injury recovery</li>
                            <li>Chronic pain management</li>
                            <li>Balance and fall prevention</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Create templates for frequently used programs</li>
                            <li>Customize programs to individual patient needs</li>
                            <li>Track progress through program phases</li>
                            <li>Update programs based on outcomes</li>
                        </ul>
                    '
                ],
                'billing-management' => [
                    'title' => 'Billing & Financial Management',
                    'content' => '
                        <h3>Overview</h3>
                        <p>The Billing module helps you manage invoices, payments, insurance claims, and financial reporting for your clinic.</p>
                        
                        <h3>Accessing Billing</h3>
                        <p><strong>URL:</strong> <code>/clinic/billing</code><br>
                        <strong>Navigation:</strong> Sidebar → Billing</p>
                        
                        <h3>Creating an Invoice</h3>
                        <ol>
                            <li>Click <strong>Create Invoice</strong></li>
                            <li>Select patient</li>
                            <li>Add line items:
                                <ul>
                                    <li>Services provided</li>
                                    <li>Procedures</li>
                                    <li>Products/equipment</li>
                                </ul>
                            </li>
                            <li>Apply discounts (if any)</li>
                            <li>Select payment method</li>
                            <li>Generate invoice</li>
                        </ol>
                        
                        <h3>Payment Processing</h3>
                        <h4>Recording Payment</h4>
                        <ol>
                            <li>Go to invoice</li>
                            <li>Click <strong>Record Payment</strong></li>
                            <li>Enter payment amount</li>
                            <li>Select payment method (Cash, Card, Insurance, etc.)</li>
                            <li>Add payment reference</li>
                            <li>Save payment</li>
                        </ol>
                        
                        <h3>Insurance Claims</h3>
                        <h4>Submitting Claim</h4>
                        <ol>
                            <li>Create invoice for insured patient</li>
                            <li>Select insurance provider</li>
                            <li>Add required documentation</li>
                            <li>Submit claim</li>
                            <li>Track claim status</li>
                        </ol>
                        
                        <h3>Financial Reports</h3>
                        <ul>
                            <li><strong>Revenue Report</strong> - Total revenue by period</li>
                            <li><strong>Outstanding Invoices</strong> - Unpaid invoices</li>
                            <li><strong>Payment History</strong> - All payments received</li>
                            <li><strong>Insurance Claims</strong> - Claim status and payments</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Create invoices immediately after service</li>
                            <li>Follow up on outstanding payments</li>
                            <li>Submit insurance claims promptly</li>
                            <li>Review financial reports regularly</li>
                        </ul>
                    '
                ],
                'analytics-reports' => [
                    'title' => 'Analytics & Reports',
                    'content' => '
                        <h3>Overview</h3>
                        <p>The Analytics section provides comprehensive insights into your clinic\'s performance, patient outcomes, and operational metrics.</p>
                        
                        <h3>Accessing Analytics</h3>
                        <p><strong>URL:</strong> <code>/clinic/analytics</code><br>
                        <strong>Navigation:</strong> Sidebar → Analytics</p>
                        
                        <h3>Available Reports</h3>
                        <h4>Patient Reports</h4>
                        <ul>
                            <li>New patient registrations</li>
                            <li>Patient demographics</li>
                            <li>Patient retention rates</li>
                            <li>Treatment outcomes</li>
                        </ul>
                        
                        <h4>Appointment Reports</h4>
                        <ul>
                            <li>Appointment volume</li>
                            <li>No-show rates</li>
                            <li>Therapist utilization</li>
                            <li>Appointment types distribution</li>
                        </ul>
                        
                        <h4>Financial Reports</h4>
                        <ul>
                            <li>Revenue trends</li>
                            <li>Payment methods</li>
                            <li>Outstanding balances</li>
                            <li>Insurance claim status</li>
                        </ul>
                        
                        <h4>Clinical Reports</h4>
                        <ul>
                            <li>Treatment outcomes</li>
                            <li>Episode completion rates</li>
                            <li>Common diagnoses</li>
                            <li>Treatment effectiveness</li>
                        </ul>
                        
                        <h3>Generating Reports</h3>
                        <ol>
                            <li>Select report type</li>
                            <li>Choose date range</li>
                            <li>Apply filters (if needed)</li>
                            <li>Click <strong>Generate Report</strong></li>
                            <li>Export to PDF/Excel (optional)</li>
                        </ol>
                        
                        <h3>Dashboard Charts</h3>
                        <ul>
                            <li>Revenue charts</li>
                            <li>Appointment trends</li>
                            <li>Patient growth</li>
                            <li>Treatment outcomes</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Review reports weekly</li>
                            <li>Track key performance indicators</li>
                            <li>Use data to improve operations</li>
                            <li>Export important reports for records</li>
                        </ul>
                    '
                ],
                'staff-management' => [
                    'title' => 'Staff Management Guide',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Staff Management allows you to add, manage, and organize your clinic staff members including therapists, assistants, and administrative personnel.</p>
                        
                        <h3>Accessing Staff Management</h3>
                        <p><strong>URL:</strong> <code>/clinic/staff</code><br>
                        <strong>Navigation:</strong> Sidebar → Staff</p>
                        
                        <h3>Adding New Staff</h3>
                        <ol>
                            <li>Click <strong>Add Staff Member</strong></li>
                            <li>Fill in information:
                                <ul>
                                    <li>Full Name</li>
                                    <li>Email</li>
                                    <li>Phone Number</li>
                                    <li>Role (Therapist, Assistant, Admin, etc.)</li>
                                    <li>Specialty</li>
                                    <li>License Number (if applicable)</li>
                                </ul>
                            </li>
                            <li>Set permissions and access levels</li>
                            <li>Save staff member</li>
                        </ol>
                        
                        <h3>Staff Status Management</h3>
                        <p>See the <strong>Staff Status Management Guide</strong> for detailed information on activating and deactivating staff members.</p>
                        
                        <h3>Editing Staff</h3>
                        <ol>
                            <li>Find staff member in list</li>
                            <li>Click <strong>Edit</strong> button</li>
                            <li>Update information</li>
                            <li>Save changes</li>
                        </ol>
                        
                        <h3>Staff Roles</h3>
                        <ul>
                            <li><strong>Admin</strong> - Full system access</li>
                            <li><strong>Therapist</strong> - Clinical access</li>
                            <li><strong>Assistant</strong> - Limited access</li>
                            <li><strong>Receptionist</strong> - Appointment and patient access</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Keep staff information updated</li>
                            <li>Deactivate staff when they leave</li>
                            <li>Assign appropriate roles and permissions</li>
                            <li>Review staff list regularly</li>
                        </ul>
                    '
                ],
                'doctors-therapists' => [
                    'title' => 'Doctors & Therapists Management',
                    'content' => '
                        <h3>Overview</h3>
                        <p>The Doctors/Therapists section allows you to manage your clinical staff, assign specialties, and track their schedules and patient loads.</p>
                        
                        <h3>Accessing Doctors/Therapists</h3>
                        <p><strong>URL:</strong> <code>/clinic/doctors</code><br>
                        <strong>Navigation:</strong> Sidebar → Doctors</p>
                        
                        <h3>Adding a Therapist</h3>
                        <ol>
                            <li>Click <strong>Add Therapist</strong></li>
                            <li>Enter therapist details:
                                <ul>
                                    <li>Name</li>
                                    <li>Specialty</li>
                                    <li>License information</li>
                                    <li>Qualifications</li>
                                    <li>Contact information</li>
                                </ul>
                            </li>
                            <li>Set availability schedule</li>
                            <li>Assign to clinic</li>
                            <li>Save</li>
                        </ol>
                        
                        <h3>Therapist Profile</h3>
                        <ul>
                            <li>Personal information</li>
                            <li>Specialties and certifications</li>
                            <li>Schedule and availability</li>
                            <li>Patient assignments</li>
                            <li>Performance metrics</li>
                        </ul>
                        
                        <h3>Managing Schedules</h3>
                        <ol>
                            <li>Go to therapist profile</li>
                            <li>Click <strong>Edit Schedule</strong></li>
                            <li>Set working hours</li>
                            <li>Set days off</li>
                            <li>Save schedule</li>
                        </ol>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Keep therapist information current</li>
                            <li>Update schedules regularly</li>
                            <li>Track therapist workload</li>
                            <li>Assign patients based on specialty</li>
                        </ul>
                    '
                ],
                'services-management' => [
                    'title' => 'Services Management',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Services Management allows you to define and manage the services your clinic offers, including treatments, consultations, and procedures.</p>
                        
                        <h3>Accessing Services</h3>
                        <p><strong>URL:</strong> <code>/clinic/services</code><br>
                        <strong>Navigation:</strong> Sidebar → Services</p>
                        
                        <h3>Creating a Service</h3>
                        <ol>
                            <li>Click <strong>Add Service</strong></li>
                            <li>Enter service details:
                                <ul>
                                    <li>Service name</li>
                                    <li>Description</li>
                                    <li>Category</li>
                                    <li>Duration</li>
                                    <li>Price</li>
                                    <li>Specialty</li>
                                </ul>
                            </li>
                            <li>Set availability</li>
                            <li>Save service</li>
                        </ol>
                        
                        <h3>Service Categories</h3>
                        <ul>
                            <li>Initial Evaluation</li>
                            <li>Treatment Session</li>
                            <li>Re-evaluation</li>
                            <li>Consultation</li>
                            <li>Specialized Treatment</li>
                        </ul>
                        
                        <h3>Pricing</h3>
                        <ul>
                            <li>Set base price for service</li>
                            <li>Add insurance codes</li>
                            <li>Set different prices for different payment methods</li>
                            <li>Apply discounts if applicable</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Keep service list updated</li>
                            <li>Set accurate pricing</li>
                            <li>Link services to specialties</li>
                            <li>Review service utilization regularly</li>
                        </ul>
                    '
                ],
                'job-posting' => [
                    'title' => 'Job Posting Guide',
                    'content' => '
                        <h3>Overview</h3>
                        <p>The Job Posting feature allows you to post job openings, manage applications, and hire therapists and staff for your clinic.</p>
                        
                        <h3>Accessing Jobs</h3>
                        <p><strong>URL:</strong> <code>/clinic/jobs</code><br>
                        <strong>Navigation:</strong> Sidebar → Jobs (if available)</p>
                        
                        <h3>Posting a New Job</h3>
                        <ol>
                            <li>Click <strong>Post New Job</strong></li>
                            <li>Fill in job details:
                                <ul>
                                    <li>Job Title</li>
                                    <li>Type (Full-time, Part-time, Contract, Training)</li>
                                    <li>Location</li>
                                    <li>Description</li>
                                    <li>Urgency Level</li>
                                </ul>
                            </li>
                            <li>Select required specialties</li>
                            <li>Select required techniques/skills</li>
                            <li>Set experience requirements</li>
                            <li>Set salary information</li>
                            <li>Publish job</li>
                        </ol>
                        
                        <h3>Managing Applications</h3>
                        <ol>
                            <li>Go to job listing</li>
                            <li>Click <strong>View Applications</strong></li>
                            <li>Review applicant profiles</li>
                            <li>Accept or reject applications</li>
                            <li>Schedule interviews</li>
                        </ol>
                        
                        <h3>Job Statuses</h3>
                        <ul>
                            <li><strong>Active</strong> - Currently accepting applications</li>
                            <li><strong>Paused</strong> - Temporarily not accepting applications</li>
                            <li><strong>Closed</strong> - Position filled or cancelled</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Write clear, detailed job descriptions</li>
                            <li>Set realistic requirements</li>
                            <li>Respond to applications promptly</li>
                            <li>Update job status when filled</li>
                        </ul>
                    '
                ],
                'waiting-list' => [
                    'title' => 'Waiting List Management',
                    'content' => '
                        <h3>Overview</h3>
                        <p>The Waiting List feature helps you manage patients who are waiting for appointments when your schedule is full.</p>
                        
                        <h3>Accessing Waiting List</h3>
                        <p><strong>URL:</strong> <code>/clinic/waiting-list</code><br>
                        <strong>Navigation:</strong> Sidebar → Waiting List</p>
                        
                        <h3>Adding to Waiting List</h3>
                        <ol>
                            <li>Click <strong>Add to Waiting List</strong></li>
                            <li>Select patient</li>
                            <li>Choose preferred date/time</li>
                            <li>Select therapist preference</li>
                            <li>Add notes</li>
                            <li>Save</li>
                        </ol>
                        
                        <h3>Managing Waiting List</h3>
                        <ul>
                            <li>View all patients waiting</li>
                            <li>Sort by priority or date</li>
                            <li>Contact patients when slots open</li>
                            <li>Convert to appointment when available</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Contact patients promptly when slots open</li>
                            <li>Update waiting list regularly</li>
                            <li>Prioritize urgent cases</li>
                        </ul>
                    '
                ],
                'reception-forms' => [
                    'title' => 'Reception Forms Guide',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Reception Forms allow you to create and manage intake forms, questionnaires, and patient registration forms that can be filled out at reception or by patients.</p>
                        
                        <h3>Accessing Reception Forms</h3>
                        <p><strong>URL:</strong> <code>/clinic/reception-forms</code><br>
                        <strong>Navigation:</strong> Sidebar → Reception Forms</p>
                        
                        <h3>Creating a Form</h3>
                        <ol>
                            <li>Click <strong>Create Form</strong></li>
                            <li>Enter form name and description</li>
                            <li>Add form fields:
                                <ul>
                                    <li>Text fields</li>
                                    <li>Dropdown menus</li>
                                    <li>Checkboxes</li>
                                    <li>Radio buttons</li>
                                    <li>Date pickers</li>
                                </ul>
                            </li>
                            <li>Set field requirements</li>
                            <li>Save form</li>
                        </ol>
                        
                        <h3>Using Forms</h3>
                        <ul>
                            <li>Assign forms to patients</li>
                            <li>Forms can be filled online or in-clinic</li>
                            <li>View completed forms in patient profile</li>
                            <li>Export form data</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Create forms for common intake needs</li>
                            <li>Keep forms concise and clear</li>
                            <li>Review completed forms before appointments</li>
                        </ul>
                    '
                ],
                'insurance-claims' => [
                    'title' => 'Insurance Claims Management',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Insurance Claims Management helps you submit, track, and manage insurance claims for patient treatments.</p>
                        
                        <h3>Accessing Insurance Claims</h3>
                        <p><strong>URL:</strong> <code>/clinic/insurance-claims</code><br>
                        <strong>Navigation:</strong> Sidebar → Insurance Claims</p>
                        
                        <h3>Submitting a Claim</h3>
                        <ol>
                            <li>Select patient with insurance</li>
                            <li>Click <strong>Create Claim</strong></li>
                            <li>Enter claim details:
                                <ul>
                                    <li>Insurance provider</li>
                                    <li>Policy number</li>
                                    <li>Treatment dates</li>
                                    <li>Diagnosis codes</li>
                                    <li>Procedure codes</li>
                                </ul>
                            </li>
                            <li>Upload required documents</li>
                            <li>Submit claim</li>
                        </ol>
                        
                        <h3>Tracking Claims</h3>
                        <ul>
                            <li>View claim status (Pending, Approved, Rejected)</li>
                            <li>Track payment status</li>
                            <li>View claim history</li>
                            <li>Resubmit rejected claims</li>
                        </ul>
                        
                        <h3>Claim Statuses</h3>
                        <ul>
                            <li><strong>Pending</strong> - Awaiting insurance review</li>
                            <li><strong>Approved</strong> - Claim approved, payment pending</li>
                            <li><strong>Rejected</strong> - Claim denied, review required</li>
                            <li><strong>Paid</strong> - Payment received</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Submit claims promptly</li>
                            <li>Ensure all codes are accurate</li>
                            <li>Follow up on pending claims</li>
                            <li>Keep documentation organized</li>
                        </ul>
                    '
                ],
                'notifications' => [
                    'title' => 'Notifications & Alerts',
                    'content' => '
                        <h3>Overview</h3>
                        <p>The Notifications system keeps you informed about important events, reminders, and updates in your clinic.</p>
                        
                        <h3>Accessing Notifications</h3>
                        <p><strong>URL:</strong> <code>/clinic/notifications</code><br>
                        <strong>Navigation:</strong> Sidebar → Notifications (bell icon)</p>
                        
                        <h3>Notification Types</h3>
                        <ul>
                            <li><strong>Appointment Reminders</strong> - Upcoming appointments</li>
                            <li><strong>New Patient Registration</strong> - New patient added</li>
                            <li><strong>Payment Received</strong> - Payment notifications</li>
                            <li><strong>Insurance Claim Updates</strong> - Claim status changes</li>
                            <li><strong>System Alerts</strong> - Important system messages</li>
                        </ul>
                        
                        <h3>Managing Notifications</h3>
                        <ul>
                            <li>Mark as read</li>
                            <li>Delete notifications</li>
                            <li>Filter by type</li>
                            <li>Set notification preferences</li>
                        </ul>
                        
                        <h3>Notification Settings</h3>
                        <ol>
                            <li>Go to Profile & Settings</li>
                            <li>Navigate to Notification Preferences</li>
                            <li>Enable/disable notification types</li>
                            <li>Set email/SMS preferences</li>
                            <li>Save settings</li>
                        </ol>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Check notifications regularly</li>
                            <li>Configure preferences to avoid overload</li>
                            <li>Respond to important alerts promptly</li>
                        </ul>
                    '
                ],
                'patient-search' => [
                    'title' => 'Searching & Finding Patients',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Learn how to quickly find and access patient records using Phyzioline\'s search and filter features.</p>
                        
                        <h3>Search Methods</h3>
                        <h4>Quick Search</h4>
                        <ul>
                            <li>Use the search bar at the top</li>
                            <li>Search by patient name</li>
                            <li>Search by phone number</li>
                            <li>Search by patient ID</li>
                        </ul>
                        
                        <h4>Advanced Search</h4>
                        <ol>
                            <li>Go to Patients page</li>
                            <li>Click <strong>Advanced Search</strong></li>
                            <li>Use multiple filters:
                                <ul>
                                    <li>Name</li>
                                    <li>Date of birth</li>
                                    <li>Phone number</li>
                                    <li>Email</li>
                                    <li>Registration date</li>
                                    <li>Status</li>
                                </ul>
                            </li>
                            <li>Click Search</li>
                        </ol>
                        
                        <h3>Filtering Options</h3>
                        <ul>
                            <li>Filter by status (Active, Inactive)</li>
                            <li>Filter by specialty</li>
                            <li>Filter by registration date</li>
                            <li>Filter by last visit date</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Use phone number for quick lookup</li>
                            <li>Save frequent searches</li>
                            <li>Use filters to narrow results</li>
                        </ul>
                    '
                ],
                'appointment-reminders' => [
                    'title' => 'Appointment Reminders',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Appointment reminders help reduce no-shows by automatically notifying patients about upcoming appointments.</p>
                        
                        <h3>Setting Up Reminders</h3>
                        <ol>
                            <li>Go to Profile & Settings</li>
                            <li>Navigate to Appointment Settings</li>
                            <li>Enable automatic reminders</li>
                            <li>Set reminder timing (24 hours, 2 hours before)</li>
                            <li>Choose notification methods (Email, SMS)</li>
                            <li>Save settings</li>
                        </ol>
                        
                        <h3>Manual Reminders</h3>
                        <ol>
                            <li>Go to appointment</li>
                            <li>Click <strong>Send Reminder</strong></li>
                            <li>Choose method (Email or SMS)</li>
                            <li>Send reminder</li>
                        </ol>
                        
                        <h3>Reminder Templates</h3>
                        <ul>
                            <li>Customize reminder messages</li>
                            <li>Include appointment details</li>
                            <li>Add clinic contact information</li>
                            <li>Include cancellation instructions</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Send reminders 24 hours before</li>
                            <li>Send follow-up reminder 2 hours before</li>
                            <li>Personalize messages when possible</li>
                            <li>Track reminder effectiveness</li>
                        </ul>
                    '
                ],
                'document-management' => [
                    'title' => 'Document Management',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Document Management allows you to store, organize, and access patient documents, forms, and medical records.</p>
                        
                        <h3>Uploading Documents</h3>
                        <ol>
                            <li>Go to patient profile</li>
                            <li>Navigate to Documents tab</li>
                            <li>Click <strong>Upload Document</strong></li>
                            <li>Select file</li>
                            <li>Choose document type:
                                <ul>
                                    <li>ID/Passport</li>
                                    <li>Insurance Card</li>
                                    <li>Medical Reports</li>
                                    <li>X-rays/Scans</li>
                                    <li>Other</li>
                                </ul>
                            </li>
                            <li>Add description</li>
                            <li>Upload</li>
                        </ol>
                        
                        <h3>Organizing Documents</h3>
                        <ul>
                            <li>Create folders by category</li>
                            <li>Tag documents</li>
                            <li>Set document access permissions</li>
                            <li>Archive old documents</li>
                        </ul>
                        
                        <h3>Viewing Documents</h3>
                        <ul>
                            <li>Click on document to view</li>
                            <li>Download documents</li>
                            <li>Share documents securely</li>
                            <li>Print documents</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Upload documents immediately after receipt</li>
                            <li>Use clear naming conventions</li>
                            <li>Organize by date and type</li>
                            <li>Keep documents secure and confidential</li>
                        </ul>
                    '
                ],
                'reports-export' => [
                    'title' => 'Exporting Reports & Data',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Learn how to export reports, patient data, and analytics from Phyzioline for external use or record-keeping.</p>
                        
                        <h3>Exporting Reports</h3>
                        <ol>
                            <li>Go to Analytics or Reports section</li>
                            <li>Generate the report you need</li>
                            <li>Click <strong>Export</strong> button</li>
                            <li>Choose format:
                                <ul>
                                    <li>PDF - For printing or sharing</li>
                                    <li>Excel - For data analysis</li>
                                    <li>CSV - For database import</li>
                                </ul>
                            </li>
                            <li>Download file</li>
                        </ol>
                        
                        <h3>Exporting Patient Data</h3>
                        <ol>
                            <li>Go to Patients page</li>
                            <li>Apply filters if needed</li>
                            <li>Click <strong>Export</strong></li>
                            <li>Select data fields to include</li>
                            <li>Choose format</li>
                            <li>Export</li>
                        </ol>
                        
                        <h3>Exporting Appointments</h3>
                        <ul>
                            <li>Export appointment calendar</li>
                            <li>Export appointment history</li>
                            <li>Export by date range</li>
                            <li>Include patient and therapist information</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Export regularly for backups</li>
                            <li>Use appropriate format for purpose</li>
                            <li>Keep exported data secure</li>
                            <li>Comply with data protection regulations</li>
                        </ul>
                    '
                ],
                'user-permissions' => [
                    'title' => 'User Roles & Permissions',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Understanding user roles and permissions helps you control access to different features and data in your clinic system.</p>
                        
                        <h3>Available Roles</h3>
                        <h4>Clinic Admin</h4>
                        <ul>
                            <li>Full system access</li>
                            <li>Can manage all staff</li>
                            <li>Can configure settings</li>
                            <li>Can view all reports</li>
                        </ul>
                        
                        <h4>Therapist</h4>
                        <ul>
                            <li>Can view assigned patients</li>
                            <li>Can create clinical notes</li>
                            <li>Can manage own appointments</li>
                            <li>Limited access to settings</li>
                        </ul>
                        
                        <h4>Receptionist</h4>
                        <ul>
                            <li>Can manage appointments</li>
                            <li>Can register patients</li>
                            <li>Can view basic patient info</li>
                            <li>Cannot access clinical notes</li>
                        </ul>
                        
                        <h4>Assistant</h4>
                        <ul>
                            <li>Limited view access</li>
                            <li>Can assist with basic tasks</li>
                            <li>No editing permissions</li>
                        </ul>
                        
                        <h3>Setting Permissions</h3>
                        <ol>
                            <li>Go to Staff Management</li>
                            <li>Select staff member</li>
                            <li>Click Edit</li>
                            <li>Assign role</li>
                            <li>Set specific permissions</li>
                            <li>Save</li>
                        </ol>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Assign minimum necessary permissions</li>
                            <li>Review permissions regularly</li>
                            <li>Update permissions when roles change</li>
                            <li>Follow principle of least privilege</li>
                        </ul>
                    '
                ],
                'backup-data' => [
                    'title' => 'Data Backup & Security',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Understanding data backup and security practices helps protect your clinic\'s important information.</p>
                        
                        <h3>Automatic Backups</h3>
                        <ul>
                            <li>Phyzioline performs automatic daily backups</li>
                            <li>Data is stored securely in the cloud</li>
                            <li>Backups are retained for 30 days</li>
                            <li>No action required from you</li>
                        </ul>
                        
                        <h3>Manual Data Export</h3>
                        <ol>
                            <li>Go to Settings</li>
                            <li>Navigate to Data Management</li>
                            <li>Click <strong>Export All Data</strong></li>
                            <li>Select data types to export</li>
                            <li>Download backup file</li>
                            <li>Store securely offline</li>
                        </ol>
                        
                        <h3>Security Best Practices</h3>
                        <ul>
                            <li>Use strong, unique passwords</li>
                            <li>Enable two-factor authentication</li>
                            <li>Don\'t share login credentials</li>
                            <li>Log out when finished</li>
                            <li>Keep software updated</li>
                        </ul>
                        
                        <h3>Data Privacy</h3>
                        <ul>
                            <li>Patient data is encrypted</li>
                            <li>Access is logged and monitored</li>
                            <li>Compliance with healthcare regulations</li>
                            <li>Regular security audits</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Export data regularly for local backup</li>
                            <li>Review access logs periodically</li>
                            <li>Report security concerns immediately</li>
                            <li>Train staff on security practices</li>
                        </ul>
                    '
                ],
                'clinical-templates' => [
                    'title' => 'Clinical Templates Guide',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Clinical Templates help you create standardized note formats for different specialties and note types, saving time and ensuring consistency.</p>
                        
                        <h3>Using Templates</h3>
                        <ol>
                            <li>When creating a clinical note</li>
                            <li>Select your specialty</li>
                            <li>Choose note type</li>
                            <li>System automatically loads appropriate template</li>
                            <li>Fill in template fields</li>
                        </ol>
                        
                        <h3>Template Types</h3>
                        <ul>
                            <li>SOAP Note Templates</li>
                            <li>Evaluation Templates</li>
                            <li>Progress Note Templates</li>
                            <li>Discharge Summary Templates</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Use templates for consistency</li>
                            <li>Customize templates to your needs</li>
                            <li>Ensure all required fields are filled</li>
                        </ul>
                    '
                ],
                'coding-validation' => [
                    'title' => 'Medical Coding & Validation',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Medical coding validation ensures your diagnosis and procedure codes are correct and compliant with healthcare standards.</p>
                        
                        <h3>Adding Codes</h3>
                        <ol>
                            <li>When creating clinical note</li>
                            <li>Navigate to Coding section</li>
                            <li>Add ICD-10 diagnosis codes</li>
                            <li>Add CPT procedure codes</li>
                            <li>System validates codes automatically</li>
                        </ol>
                        
                        <h3>Code Validation</h3>
                        <ul>
                            <li>ICD-10 code verification</li>
                            <li>CPT code validation</li>
                            <li>NCCI edit checking</li>
                            <li>Compliance warnings</li>
                        </ul>
                        
                        <h3>Fixing Errors</h3>
                        <ul>
                            <li>Review validation errors</li>
                            <li>Select correct codes from suggestions</li>
                            <li>Update codes as needed</li>
                            <li>Re-validate before signing note</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Always validate codes before signing</li>
                            <li>Use specific, accurate codes</li>
                            <li>Review compliance warnings</li>
                            <li>Keep up with coding updates</li>
                        </ul>
                    '
                ],
                'assessments' => [
                    'title' => 'Clinical Assessments Guide',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Clinical Assessments allow you to conduct standardized evaluations and track patient progress over time.</p>
                        
                        <h3>Creating an Assessment</h3>
                        <ol>
                            <li>Go to patient profile</li>
                            <li>Navigate to Assessments</li>
                            <li>Click <strong>New Assessment</strong></li>
                            <li>Select assessment type</li>
                            <li>Fill in assessment form</li>
                            <li>Save assessment</li>
                        </ol>
                        
                        <h3>Assessment Types</h3>
                        <ul>
                            <li>Initial Evaluation</li>
                            <li>Progress Assessment</li>
                            <li>Re-evaluation</li>
                            <li>Discharge Assessment</li>
                            <li>Specialty-specific assessments</li>
                        </ul>
                        
                        <h3>Tracking Progress</h3>
                        <ul>
                            <li>Compare assessments over time</li>
                            <li>View progress charts</li>
                            <li>Identify improvements</li>
                            <li>Adjust treatment plans</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Conduct assessments at regular intervals</li>
                            <li>Use standardized assessment tools</li>
                            <li>Document findings thoroughly</li>
                            <li>Link assessments to treatment plans</li>
                        </ul>
                    '
                ],
                'voice-to-text' => [
                    'title' => 'Voice-to-Text Feature',
                    'content' => '
                        <h3>Overview</h3>
                        <p>The Voice-to-Text feature allows you to dictate clinical notes using your voice, saving time and improving documentation efficiency.</p>
                        
                        <h3>Using Voice-to-Text</h3>
                        <ol>
                            <li>Open clinical note editor</li>
                            <li>Click microphone icon</li>
                            <li>Allow browser microphone access</li>
                            <li>Start speaking clearly</li>
                            <li>Text appears in real-time</li>
                            <li>Click stop when finished</li>
                            <li>Review and edit transcribed text</li>
                        </ol>
                        
                        <h3>Tips for Best Results</h3>
                        <ul>
                            <li>Speak clearly and at moderate pace</li>
                            <li>Use medical terminology naturally</li>
                            <li>Pause for punctuation</li>
                            <li>Review transcription for accuracy</li>
                            <li>Edit as needed after transcription</li>
                        </ul>
                        
                        <h3>Browser Requirements</h3>
                        <ul>
                            <li>Modern browser (Chrome, Edge, Safari)</li>
                            <li>Microphone access permission</li>
                            <li>Stable internet connection</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Use in quiet environment</li>
                            <li>Review all transcribed text</li>
                            <li>Add proper formatting</li>
                            <li>Verify medical terms are correct</li>
                        </ul>
                    '
                ],
                'multi-clinic' => [
                    'title' => 'Multi-Clinic Management',
                    'content' => '
                        <h3>Overview</h3>
                        <p>If you manage multiple clinic locations, Phyzioline allows you to switch between clinics and manage them from one account.</p>
                        
                        <h3>Switching Clinics</h3>
                        <ol>
                            <li>Click clinic selector in top navigation</li>
                            <li>Select clinic from dropdown</li>
                            <li>System switches to selected clinic</li>
                            <li>All data and features update accordingly</li>
                        </ol>
                        
                        <h3>Clinic-Specific Data</h3>
                        <ul>
                            <li>Each clinic has separate:
                                <ul>
                                    <li>Patient records</li>
                                    <li>Staff members</li>
                                    <li>Appointments</li>
                                    <li>Settings</li>
                                </ul>
                            </li>
                            <li>Data is isolated between clinics</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Always verify current clinic before actions</li>
                            <li>Use clinic selector to switch when needed</li>
                            <li>Keep clinic information updated</li>
                        </ul>
                    '
                ],
                'mobile-access' => [
                    'title' => 'Mobile Access & App',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Phyzioline is fully accessible on mobile devices, allowing you to manage your clinic on the go.</p>
                        
                        <h3>Mobile Browser Access</h3>
                        <ul>
                            <li>Open mobile browser</li>
                            <li>Navigate to Phyzioline website</li>
                            <li>Log in with your credentials</li>
                            <li>Full functionality available</li>
                        </ul>
                        
                        <h3>Mobile Features</h3>
                        <ul>
                            <li>View appointments</li>
                            <li>Access patient records</li>
                            <li>Create quick notes</li>
                            <li>Check notifications</li>
                            <li>View reports</li>
                        </ul>
                        
                        <h3>Mobile Tips</h3>
                        <ul>
                            <li>Use landscape mode for tables</li>
                            <li>Pinch to zoom for details</li>
                            <li>Use mobile-optimized forms</li>
                            <li>Enable notifications for alerts</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Keep mobile app updated</li>
                            <li>Use secure connection (HTTPS)</li>
                            <li>Log out when finished</li>
                            <li>Keep device secure</li>
                        </ul>
                    '
                ],
                'troubleshooting' => [
                    'title' => 'Troubleshooting Common Issues',
                    'content' => '
                        <h3>Overview</h3>
                        <p>This guide helps you resolve common issues you may encounter while using Phyzioline.</p>
                        
                        <h3>Login Issues</h3>
                        <h4>Can\'t Log In</h4>
                        <ul>
                            <li>Verify email and password are correct</li>
                            <li>Check if Caps Lock is on</li>
                            <li>Try password reset</li>
                            <li>Clear browser cache</li>
                            <li>Contact support if issue persists</li>
                        </ul>
                        
                        <h3>Slow Performance</h3>
                        <ul>
                            <li>Check internet connection</li>
                            <li>Close unnecessary browser tabs</li>
                            <li>Clear browser cache</li>
                            <li>Try different browser</li>
                            <li>Check if system maintenance is scheduled</li>
                        </ul>
                        
                        <h3>Data Not Saving</h3>
                        <ul>
                            <li>Check internet connection</li>
                            <li>Verify all required fields are filled</li>
                            <li>Try refreshing page</li>
                            <li>Check browser console for errors</li>
                            <li>Contact support with error details</li>
                        </ul>
                        
                        <h3>Getting Help</h3>
                        <ul>
                            <li>Check this Help Center</li>
                            <li>Contact support: support@phyzioline.com</li>
                            <li>Include error messages and screenshots</li>
                            <li>Describe steps to reproduce issue</li>
                        </ul>
                    '
                ],
                'calendar-integration' => [
                    'title' => 'Calendar Integration',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Integrate Phyzioline appointments with external calendar systems like Google Calendar, Outlook, or Apple Calendar.</p>
                        
                        <h3>Setting Up Integration</h3>
                        <ol>
                            <li>Go to Profile & Settings</li>
                            <li>Navigate to Integrations</li>
                            <li>Select calendar system</li>
                            <li>Authorize connection</li>
                            <li>Configure sync settings</li>
                            <li>Save</li>
                        </ol>
                        
                        <h3>Sync Options</h3>
                        <ul>
                            <li>Two-way sync (appointments sync both ways)</li>
                            <li>One-way sync (Phyzioline to calendar only)</li>
                            <li>Sync frequency settings</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Use two-way sync for full integration</li>
                            <li>Review sync settings regularly</li>
                            <li>Keep calendar credentials secure</li>
                        </ul>
                    '
                ],
                'email-templates' => [
                    'title' => 'Email Templates & Communication',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Customize email templates for appointment reminders, confirmations, and patient communications.</p>
                        
                        <h3>Managing Templates</h3>
                        <ol>
                            <li>Go to Settings</li>
                            <li>Navigate to Email Templates</li>
                            <li>Select template type</li>
                            <li>Edit content</li>
                            <li>Preview template</li>
                            <li>Save</li>
                        </ol>
                        
                        <h3>Template Variables</h3>
                        <ul>
                            <li>{patient_name} - Patient name</li>
                            <li>{appointment_date} - Appointment date</li>
                            <li>{therapist_name} - Therapist name</li>
                            <li>{clinic_name} - Your clinic name</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Keep messages professional and clear</li>
                            <li>Include all necessary information</li>
                            <li>Test templates before using</li>
                        </ul>
                    '
                ],
                'patient-portal' => [
                    'title' => 'Patient Portal Access',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Patients can access their own portal to view appointments, documents, and treatment history.</p>
                        
                        <h3>Enabling Patient Portal</h3>
                        <ol>
                            <li>Go to Settings</li>
                            <li>Navigate to Patient Portal</li>
                            <li>Enable portal access</li>
                            <li>Configure available features</li>
                            <li>Save settings</li>
                        </ol>
                        
                        <h3>Patient Portal Features</h3>
                        <ul>
                            <li>View upcoming appointments</li>
                            <li>Request appointment changes</li>
                            <li>Access documents</li>
                            <li>View treatment history</li>
                            <li>Message clinic</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Enable portal for better patient engagement</li>
                            <li>Configure appropriate access levels</li>
                            <li>Train patients on portal use</li>
                        </ul>
                    '
                ],
                'api-integration' => [
                    'title' => 'API & Third-Party Integrations',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Phyzioline offers API access for integrating with other systems and third-party applications.</p>
                        
                        <h3>API Access</h3>
                        <ol>
                            <li>Contact support for API access</li>
                            <li>Receive API credentials</li>
                            <li>Review API documentation</li>
                            <li>Implement integration</li>
                            <li>Test thoroughly</li>
                        </ol>
                        
                        <h3>Available Integrations</h3>
                        <ul>
                            <li>Accounting software</li>
                            <li>Lab systems</li>
                            <li>Imaging systems</li>
                            <li>Billing systems</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Work with technical team</li>
                            <li>Test in development first</li>
                            <li>Monitor integration performance</li>
                        </ul>
                    '
                ],
                'data-privacy' => [
                    'title' => 'Data Privacy & HIPAA Compliance',
                    'content' => '
                        <h3>Overview</h3>
                        <p>Phyzioline is designed with healthcare data privacy and compliance in mind, following HIPAA and other healthcare regulations.</p>
                        
                        <h3>Privacy Features</h3>
                        <ul>
                            <li>Encrypted data transmission</li>
                            <li>Secure data storage</li>
                            <li>Access logging and auditing</li>
                            <li>User authentication</li>
                            <li>Role-based access control</li>
                        </ul>
                        
                        <h3>Your Responsibilities</h3>
                        <ul>
                            <li>Use strong passwords</li>
                            <li>Don\'t share login credentials</li>
                            <li>Log out when finished</li>
                            <li>Report security incidents</li>
                            <li>Train staff on privacy practices</li>
                        </ul>
                        
                        <h3>Best Practices</h3>
                        <ul>
                            <li>Review privacy settings regularly</li>
                            <li>Limit access to necessary personnel</li>
                            <li>Keep software updated</li>
                            <li>Follow clinic privacy policies</li>
                        </ul>
                    '
                ]
            ]
        ],
         'faq' => [
            'icon' => 'las la-question-circle',
            'title' => 'Frequently Asked Questions',
            'slug' => 'faq',
            'description' => 'Common questions about selling and buying.',
             'articles' => [
                 'general-faq' => [
                     'title' => 'General FAQ',
                     'content' => '
                        <p><strong>Q: Can I sell without inventory?</strong><br>
                        Yes, via dropshipping with approved suppliers.</p>
                        <p><strong>Q: Do you allow international vendors?</strong><br>
                        Yes, subject to compliance verification and logistics capabilities.</p>
                        <p><strong>Q: Are prices controlled?</strong><br>
                        Phyzioline enforces fair pricing policies to prevent price gouging.</p>
                     '
                ]
            ]
        ]
        ];
    }

    protected function getArabicContent()
    {
         return [
        'introduction' => [
            'icon' => 'las la-info-circle',
            'title' => 'مقدمة عن فيزيولاين',
            'slug' => 'introduction',
            'description' => 'نظرة عامة على المنصة، الأدوار في النظام البيئي، ونموذج العمل.',
            'articles' => [
                'what-is-phyzioline' => [
                    'title' => 'ما هي منصة فيزيولاين؟',
                    'content' => '
                        <h3>نظرة عامة على المنصة</h3>
                        <p>فيزيولاين هي منصة تجارة إلكترونية وتوزيع متعددة البائعين متخصصة بشكل عمودي في العلاج الطبيعي، التأهيل، اللياقة الطبية، ومعدات الصحة. على عكس الأسواق العامة، نحن نفرض رقابة طبية صارمة ومراقبة للجودة.</p>
                        
                        <h3>النظام البيئي</h3>
                        <p>تربط المنصة بين أربعة أطراف رئيسية:</p>
                        <ul>
                            <li><strong>العملاء النهائيين:</strong> العيادات، المستشفيات، أخصائيي العلاج الطبيعي، والمرضى.</li>
                            <li><strong>البائعين والمصنعين:</strong> الموردين المحليين والدوليين الموثقين للمعدات الطبية.</li>
                            <li><strong>شركاء الشحن:</strong> متخصصون في التعامل مع الأجهزة الطبية الحساسة (سلسلة التبريد، القابلية للكسر).</li>
                            <li><strong>المؤسسات المالية:</strong> معالجة آمنة للمدوعات وتمويل الشركات (B2B).</li>
                        </ul>
                        <div class="alert alert-info border-0 shadow-sm text-right">
                            <i class="las la-info-circle ml-2"></i> <strong>ملاحظة:</strong> تعمل فيزيولاين كسوق مُدار. تخضع جميع المنتجات والبائعين لعملية تدقيق صارمة قبل السماح لها بالظهور.
                        </div>
                    '
                ],
                'who-uses-phyzioline' => [
                    'title' => 'من يستخدم فيزيولاين؟',
                    'content' => '
                        <h3>أدوار المستخدمين والصلاحيات</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered text-right">
                                <thead class="thead-light">
                                    <tr>
                                        <th>الدور</th>
                                        <th>الأنشطة الرئيسية</th>
                                        <th>مستوى التحقق</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>عملاء التجزئة</strong></td>
                                        <td>شراء منتجات التأهيل الشخصية، حجز الزيارات المنزلية.</td>
                                        <td>التحقق من البريد الإلكتروني/الهاتف</td>
                                    </tr>
                                    <tr>
                                        <td><strong>العيادات والمستشفيات</strong></td>
                                        <td>الشراء بالجملة، الطلبات الدورية، الفواتير الضريبية.</td>
                                        <td>الرخصة التجارية + البطاقة الضريبية</td>
                                    </tr>
                                    <tr>
                                        <td><strong>البائعين / التجار</strong></td>
                                        <td>إدراج المنتجات، إدارة المخزون، وتجهيز الطلبات.</td>
                                        <td>تحقق كامل (قانوني وبنكي)</td>
                                    </tr>
                                    <tr>
                                        <td><strong>الدروب شيبرز</strong></td>
                                        <td>البيع بدون مخزون عبر موردين معتمدين.</td>
                                        <td>اتفاقية مع الموردين</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    '
                ]
            ]
        ],
        'account-management' => [
            'icon' => 'las la-user-cog',
            'title' => 'إدارة الحساب والمستخدمين',
            'slug' => 'account-management',
            'description' => 'إرشادات التسجيل، متطلبات التحقق (KYC)، وسياسات العقوبات الصارمة.',
            'articles' => [
                'creating-customer-account' => [
                    'title' => 'إنشاء حساب عميل',
                    'content' => '
                        <h3>خطوات التسجيل</h3>
                        <ol>
                            <li>اذهب إلى <strong>Phyzioline.com</strong> واضغط على <strong>تسجيل جديد</strong>.</li>
                            <li>اختر نوع الحساب:
                                <ul>
                                    <li><strong>فرد:</strong> للمرضى والاستخدام الشخصي.</li>
                                    <li><strong>عيادة / شركة:</strong> لمشتريات الأعمال (يتطلب بطاقة ضريبية).</li>
                                </ul>
                            </li>
                            <li>املأ الحقول الإلزامية: الاسم الكامل، البريد الإلكتروني، رقم الموبايل (تحقق OTP)، وكلمة مرور قوية.</li>
                            <li>أكمل التحقق عبر رمز OTP لكل من البريد والموبايل لتفعيل الحساب.</li>
                        </ol>

                        <h3 class="text-danger mt-4">إجراءات محظورة</h3>
                        <ul>
                            <li><strong>الحسابات المكررة:</strong> إنشاء حسابات متعددة لاستغلال عروض الترحيب ممنوع منعاً باتاً.</li>
                            <li><strong>بيانات وهمية:</strong> استخدام بريد إلكتروني مؤقت أو أرقام وهمية سيؤدي إلى حظر تلقائي.</li>
                        </ul>

                        <h3>أخطاء شائعة</h3>
                        <ul>
                            <li>إدخال رقم ضريبي غير صحيح يمنع إصدار فواتير B2B صالحة.</li>
                            <li>استخدام بريد إلكتروني عام مشترك (مثل info@clinic.com) لحساب شخصي.</li>
                        </ul>
                    '
                ],
                'vendor-account-registration' => [
                    'title' => 'تسجيل حساب بائع وسياساته',
                    'content' => '
                         <h3>كيفية التسجيل بشكل صحيح</h3>
                        <ol>
                            <li>قدم طلبك عبر <strong>بوابة البائعين</strong>.</li>
                            <li>ارفع "المستندات الأربعة الذهبية":
                                <ul>
                                    <li><strong>السجل التجاري:</strong> يجب أن يكون سارياً لمدة 6 أشهر على الأقل.</li>
                                    <li><strong>التسجيل الضريبي / شهادة القيمة المضافة:</strong> إلزامي لتحويل الأرباح.</li>
                                    <li><strong>خطاب حساب بنكي:</strong> يجب أن يطابق الاسم القانوني للشركة.</li>
                                    <li><strong>البطاقة الشخصية / الجواز:</strong> للمفوض بالتوقيع.</li>
                                </ul>
                            </li>
                            <li>انتظر <strong>مراجعة الامتثال</strong> (3–7 أيام عمل).</li>
                        </ol>

                        <h3 class="text-danger mt-4">العقوبات والإنفاذ</h3>
                        <table class="table table-bordered text-right">
                            <thead class="thead-light">
                                <tr>
                                    <th>المخالفة</th>
                                    <th>الإجراء / العقوبة</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>تقديم مستندات مزورة</td>
                                    <td><strong>حظر دائم</strong> (إدراج في القائمة السوداء)</td>
                                </tr>
                                <tr>
                                    <td>عدم تطابق اسم البنك</td>
                                    <td>رفض الطلب (يسمح بإعادة التقديم)</td>
                                </tr>
                                <tr>
                                    <td>بيع منتجات مقلدة</td>
                                    <td><strong>حظر دائم</strong> + إجراء قانوني + حجز الأموال (180 يوماً)</td>
                                </tr>
                            </tbody>
                        </table>
                    '
                ]
            ]
        ],
        'product-catalog' => [
            'icon' => 'las la-box-open',
            'title' => 'سياسات المنتجات وجودة القوائم',
            'slug' => 'product-catalog',
            'description' => 'إدراج الأجهزة الطبية بشكل صحيح، تجنب مخالفات الادعاءات، وقواعد الصور.',
            'articles' => [
                'creating-listing' => [
                    'title' => 'إرشادات الإدراج ونقاط الجودة',
                    'content' => '
                        <h3>معايير جودة الإدراج</h3>
                        <p>لضمان معدل تحويل عالي وسلامة طبية، يجب أن تستوفي جميع القوائم هذه المعايير:</p>
                        <ul>
                            <li><strong>العنوان:</strong> [الماركة] + [الموديل] + [الميزة الرئيسية] + [الاسم العام]. <br><em>مثال: "جهاز قياس ضغط الدم أومرون M3 للذراع العلوي".</em></li>
                            <li><strong>الصور:</strong> 5 صور عالية الدقة على الأقل بخلفية بيضاء نقية (RGB 255,255,255). بدون علامات مائية.</li>
                            <li><strong>الوصف:</strong> يجب أن يذكر بوضوح دواعي الاستعمال، موانع الاستعمال، والمواصفات الفنية.</li>
                        </ul>

                        <h3 class="text-danger mt-4">إجراءات محظورة / ممنوعة</h3>
                        <ul>
                            <li><strong>ادعاءات طبية كاذبة:</strong> استخدام كلمات مثل "علاج نهائي"، "معجزة"، أو "إصلاح فوري" بدون إثبات FDA/CE.</li>
                            <li><strong>حشو الكلمات المفتاحية:</strong> إضافة كلمات غير ذات صلة في العنوان (مثلاً بيع دعامة وكتابة "كرسي متحرك" في العنوان).</li>
                            <li><strong>نسخ المحتوى:</strong> نسخ الوصف مباشرة من أمازون أو المنافسين (مخالفة SEO).</li>
                        </ul>

                        <h3>إنفاذ النظام</h3>
                        <ul>
                            <li><strong>حجب البحث:</strong> القوائم ذات الصور السيئة أو الوصف القصير تُزال من نتائج البحث.</li>
                            <li><strong>تنبيه الحساب:</strong> 3 مخالفات للسياسة خلال 30 يوماً تؤدي إلى <strong>تليق الحساب لمدة 7 أيام</strong>.</li>
                        </ul>
                    '
                ],
                'prohibited-items' => [
                     'title' => 'المنتجات المحظورة والمقيدة',
                     'content' => '
                        <h3>المنتجات المحظورة</h3>
                        <p>المنتجات التالية محظورة تماماً على فيزيولاين:</p>
                        <ul>
                            <li>الأدوية التي تتطلب وصفة طبية (POM) بدون تكامل صيدلي خاص.</li>
                            <li>منتجات النظافة الشخصية المستعملة أو المجددة (مثل الأقطاب الكهربائية المستعملة، الكريمات المفتوحة).</li>
                            <li>أجهزة بشهادات معايرة منتهية الصلاحية.</li>
                        </ul>
                        <h3>المنتجات المقيدة (تتطلب موافقة)</h3>
                        <ul>
                            <li><strong>الأجهزة الطبية من الفئة IIb و III:</strong> تتطلب رفع موافقة صريحة من وزارة الصحة.</li>
                            <li><strong>المواد المشعة / معدات الأشعة السينية:</strong> تتطلب تصريح لوجستي خاص.</li>
                        </ul>
                     '
                ]
            ]
        ],
        'order-management' => [
            'icon' => 'las la-shopping-cart',
            'title' => 'معالجة الطلبات ومستوى الخدمة',
            'slug' => 'order-management',
            'description' => 'جداول زمنية صارمة لمعالجة الطلبات لتجنب معدلات الإلغاء.',
            'articles' => [
                'order-lifecycle-sla' => [
                     'title' => 'دورة حياة الطلب واتفاقيات مستوى الخدمة (SLA)',
                     'content' => '
                        <h3>إجراءات التشغيل القياسية (SOP)</h3>
                        <ol>
                            <li><strong>استلام الطلب:</strong> يتلقى البائع إشعاراً فورياً.</li>
                            <li><strong>القبول (المهلة: 4 ساعات):</strong> يجب على البائع "قبول" الطلب لتأكيد توفر المخزون.</li>
                            <li><strong>التغليف:</strong> يجب تغليف العنصر وفقاً لمعايير شحن المنتجات الطبية.</li>
                            <li><strong>جاهز للشحن (المهلة: 24 ساعة):</strong> يجب على البائع تحديد العنصر كـ "جاهز" وإصدار بوليصة الشحن.</li>
                            <li><strong>التسليم للكورير:</strong> تستلم شركة الشحن العنصر خلال النافذة المحددة.</li>
                        </ol>

                        <h3 class="text-danger mt-4">مقاييس الأداء والعقوبات</h3>
                        <table class="table table-bordered text-right">
                            <thead class="thead-light">
                                <tr>
                                    <th>المقياس</th>
                                    <th>الهدف</th>
                                    <th>عقوبة الفشل</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>معدل التأخر في الشحن (LDR)</td>
                                    <td>أقل من 4%</td>
                                    <td>حجب القائمة (خسارة صندوق الشراء)</td>
                                </tr>
                                <tr>
                                    <td>معدل الإلغاء قبل الشحن</td>
                                    <td>أقل من 2.5%</td>
                                    <td>خطر تعليق الحساب</td>
                                </tr>
                                <tr>
                                    <td>معدل التتبع الصالح (VTR)</td>
                                    <td>أكثر من 95%</td>
                                    <td>تقييد الفئة</td>
                                </tr>
                            </tbody>
                        </table>
                     '
                ]
            ]
        ],
         'shipping' => [
            'icon' => 'las la-shipping-fast',
            'title' => 'سياسات الشحن والتنفيذ',
            'slug' => 'shipping',
            'description' => 'قواعد FBV مقابل FBP، مسؤولية الطرود المفقودة، ومصفوفة التغليف.',
            'articles' => [
                'fulfillment-options' => [
                     'title' => 'نماذج التنفيذ (القواعد)',
                     'content' => '
                        <h3>1. التنفيذ بواسطة البائع (FBV)</h3>
                        <p>أنت تقوم بالتخزين، التغليف، والشحن. الأفضل للمعدات الكبيرة أو العناصر بطيئة الدوران.</p>
                        <ul>
                            <li><strong>الدور:</strong> البائع يمتلك مسؤولية "الميل الأخير" حتى التسليم لشركة الشحن.</li>
                            <li><strong>المخاطرة:</strong> البائع مسؤول بنسبة 100% عن الشحنات المتأخرة.</li>
                        </ul>
                        <h3>2. التنفيذ بواسطة فيزيولاين (FBP)</h3>
                        <p>أنت ترسل المخزون إلى مستودعنا. نحن نتولى كل شيء.</p>
                        <ul>
                            <li><strong>المزايا:</strong> شارة "مميز"، توصيل أسرع، فيزيولاين تتولى خدمة العملاء.</li>
                            <li><strong>الرسوم:</strong> تطبق رسوم التخزين + رسوم الانتقاء والتغليف.</li>
                        </ul>
                     '
                ],
                'packaging-guidelines' => [
                     'title' => 'مصفوفة التغليف والمسؤولية',
                     'content' => '
                         <h3>معايير التغليف</h3>
                         <p>التغليف غير المناسب الذي يؤدي إلى تلف سيؤدي إلى <strong>رفض المطالبة</strong>.</p>
                         <ul>
                             <li><strong>السوائل/الجل:</strong> يجب أن تكون محكمة الغلق بشكل مزدوج (غطاء محكم + كيس بولي) لمنع التسرب.</li>
                             <li><strong>الإلكترونيات:</strong> يجب أن يكون هناك ما لا يقل عن 2 بوصة من غلاف الفقاعات/بطانة على جميع الجوانب.</li>
                             <li><strong>الأغراض الثقيلة (>20 كجم):</strong> يجب وضعها على طبالي (بالتات) أو ربطها بإحكام.</li>
                         </ul>
                         
                         <h3>مصفوفة مسؤولية الفقد/التلف</h3>
                         <table class="table table-bordered text-right">
                            <thead class="thead-light">
                                <tr>
                                    <th>السيناريو</th>
                                    <th>الطرف المسؤول</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>تلف العنصر بسبب سوء التغليف</td>
                                    <td><strong>البائع</strong></td>
                                </tr>
                                <tr>
                                    <td>فقد العنصر من قبل الكورير (تم المسح عند الاستلام)</td>
                                    <td><strong>فيزيولاين / شركة الشحن</strong></td>
                                </tr>
                                <tr>
                                    <td>العميل يدعي "لم أستلم العنصر" (تم توقيع الاستلام)</td>
                                    <td><strong>يتطلب تحقيق</strong></td>
                                </tr>
                            </tbody>
                        </table>
                     '
                ]
            ]
        ],
        'payments' => [
            'icon' => 'las la-wallet',
            'title' => 'السياسات المالية',
            'slug' => 'payments',
            'description' => 'دورات الدفع، المبالغ المحتجزة، ومعالجة ضريبة القيمة المضافة.',
            'articles' => [
                'payout-policy' => [
                     'title' => 'سياسة التحويلات والحجز',
                     'content' => '
                        <h3>دورة التحويل</h3>
                        <p>يتم إنشاء المدفوعات على أساس <strong>أسبوعي</strong> (كل خميس) للطلبات "المكتملة" (تم التسليم + مر فترة الإرجاع).</p>
                        
                        <h3 class="text-danger">احتياطي مستوى الحساب (ALR)</h3>
                        <p>لتغطية المرتجعات المحتملة أو رد الموال، قد تطبق فيزيولاين احتياطي:</p>
                        <ul>
                            <li><strong>البائعون الجدد (أول 90 يوماً):</strong> احتياطي متجدد لمدة 7 أيام.</li>
                            <li><strong>البائعون عالي المخاطر (معدل إرجاع مرتفع):</strong> احتياطي متجدد لمدة 14 يوماً.</li>
                        </ul>

                        <h3>الضريبة والفواتير</h3>
                        <ul>
                            <li>يجب على البائعين إصدار فاتورة ضريبية لكل طلب.</li>
                            <li>تخصم فيزيولاين رسوم العمولة شاملة ضريبة القيمة المضافة.</li>
                            <li>يعد الفشل في رفع فاتورة ضريبية خلال 48 ساعة <strong>مخالفة للامتثال</strong>.</li>
                        </ul>
                     '
                ]
            ]
        ],
        'returns' => [
            'icon' => 'las la-undo',
            'title' => 'المرتجعات والنزاعات',
            'slug' => 'returns',
            'description' => 'معالجة المرتجعات، الاعتراض على المطالبات، واستثناءات النظافة.',
             'articles' => [
                 'return-policy-detailed' => [
                     'title' => 'سياسة الإرجاع والاستثناءات',
                     'content' => '
                        <h3>نافذة الإرجاع القياسية</h3>
                        <p>لدى العملاء <strong>14 يوماً</strong> (أو 30 يوماً لعيوب الصناعة) لإرجاع العناصر.</p>
                        
                        <h3>العناصر غير القابلة للإرجاع (قواعد النظافة)</h3>
                        <p>لأسباب تتعلق بالصحة والسلامة، لا يمكن إرجاع ما يلي إذا تم فتحها:</p>
                        <ul>
                            <li>الأقطاب الكهربائية وسادات الجل.</li>
                            <li>الملابس الضاغطة (التي تم ارتداؤها).</li>
                            <li>الكريمات، المستحضرات، والزيوت.</li>
                            <li>أجهزة التنفس (النيبولايزر، مقياس التنفس).</li>
                        </ul>
                        
                        <h3>حل النزاعات</h3>
                        <p>إذا استلم البائع مرتجعاً تالفاً أو مستخدماً من قبل العميل:</p>
                        <ol>
                            <li><strong>لا تقم بقبول</strong> الشحنة إذا كانت تالفة بشكل واضح.</li>
                            <li><strong>دليل الصور:</strong> ارفع صوراً للعنصر خلال 48 ساعة من الاستلام عبر بوابة النزاعات.</li>
                            <li><strong>التحكيم:</strong> سيقوم فريق فيزيولاين بالمراجعة وقد يعرض استرداداً جزئياً (رسوم إعادة التخزين) للبائع.</li>
                        </ol>
                     '
                ]
            ]
        ],
        'compliance' => [
            'icon' => 'las la-shield-alt',
            'title' => 'الامتثال الطبي والسلامة',
            'slug' => 'compliance',
            'description' => 'تسجيل وزارة الصحة، تتبع الأجهزة، والاستدعاءات.',
             'articles' => [
                 'medical-compliance' => [
                     'title' => 'امتثال الأجهزة الطبية',
                     'content' => '
                        <h3>متطلبات التسجيل</h3>
                        <p>يجب أن تتوافق جميع الأجهزة الطبية المباعة مع اللوائح المحلية (وزارة الصحة / هيئة الغذاء والدواء).</p>
                        <ul>
                            <li><strong>رخصة الاستيراد:</strong> العناصر المصنعة خارج الدولة يجب أن تمتلك تصاريح استيراد سارية.</li>
                            <li><strong>الممثل المعتمد (AR):</strong> يجب أن يكون البائعون وكلاء معتمدين للماركات التي يبيعونها.</li>
                        </ul>

                        <h3 class="text-danger">إجراءات محظورة</h3>
                        <ul>
                            <li>بيع أجهزة <strong>بشهادة معايرة منتهية</strong>.</li>
                            <li>بيع أجهزة <strong>"للاستخدام المهني فقط"</strong> للمستخدمين المنزليين دون التحقق من أوراق الاعتماد.</li>
                        </ul>

                        <h3>بروتوكول الاستدعاء</h3>
                        <p>في حالة استدعاء من الشركة المصنعة:</p>
                        <ol>
                            <li>يجب على البائع إخطار فريق امتثال فيزيولاين فوراً (خلال 4 ساعات).</li>
                            <li>ستقوم فيزيولاين بتجميد جميع المخزون وإخطار العملاء المتأثرين.</li>
                            <li>يتحمل البائع جميع تكاليف الخدمات اللوجستية العكسية والاستبدال.</li>
                        </ol>
                     '
                ]
            ]
        ],
         'support' => [
            'icon' => 'las la-headset',
            'title' => 'المساعدة والدعم',
            'slug' => 'support',
            'description' => 'قنوات الاتصال وأهداف مستوى الخدمة.',
             'articles' => [
                 'contact-channels' => [
                     'title' => 'قنوات الدعم واتفاقيات مستوى الخدمة',
                     'content' => '
                        <h3>قنوات الدعم</h3>
                        <ul>
                            <li><strong>مركز المساعدة:</strong> (قاعدة المعرفة هذه)</li>
                            <li><strong>دعم البريد الإلكتروني:</strong> support@phyzioline.com</li>
                            <li><strong>نظام تذاكر البائعين:</strong> داخل لوحة تحكم البائع</li>
                        </ul>
                        <h3>أهداف مستوى الخدمة (SLA)</h3>
                        <ul>
                            <li><strong>استفسار عام:</strong> 24–48 ساعة</li>
                            <li><strong>مشاكل البائعين:</strong> 12–24 ساعة</li>
                            <li><strong>قضايا طبية حرجة:</strong> تصعيد فوري</li>
                        </ul>
                     '
                ]
            ]
        ],
        'how-to-use' => [
            'icon' => 'las la-book-open',
            'title' => 'كيفية استخدام فيزيولاين',
            'slug' => 'how-to-use',
            'description' => 'أدلة شاملة لاستخدام جميع الميزات في نظام إدارة العيادات فيزيولاين.',
            'articles' => [
                'staff-status-management' => [
                    'title' => 'دليل إدارة حالة الموظفين',
                    'content' => '
                        <h3>نظرة عامة</h3>
                        <p>نظام حالة الموظفين في فيزيولاين يدير ما إذا كان أعضاء الفريق <strong>نشطين</strong> أو <strong>غير نشطين</strong> في عيادتك. يتيح لك إلغاء تنشيط الموظفين مؤقتًا دون حذفهم نهائيًا.</p>
                        
                        <h3>كيف تعمل حالة الموظف</h3>
                        <h4>أنواع الحالة:</h4>
                        <ul>
                            <li>✅ <strong>نشط</strong> - الموظف يعمل حاليًا ويمكنه الوصول إلى النظام</li>
                            <li>❌ <strong>غير نشط</strong> - الموظف معطل مؤقتًا (في إجازة، تم إنهاء خدمته، إلخ)</li>
                        </ul>
                        
                        <h3>أين تدير حالة الموظف</h3>
                        <p><strong>الموقع:</strong> صفحة دليل الموظفين<br>
                        <strong>الرابط:</strong> <code>/clinic/staff</code><br>
                        <strong>التنقل:</strong> الشريط الجانبي → الموظفون</p>
                        
                        <h3>كيفية تفعيل/إلغاء تفعيل الموظف</h3>
                        <ol>
                            <li>انتقل إلى <strong>دليل الموظفين</strong></li>
                            <li>ابحث عن الموظف</li>
                            <li>انقر على زر الحالة (🟡 لإلغاء التفعيل / 🟢 للتفعيل)</li>
                            <li>أكد الإجراء</li>
                        </ol>
                    '
                ],
                'dashboard-overview' => [
                    'title' => 'نظرة عامة على لوحة التحكم',
                    'content' => '
                        <h3>نظرة عامة</h3>
                        <p>لوحة التحكم هي مركز القيادة المركزي في فيزيولاين. توفر نظرة شاملة على عمليات عيادتك والمقاييس الرئيسية.</p>
                        
                        <h3>الوصول إلى لوحة التحكم</h3>
                        <p><strong>الرابط:</strong> <code>/clinic/dashboard</code></p>
                        
                        <h3>مكونات لوحة التحكم</h3>
                        <ul>
                            <li>بطاقات المقاييس الرئيسية</li>
                            <li>النشاط الأخير</li>
                            <li>إجراءات سريعة</li>
                            <li>الرسوم البيانية والتحليلات</li>
                        </ul>
                    '
                ],
                'patient-management' => [
                    'title' => 'دليل إدارة المرضى',
                    'content' => '
                        <h3>نظرة عامة</h3>
                        <p>إدارة المرضى هي جوهر عمليات عيادتك. يغطي هذا الدليل كيفية تسجيل وإدارة وتتبع مرضاك في فيزيولاين.</p>
                        
                        <h3>تسجيل مريض جديد</h3>
                        <ol>
                            <li>انقر على <strong>إضافة مريض جديد</strong></li>
                            <li>املأ المعلومات المطلوبة</li>
                            <li>أضف التاريخ الطبي</li>
                            <li>احفظ السجل</li>
                        </ol>
                    '
                ],
                'appointment-scheduling' => [
                    'title' => 'دليل جدولة المواعيد',
                    'content' => '
                        <h3>نظرة عامة</h3>
                        <p>جدولة المواعيد في فيزيولاين تتيح لك إدارة تقويم عيادتك وجدولة زيارات المرضى.</p>
                        
                        <h3>إنشاء موعد جديد</h3>
                        <ol>
                            <li>انقر على <strong>جدولة موعد</strong></li>
                            <li>اختر المريض</li>
                            <li>اختر المعالج</li>
                            <li>حدد التاريخ والوقت</li>
                            <li>احفظ الموعد</li>
                        </ol>
                    '
                ],
                'clinical-notes' => [
                    'title' => 'الملاحظات السريرية والتوثيق',
                    'content' => '
                        <h3>نظرة عامة</h3>
                        <p>الملاحظات السريرية تتيح لك توثيق زيارات المرضى والتقييمات والعلاجات والنتائج.</p>
                        
                        <h3>إنشاء ملاحظة سريرية</h3>
                        <ol>
                            <li>انقر على <strong>إنشاء ملاحظة جديدة</strong></li>
                            <li>اختر المريض</li>
                            <li>اختر نوع الملاحظة</li>
                            <li>املأ الأقسام</li>
                            <li>احفظ</li>
                        </ol>
                    '
                ]
            ]
        ],
         'faq' => [
            'icon' => 'las la-question-circle',
            'title' => 'الأسئلة الشائعة',
            'slug' => 'faq',
            'description' => 'أسئلة شائعة حول البيع والشراء.',
             'articles' => [
                 'general-faq' => [
                     'title' => 'أسئلة عامة',
                     'content' => '
                        <p><strong>س: هل يمكنني البيع بدون مخزون؟</strong><br>
                        نعم، عبر الدروب شيبينغ مع موردين معتمدين.</p>
                        <p><strong>س: هل تسمحون ببائعين دوليين؟</strong><br>
                        نعم، يخضع ذلك للتحقق من الامتثال والقدرات اللوجستية.</p>
                        <p><strong>س: هل الأسعار مراقبة؟</strong><br>
                        تفرض فيزيولاين سياسات تسعير عادلة لمنع التلاعب بالأسعار.</p>
                     '
                ]
            ]
        ]
        ];
    }

    /**
     * Display the Help Center Home
     */
    public function index()
    {
        $kb = $this->getKnowledgeBase();
        return view('web.help.index', [
            'categories' => $kb
        ]);
    }

    /**
     * Display a specific category
     */
    public function category($slug)
    {
        $kb = $this->getKnowledgeBase();
        if (!isset($kb[$slug])) {
            abort(404);
        }

        return view('web.help.category', [
            'category' => $kb[$slug]
        ]);
    }

    /**
     * Display a specific article
     */
    public function article($category_slug, $article_slug)
    {
        $kb = $this->getKnowledgeBase();
        if (!isset($kb[$category_slug])) {
            abort(404);
        }

        $category = $kb[$category_slug];

        if (!isset($category['articles'][$article_slug])) {
            abort(404);
        }

        return view('web.help.article', [
            'category' => $category,
            'article' => $category['articles'][$article_slug],
            'categories' => $kb // For sidebar navigation
        ]);
    }
    /**
     * Search the Knowledge Base
     */
    public function search(\Illuminate\Http\Request $request)
    {
        $query = $request->input('q');
        $kb = $this->getKnowledgeBase();
        $results = [];

        if ($query) {
            $keywords = explode(' ', strtolower($query));

            foreach ($kb as $catSlug => $category) {
                if (isset($category['articles'])) {
                    foreach ($category['articles'] as $artSlug => $article) {
                        $score = 0;
                        $titleLower = strtolower($article['title']);
                        $contentLower = strtolower(strip_tags($article['content']));

                        foreach ($keywords as $word) {
                            if (empty($word) || strlen($word) < 2) continue; // Skip single chars
                            
                            if (str_contains($titleLower, $word)) {
                                $score += 10;
                            }
                            if (str_contains($contentLower, $word)) {
                                $score += 1;
                            }
                        }

                        if ($score > 0) {
                            $results[] = [
                                'title' => $article['title'],
                                'excerpt' => \Illuminate\Support\Str::limit(strip_tags($article['content']), 150),
                                'category_title' => $category['title'],
                                'category_slug' => $catSlug,
                                'article_slug' => $artSlug,
                                'score' => $score
                            ];
                        }
                    }
                }
            }
        }

        // Sort by score descending
        usort($results, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return view('web.help.search', [
            'query' => $query,
            'results' => $results,
            'categories' => $kb
        ]);
    }
}
