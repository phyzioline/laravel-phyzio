<?php

namespace App\Services\SEO;

class SEOService
{
    /**
     * Generate structured data for Organization
     */
    public static function organizationSchema()
    {
        $gSetting = \App\Models\Setting::first();
        
        return [
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => "Phyzioline",
            "alternateName" => "فيزيولاين",
            "url" => url('/'),
            "logo" => asset('web/assets/images/logo.png'),
            "description" => "All Physical Therapist Needs is Our Mission From PT to PT. Comprehensive physical therapy software solutions, products, and services.",
            "foundingDate" => "2020",
            "founder" => [
                "@type" => "Person",
                "name" => "Dr. Mahmoud Mosbah"
            ],
            "sameAs" => [
                $gSetting->facebook ?? 'https://facebook.com/phyzioline',
                $gSetting->twitter ?? 'https://twitter.com/phyzioline',
                $gSetting->instagram ?? 'https://instagram.com/phyzioline',
                $gSetting->linkedin ?? 'https://linkedin.com/company/phyzioline'
            ],
            "contactPoint" => [
                "@type" => "ContactPoint",
                "telephone" => $gSetting->phone ?? '+20-XXX-XXX-XXXX',
                "contactType" => "Customer Service",
                "email" => $gSetting->email ?? 'info@phyzioline.com',
                "areaServed" => "EG",
                "availableLanguage" => ["en", "ar"]
            ],
            "address" => [
                "@type" => "PostalAddress",
                "addressCountry" => "EG",
                "addressLocality" => "Egypt"
            ]
        ];
    }

    /**
     * Generate structured data for WebSite
     */
    public static function websiteSchema()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "WebSite",
            "name" => "Phyzioline",
            "url" => url('/'),
            "potentialAction" => [
                "@type" => "SearchAction",
                "target" => [
                    "@type" => "EntryPoint",
                    "urlTemplate" => url('/{locale}/shop/search?q={search_term_string}')
                ],
                "query-input" => "required name=search_term_string"
            ]
        ];
    }

    /**
     * Generate structured data for Shop/Store
     */
    public static function shopSchema()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "Store",
            "name" => "Phyzioline Shop",
            "description" => "Professional physical therapy products, medical equipment, and rehabilitation supplies. All Physical Therapist Needs is Our Mission From PT to PT.",
            "url" => route('web.shop.search.' . app()->getLocale()),
            "image" => asset('web/assets/images/logo.png'),
            "priceRange" => "$$",
            "address" => [
                "@type" => "PostalAddress",
                "addressCountry" => "EG"
            ],
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => "4.8",
                "reviewCount" => "150"
            ]
        ];
    }

    /**
     * Generate structured data for Service (Home Visits)
     */
    public static function homeVisitsSchema()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "Service",
            "serviceType" => "Home Physical Therapy Visits",
            "provider" => [
                "@type" => "Organization",
                "name" => "Phyzioline"
            ],
            "areaServed" => [
                "@type" => "Country",
                "name" => "Egypt"
            ],
            "description" => "Professional physical therapists available for home visits. Expert care at your doorstep from certified specialists.",
            "offers" => [
                "@type" => "Offer",
                "priceCurrency" => "EGP",
                "availability" => "https://schema.org/InStock"
            ]
        ];
    }

    /**
     * Generate structured data for SoftwareApplication (Clinic ERP)
     */
    public static function erpSchema()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "SoftwareApplication",
            "name" => "Phyzioline Clinic ERP",
            "applicationCategory" => "MedicalSoftwareApplication",
            "operatingSystem" => "Web",
            "offers" => [
                "@type" => "Offer",
                "price" => "0",
                "priceCurrency" => "EGP",
                "priceValidUntil" => date('Y-12-31', strtotime('+1 year'))
            ],
            "description" => "Complete clinic management system for physical therapy practices. EMR, scheduling, billing, and patient engagement in one secure platform.",
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => "4.9",
                "reviewCount" => "85"
            ],
            "featureList" => [
                "Digital EMR",
                "Smart Scheduling",
                "Billing & Invoicing",
                "Staff Management",
                "Analytics Dashboard",
                "HIPAA Compliant Security"
            ]
        ];
    }

    /**
     * Generate structured data for Course (Courses)
     */
    public static function coursesSchema()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "EducationalOrganization",
            "name" => "Phyzioline Courses",
            "description" => "Advance your physiotherapy career with specialized courses and workshops. Certifications, video lessons, and practical training.",
            "url" => route('web.courses.index'),
            "courseMode" => ["online", "blended"],
            "educationalCredentialAwarded" => "Certificate",
            "teaches" => [
                "Physical Therapy",
                "Physiotherapy",
                "Rehabilitation",
                "Medical Equipment Usage"
            ]
        ];
    }

    /**
     * Generate structured data for JobPosting (Jobs)
     */
    public static function jobsSchema()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "JobPosting",
            "title" => "Physical Therapy Jobs",
            "description" => "Find the best physical therapy jobs and medical career opportunities. Connect with top clinics and hospitals.",
            "employmentType" => ["FULL_TIME", "PART_TIME", "CONTRACTOR"],
            "industry" => "Healthcare",
            "occupationalCategory" => "Physical Therapist"
        ];
    }

    /**
     * Generate structured data for DataCatalog (Data Hub)
     */
    public static function dataHubSchema()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "DataCatalog",
            "name" => "Phyzioline Data Hub",
            "description" => "Global physical therapy data, statistics, and licensing requirements. Your central resource for PT insights.",
            "url" => route('web.datahub.index'),
            "keywords" => "Physical Therapy Data, Statistics, Licensing, Global PT Landscape"
        ];
    }

    /**
     * Generate BreadcrumbList schema
     */
    public static function breadcrumbSchema($items)
    {
        $breadcrumbList = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => []
        ];

        $position = 1;
        foreach ($items as $item) {
            $breadcrumbList["itemListElement"][] = [
                "@type" => "ListItem",
                "position" => $position,
                "name" => $item['name'],
                "item" => $item['url'] ?? null
            ];
            $position++;
        }

        return $breadcrumbList;
    }

    /**
     * Get SEO meta for a specific page
     */
    public static function getPageMeta($pageType, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $isArabic = $locale === 'ar';

        $meta = [
            'shop' => [
                'title' => $isArabic 
                    ? 'متجر فيزيولاين - منتجات العلاج الطبيعي والمعدات الطبية'
                    : 'Phyzioline Shop - Physical Therapy Products & Medical Equipment',
                'description' => $isArabic
                    ? 'متجر متخصص في منتجات العلاج الطبيعي والمعدات الطبية. جميع احتياجات أخصائي العلاج الطبيعي هي مهمتنا من PT إلى PT.'
                    : 'Professional physical therapy products, medical equipment, and rehabilitation supplies. All Physical Therapist Needs is Our Mission From PT to PT.',
                'keywords' => $isArabic
                    ? 'متجر طبي, أجهزة علاج طبيعي, معدات طبية, فيزيولاين, منتجات تأهيل'
                    : 'Physical Therapy Products, Medical Equipment, Rehabilitation Supplies, PT Shop, Medical Store'
            ],
            'home_visits' => [
                'title' => $isArabic
                    ? 'زيارات العلاج الطبيعي المنزلية - فيزيولاين'
                    : 'Home Physical Therapy Visits - Phyzioline',
                'description' => $isArabic
                    ? 'احجز أخصائي علاج طبيعي محترف للزيارات المنزلية. رعاية خبيرة على عتبة دارك من أخصائيين معتمدين.'
                    : 'Book a professional physical therapist for home visits. Expert care at your doorstep from certified specialists.',
                'keywords' => $isArabic
                    ? 'زيارات منزلية, دكتور علاج طبيعي, تأهيل منزلي, فيزيولاين'
                    : 'Home Physical Therapy, Doctor Visit, Home Rehabilitation, Phyzioline Home Visits'
            ],
            'erp' => [
                'title' => $isArabic
                    ? 'نظام إدارة العيادات - فيزيولاين ERP'
                    : 'Clinic ERP System - Phyzioline',
                'description' => $isArabic
                    ? 'أدر عيادتك بكفاءة مع نظام فيزيولاين ERP الشامل. السجلات الطبية الإلكترونية، الجدولة، الفوترة والمزيد في منصة واحدة آمنة.'
                    : 'Manage your medical clinic efficiently with Phyzioline ERP. EMR, scheduling, billing, and patient engagement in one secure platform.',
                'keywords' => $isArabic
                    ? 'نظام إدارة عيادات, برنامج طبي, Clinic ERP, إدارة عيادات'
                    : 'Clinic Management System, Medical Software, Clinic ERP, EMR System'
            ],
            'courses' => [
                'title' => $isArabic
                    ? 'دورات العلاج الطبيعي - فيزيولاين'
                    : 'Physical Therapy Courses - Phyzioline',
                'description' => $isArabic
                    ? 'طور مسيرتك المهنية في العلاج الطبيعي مع دوراتنا المتخصصة وورش العمل. شهادات، دروس فيديو، وتدريب عملي.'
                    : 'Advance your physiotherapy career with specialized courses and workshops. Certifications, video lessons, and practical training.',
                'keywords' => $isArabic
                    ? 'دورات طبية, تعليم مستمر, دورات علاج طبيعي, تدريب'
                    : 'Medical Courses, CME, Physical Therapy Courses, Physiotherapy Education'
            ],
            'jobs' => [
                'title' => $isArabic
                    ? 'وظائف العلاج الطبيعي - فيزيولاين'
                    : 'Physical Therapy Jobs - Phyzioline',
                'description' => $isArabic
                    ? 'ابحث عن أفضل وظائف العلاج الطبيعي وفرص العمل الطبية. تواصل مع أفضل العيادات والمستشفيات.'
                    : 'Find the best physical therapy jobs and medical career opportunities. Connect with top clinics and hospitals.',
                'keywords' => $isArabic
                    ? 'وظائف طبية, توظيف, وظائف علاج طبيعي, فرص عمل'
                    : 'Medical Jobs, Physical Therapy Jobs, Physiotherapist Jobs, Healthcare Careers'
            ],
            'datahub' => [
                'title' => $isArabic
                    ? 'بنك المعلومات - فيزيولاين'
                    : 'Phyzioline Data Hub',
                'description' => $isArabic
                    ? 'استكشف بيانات العلاج الطبيعي العالمية والإحصائيات ومتطلبات الترخيص. مصدرك المركزي لرؤى العلاج الطبيعي.'
                    : 'Explore global physical therapy data, statistics, and licensing requirements. Your central resource for PT insights.',
                'keywords' => $isArabic
                    ? 'بنك المعلومات, احصائيات علاج طبيعي, تراخيص, بيانات عالمية'
                    : 'Global PT Data, Statistics, Licensing, Physical Therapy Landscape'
            ],
            'about' => [
                'title' => $isArabic
                    ? 'من نحن - فيزيولاين'
                    : 'About Us - Phyzioline',
                'description' => $isArabic
                    ? 'فيزيولاين: جميع احتياجات أخصائي العلاج الطبيعي هي مهمتنا من PT إلى PT. حلول برمجية شاملة للعلاج الطبيعي.'
                    : 'Phyzioline: All Physical Therapist Needs is Our Mission From PT to PT. Comprehensive physical therapy software solutions.',
                'keywords' => $isArabic
                    ? 'من نحن, فيزيولاين, حلول برمجية, علاج طبيعي'
                    : 'About Phyzioline, Physical Therapy Software, PT Solutions, Who We Are'
            ],
            'feed' => [
                'title' => $isArabic
                    ? 'الأخبار والمقالات - فيزيولاين'
                    : 'News & Articles - Phyzioline',
                'description' => $isArabic
                    ? 'تابع آخر الأخبار والمقالات في مجال العلاج الطبيعي والرعاية الصحية.'
                    : 'Stay updated with the latest news and articles in physical therapy and healthcare.',
                'keywords' => $isArabic
                    ? 'أخبار طبية, مقالات, علاج طبيعي, رعاية صحية'
                    : 'Medical News, Articles, Physical Therapy, Healthcare'
            ]
        ];

        return $meta[$pageType] ?? $meta['shop'];
    }
}

