<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    /**
     * The Help Center Database (Static for now)
     */
    protected $knowledgeBase = [
        'introduction' => [
            'icon' => 'las la-info-circle',
            'title' => 'Introduction to Phyzioline',
            'slug' => 'introduction',
            'description' => 'Learn about Phyzioline, our mission, and who uses our platform.',
            'articles' => [
                'what-is-phyzioline' => [
                    'title' => 'What is Phyzioline?',
                    'content' => '
                        <h3>What is Phyzioline?</h3>
                        <p>Phyzioline is a multi-vendor e-commerce and distribution platform specialized in physical therapy, rehabilitation, medical fitness, and wellness equipment.</p>
                        <p>The platform connects:</p>
                        <ul>
                            <li><strong>End customers:</strong> Clinics, hospitals, therapists, and home users.</li>
                            <li><strong>Vendors & manufacturers:</strong> Local and international suppliers.</li>
                            <li><strong>Logistics & fulfillment providers:</strong> Ensuring timely delivery.</li>
                            <li><strong>Payment gateways:</strong> Secure financial transactions.</li>
                        </ul>
                        <p>Phyzioline operates similarly to Amazon Marketplace but is vertical-focused on physiotherapy and rehab products, ensuring medical accuracy, compliance, and specialized logistics handling.</p>
                    '
                ],
                'who-uses-phyzioline' => [
                    'title' => 'Who Uses Phyzioline?',
                    'content' => '
                        <h3>Who Uses Phyzioline?</h3>
                        <ul>
                            <li><strong>Retail Customers:</strong> Buy rehab and physiotherapy products for personal use.</li>
                            <li><strong>Clinics & Hospitals:</strong> Bulk and recurring purchases for professional use.</li>
                            <li><strong>Vendors / Sellers:</strong> List and sell products to a targeted audience.</li>
                            <li><strong>Dropshippers:</strong> Sell without holding inventory via approved suppliers.</li>
                            <li><strong>Administrators:</strong> Manage operations, compliance, and platform growth.</li>
                        </ul>
                    '
                ]
            ]
        ],
        'account-management' => [
            'icon' => 'las la-user-cog',
            'title' => 'Account & User Management',
            'slug' => 'account-management',
            'description' => 'Guides for creating and managing your Customer or Vendor account.',
            'articles' => [
                'creating-customer-account' => [
                    'title' => 'Creating a Customer Account',
                    'content' => '
                        <h3>Creating a Customer Account</h3>
                        <p><strong>Steps:</strong></p>
                        <ol>
                            <li>Go to <strong>Phyzioline.com</strong> → Click <strong>Sign Up</strong>.</li>
                            <li>Choose account type: <strong>Individual</strong> or <strong>Clinic / Company</strong>.</li>
                            <li>Enter required details:
                                <ul>
                                    <li>Full name</li>
                                    <li>Email address</li>
                                    <li>Phone number</li>
                                    <li>Password (min 8 characters, 1 number, 1 symbol)</li>
                                </ul>
                            </li>
                            <li>Verify email & phone via OTP.</li>
                            <li>Account is now activated.</li>
                        </ol>
                        <p><strong>Restrictions:</strong></p>
                        <ul>
                            <li>One email = one account.</li>
                            <li>Phone verification is mandatory for checkout.</li>
                        </ul>
                    '
                ],
                'vendor-account-registration' => [
                    'title' => 'Vendor Account Registration',
                    'content' => '
                         <h3>Vendor Account Registration</h3>
                        <p><strong>Steps:</strong></p>
                        <ol>
                            <li>Apply as Vendor via the Vendor Registration page.</li>
                            <li>Submit the following documents:
                                <ul>
                                    <li>Company legal name</li>
                                    <li>Trade license / Commercial registration</li>
                                    <li>Tax ID / VAT number</li>
                                    <li>Bank account details</li>
                                </ul>
                            </li>
                            <li>Identity & compliance review (KYC) takes place.</li>
                            <li>Approval or rejection within <strong>3–7 business days</strong>.</li>
                        </ol>
                        <p><strong>Vendor Tiers:</strong></p>
                        <ul>
                            <li><strong>Basic Vendor:</strong> Standard listing limits.</li>
                            <li><strong>Verified Medical Supplier:</strong> Higher limits, badge verified.</li>
                            <li><strong>Premium Partner:</strong> Brand-authorized, exclusive benefits.</li>
                        </ul>
                        <p><strong>Restrictions:</strong></p>
                        <ul>
                            <li>Fake brands are permanently banned.</li>
                            <li>Medical devices require valid regulatory documentation.</li>
                        </ul>
                    '
                ]
            ]
        ],
        'product-catalog' => [
            'icon' => 'las la-box-open',
            'title' => 'Product Catalog & Listing',
            'slug' => 'product-catalog',
            'description' => 'How to list products, categories, and manage your inventory.',
            'articles' => [
                'product-types' => [
                     'title' => 'Product Types Supported',
                     'content' => '
                        <h3>Product Types Supported</h3>
                        <ul>
                            <li>Electrotherapy devices</li>
                            <li>Ultrasound & shockwave therapy</li>
                            <li>Cryotherapy & heat therapy</li>
                            <li>Orthopedic supports (AFOs, braces)</li>
                            <li>Rehabilitation tools</li>
                            <li>Pediatric rehab devices</li>
                            <li>Fitness & recovery equipment</li>
                        </ul>
                     '
                ],
                'creating-listing' => [
                    'title' => 'Creating a Product Listing (Vendor)',
                    'content' => '
                        <h3>Creating a Product Listing</h3>
                        <p><strong>Required Fields:</strong></p>
                        <ul>
                            <li>Product title (English + Arabic)</li>
                            <li>Brand name (verified)</li>
                            <li>Category & subcategory</li>
                            <li>Full description (medical-grade accuracy)</li>
                            <li>Bullet points (benefits & specs)</li>
                            <li>Images (min 5, white background)</li>
                            <li>Country of origin & manufacture</li>
                            <li>Compliance certificates (if required)</li>
                        </ul>
                        <p><strong>Optional Fields:</strong></p>
                        <ul>
                            <li>SKU</li>
                            <li>Variations (size, color, voltage)</li>
                            <li>Instruction manual (PDF)</li>
                        </ul>
                        <p><strong>Restrictions:</strong></p>
                        <ul>
                            <li>No misleading medical claims.</li>
                            <li>No copied Amazon descriptions.</li>
                            <li>Images must be original or licensed.</li>
                        </ul>
                    '
                ]
            ]
        ],
        'order-management' => [
            'icon' => 'las la-shopping-cart',
            'title' => 'Order Management',
            'slug' => 'order-management',
            'description' => 'Understanding the order lifecycle and status updates.',
            'articles' => [
                'order-lifecycle' => [
                     'title' => 'Order Lifecycle',
                     'content' => '
                        <h3>Order Lifecycle</h3>
                        <ol>
                            <li>Customer places order</li>
                            <li>Payment authorized</li>
                            <li>Vendor notified</li>
                            <li>Vendor accepts order</li>
                            <li>Fulfillment initiated</li>
                            <li>Shipping & tracking generated</li>
                            <li>Delivery confirmation</li>
                            <li>Funds released to vendor</li>
                        </ol>
                     '
                ],
                'order-statuses' => [
                     'title' => 'Order Status Definitions',
                     'content' => '
                        <h3>Order Status Definitions</h3>
                         <ul>
                            <li><strong>Pending Payment:</strong> Order created, awaiting payment confirmation.</li>
                            <li><strong>Processing:</strong> Payment received, order is being prepared.</li>
                            <li><strong>Ready to Ship:</strong> Packed and labeled, waiting for courier.</li>
                            <li><strong>Shipped:</strong> Handed over to logistics partner (in transit).</li>
                            <li><strong>Delivered:</strong> Successfully reached the customer.</li>
                            <li><strong>Cancelled:</strong> Order voided before shipping.</li>
                            <li><strong>Returned:</strong> Item returned by customer after delivery.</li>
                        </ul>
                     '
                ]
            ]
        ],
         'shipping' => [
            'icon' => 'las la-shipping-fast',
            'title' => 'Shipping & Fulfillment',
            'slug' => 'shipping',
            'description' => 'Shipping models (FBV vs FBP) and packaging rules.',
            'articles' => [
                'fulfillment-models' => [
                     'title' => 'Fulfillment Models',
                     'content' => '
                        <h3>A. Vendor-Fulfilled (FBV – Fulfilled by Vendor)</h3>
                        <ul>
                            <li>Vendor packs and ships the item directly.</li>
                            <li>Must upload tracking number within <strong>24–48 hours</strong>.</li>
                        </ul>
                        <h3>B. Phyzioline Fulfilled (FBP – Fulfilled by Phyzioline)</h3>
                        <ul>
                            <li>Vendor sends stock to Phyzioline warehouse.</li>
                            <li>Phyzioline handles storage, packing, shipping, and returns.</li>
                        </ul>
                     '
                ],
                'shipping-rules' => [
                     'title' => 'Shipping Rules',
                     'content' => '
                         <h3>Shipping Rules</h3>
                         <ul>
                             <li><strong>Medical devices:</strong> Require protective packaging/padding.</li>
                             <li><strong>Fragile items:</strong> "Fragile" labels are mandatory.</li>
                             <li><strong>Temperature-sensitive items:</strong> Must use cold chain logistics where applicable.</li>
                         </ul>
                     '
                ]
            ]
        ],
        'payments' => [
            'icon' => 'las la-wallet',
            'title' => 'Payments & Finance',
            'slug' => 'payments',
            'description' => 'Payment methods, vendor payouts, and commissions.',
            'articles' => [
                 'payment-methods' => [
                     'title' => 'Supported Payment Methods',
                     'content' => '
                        <h3>Supported Payment Methods</h3>
                        <ul>
                            <li>Credit / Debit Cards (Visa, Mastercard, etc.)</li>
                            <li>Local wallets suitable for the region.</li>
                            <li>Bank transfer (For large B2B orders).</li>
                            <li>Cash on Delivery (Limited categories/locations).</li>
                        </ul>
                     '
                ],
                'vendor-payouts' => [
                     'title' => 'Vendor Payouts',
                     'content' => '
                        <h3>Vendor Payouts</h3>
                        <ul>
                            <li><strong>Settlement cycle:</strong> Weekly / Bi-weekly.</li>
                            <li><strong>Commission:</strong> Deducted automatically from sales.</li>
                            <li><strong>VAT:</strong> Handled based on vendor country regulations.</li>
                        </ul>
                        <p><strong>Restrictions:</strong></p>
                        <ul>
                            <li>Suspicious activity freezes payouts immediately.</li>
                            <li>Chargebacks are deducted from the vendor balance.</li>
                        </ul>
                     '
                ]
            ]
        ],
        'returns' => [
            'icon' => 'las la-undo',
            'title' => 'Returns & Refunds',
            'slug' => 'returns',
            'description' => 'Return eligibility, non-returnable items, and refund process.',
             'articles' => [
                 'return-eligibility' => [
                     'title' => 'Return Eligibility',
                     'content' => '
                        <h3>Return Eligibility</h3>
                        <ul>
                            <li>Factory defect / Malfunction.</li>
                            <li>Wrong item delivered.</li>
                            <li>Damaged during shipping.</li>
                        </ul>
                        <h3>Non-Returnable Items</h3>
                        <ul>
                            <li>Used medical devices.</li>
                            <li>Hygiene products (once opened).</li>
                            <li>Custom-made braces or orthotics.</li>
                        </ul>
                     '
                ],
                 'refund-process' => [
                     'title' => 'Refund Process',
                     'content' => '
                        <h3>Refund Process</h3>
                        <ol>
                            <li>Return request submitted by customer.</li>
                            <li>Inspection of returned item.</li>
                            <li>Approval or rejection based on condition.</li>
                            <li>Refund issued to original payment method.</li>
                        </ol>
                     '
                ]
            ]
        ],
        'reviews' => [
            'icon' => 'las la-star',
            'title' => 'Reviews & Ratings',
            'slug' => 'reviews',
            'description' => 'Policies on product reviews and moderation.',
             'articles' => [
                 'review-policy' => [
                     'title' => 'Reviews & Ratings System',
                     'content' => '
                        <h3>Who Can Review?</h3>
                        <p>Only verified purchasers who have bought the item on Phyzioline can leave a review.</p>
                        <h3>Moderation Rules</h3>
                        <ul>
                            <li>No offensive language or hate speech.</li>
                            <li>No competitor links or advertising.</li>
                            <li>Medical misinformation will be removed.</li>
                        </ul>
                     '
                ]
            ]
        ],
         'compliance' => [
            'icon' => 'las la-shield-alt',
            'title' => 'Compliance & Regulations',
            'slug' => 'compliance',
            'description' => 'Medical regulations, certificates, and obligations.',
             'articles' => [
                 'regulations' => [
                     'title' => 'Compliance & Medical Regulations',
                     'content' => '
                        <h3>Regulatory Bodies</h3>
                        <ul>
                            <li>MOH (Local Ministry of Health)</li>
                            <li>FDA / CE / ISO (International Standards)</li>
                        </ul>
                        <h3>Vendor Obligations</h3>
                        <ul>
                            <li>Upload valid certificates for regulated devices.</li>
                            <li>Maintain traceability of products.</li>
                            <li>Cooperate fully in case of product recalls.</li>
                        </ul>
                     '
                ]
            ]
        ],
         'security' => [
            'icon' => 'las la-lock',
            'title' => 'Security & Data',
            'slug' => 'security',
            'description' => 'Platform security measures and user responsibilities.',
             'articles' => [
                 'security-measures' => [
                     'title' => 'Security & Data Protection',
                     'content' => '
                        <h3>Platform Security</h3>
                        <ul>
                            <li>SSL encryption for all data.</li>
                            <li>Secure payment gateways (PCI-DSS compliant).</li>
                            <li>Role-based access control for data privacy.</li>
                        </ul>
                        <h3>User Responsibilities</h3>
                        <ul>
                            <li>Keep passwords secure and unique.</li>
                            <li>Do not share account crdentials.</li>
                        </ul>
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
