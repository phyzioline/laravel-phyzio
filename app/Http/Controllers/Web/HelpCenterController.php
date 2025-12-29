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
}
