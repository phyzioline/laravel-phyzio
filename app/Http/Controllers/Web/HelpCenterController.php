<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    /**
     * The Help Center Database (Static for now)
     * Expanded with Amazon-style policies, enforcement rules, and step-by-step guides.
     */
    protected $knowledgeBase = [
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
        ]
    ];

    /**
     * Display the Help Center Home
     */
    public function index()
    {
        return view('web.help.index', [
            'categories' => $this->knowledgeBase
        ]);
    }

    /**
     * Display a specific category
     */
    public function category($slug)
    {
        if (!isset($this->knowledgeBase[$slug])) {
            abort(404);
        }

        return view('web.help.category', [
            'category' => $this->knowledgeBase[$slug]
        ]);
    }

    /**
     * Display a specific article
     */
    public function article($category_slug, $article_slug)
    {
        if (!isset($this->knowledgeBase[$category_slug])) {
            abort(404);
        }

        $category = $this->knowledgeBase[$category_slug];

        if (!isset($category['articles'][$article_slug])) {
            abort(404);
        }

        return view('web.help.article', [
            'category' => $category,
            'article' => $category['articles'][$article_slug],
            'categories' => $this->knowledgeBase // For sidebar navigation
        ]);
    }
}
