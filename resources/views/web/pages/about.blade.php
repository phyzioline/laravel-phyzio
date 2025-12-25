@extends('web.layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
    $pageMeta = \App\Services\SEO\SEOService::getPageMeta('about');
@endphp

@section('title', $pageMeta['title'])

@push('meta')
    <meta name="description" content="{{ $pageMeta['description'] }}">
    <meta name="keywords" content="{{ $pageMeta['keywords'] }}">
    <meta property="og:title" content="{{ $pageMeta['title'] }}">
    <meta property="og:description" content="{{ $pageMeta['description'] }}">
    <meta property="og:type" content="website">
@endpush

@push('structured-data')
<script type="application/ld+json">
@json(\App\Services\SEO\SEOService::organizationSchema())
</script>
@endpush

@section('content')
<main>
    <!-- Hero Section -->
    <section class="hero-section position-relative" style="padding-top: 150px; padding-bottom: 80px; background: linear-gradient(135deg, #02767F 0%, #04b8c4 100%); margin-top: 80px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center text-white">
                    <h1 class="display-4 font-weight-bold mb-4">
                        {{ $isArabic ? 'من نحن - فيزيولاين' : 'About Us - Phyzioline' }}
                    </h1>
                    <p class="lead mb-4">
                        {{ $isArabic 
                            ? 'جميع احتياجات أخصائي العلاج الطبيعي هي مهمتنا من PT إلى PT'
                            : 'All Physical Therapist Needs is Our Mission From PT to PT' }}
                    </p>
                    <p class="h5 font-weight-normal opacity-90">
                        {{ $isArabic
                            ? 'حلول برمجية شاملة للعلاج الطبيعي'
                            : 'Comprehensive Physical Therapy Software Solutions' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm mb-5">
                        <div class="card-body p-5">
                            <h2 class="font-weight-bold mb-4" style="color: #36415A;">
                                {{ $isArabic ? 'من نحن' : 'Who We Are' }}
                            </h2>
                            <p class="lead mb-4">
                                {{ $isArabic
                                    ? 'فيزيولاين هي شركة برمجيات رائدة مكرسة لتحويل صناعة العلاج الطبيعي وإعادة التأهيل.'
                                    : 'Phyzioline is a forward-thinking software company dedicated to transforming the physical therapy and rehabilitation industry.' }}
                            </p>
                            <p class="mb-4">
                                {{ $isArabic
                                    ? 'نقدم مجموعة متنوعة من الحلول الرقمية المصممة خصيصاً لتلبية الاحتياجات المتطورة للأخصائيين والطلاب والمؤسسات الصحية.'
                                    : 'We offer a diverse range of digital solutions tailored to meet the evolving needs of therapists, students, and healthcare institutions.' }}
                            </p>
                            <p class="mb-4">
                                {{ $isArabic
                                    ? 'تشمل منصتنا خدمات مثل الاستشارات الافتراضية، أدوات إدارة الحالات، الدورات التعليمية، مطابقة الوظائف، بيع وإيجار الأجهزة، تحليلات البيانات، والإعلانات المستهدفة.'
                                    : 'Our platform encompasses services such as virtual counseling, case management tools, educational courses, job matching, device retail and rentals, data analytics, and targeted advertising.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Mission Section -->
                    <div class="card border-0 shadow-sm mb-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-4" style="width: 60px; height: 60px;">
                                    <i class="fas fa-rocket fa-2x"></i>
                                </div>
                                <h2 class="font-weight-bold mb-0" style="color: #36415A;">
                                    {{ $isArabic ? 'مهمتنا' : 'Our Mission' }}
                                </h2>
                            </div>
                            <div class="pl-5">
                                <h3 class="font-weight-bold mb-3" style="color: #02767F;">
                                    {{ $isArabic 
                                        ? 'جميع احتياجات أخصائي العلاج الطبيعي هي مهمتنا من PT إلى PT'
                                        : 'All Physical Therapist Needs is Our Mission From PT to PT' }}
                                </h3>
                                <p class="mb-4">
                                    {{ $isArabic
                                        ? 'تهدف فيزيولاين إلى إحداث ثورة في مجال العلاج الطبيعي من خلال توفير حلول رقمية متطورة تمكّن العيادات والأخصائيين والمرضى. نسعى لتحسين تقديم الرعاية من خلال إدارة الحالات السلسة، الرؤى القائمة على البيانات، الموارد التعليمية الشاملة، وعروض الخدمات المبتكرة التي تعزز الصحة وإعادة التأهيل.'
                                        : 'Phyzioline is dedicated to revolutionizing the physical therapy landscape by providing cutting-edge digital solutions that empower clinics, therapists, and patients. We strive to enhance care delivery through seamless case management, data-driven insights, comprehensive educational resources, and innovative service offerings that promote wellness and rehabilitation.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Vision Section -->
                    <div class="card border-0 shadow-sm mb-5">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mr-4" style="width: 60px; height: 60px;">
                                    <i class="fas fa-lightbulb fa-2x"></i>
                                </div>
                                <h2 class="font-weight-bold mb-0" style="color: #36415A;">
                                    {{ $isArabic ? 'رؤيتنا' : 'Our Vision' }}
                                </h2>
                            </div>
                            <div class="pl-5">
                                <p class="mb-4">
                                    {{ $isArabic
                                        ? 'رؤيتنا هي أن نكون المنصة الرائدة عالمياً لإدارة العلاج الطبيعي والتعليم والتحليلات. نسعى لإنشاء نظام بيئي متصل حيث تتحد التكنولوجيا والرعاية الصحية لتحسين نتائج المرضى، وتحسين عمليات العيادات، وتعزيز التعلم المستمر للمهنيين في هذا المجال.'
                                        : 'Our vision is to be the leading global platform for physical therapy management, education, and analytics. We aim to create a connected ecosystem where technology and healthcare unite to improve patient outcomes, optimize clinic operations, and foster continuous learning for professionals in the field.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Services Overview -->
                    <div class="card border-0 shadow-sm mb-5">
                        <div class="card-body p-5">
                            <h2 class="font-weight-bold mb-4 text-center" style="color: #36415A;">
                                {{ $isArabic ? 'خدماتنا الشاملة' : 'Our Comprehensive Services' }}
                            </h2>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-box bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; min-width: 50px;">
                                            <i class="las la-shopping-cart"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-weight-bold mb-2">{{ $isArabic ? 'المتجر' : 'Shop' }}</h5>
                                            <p class="text-muted mb-0">
                                                {{ $isArabic
                                                    ? 'منتجات العلاج الطبيعي الاحترافية والمعدات الطبية'
                                                    : 'Professional physical therapy products and medical equipment' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-box bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; min-width: 50px;">
                                            <i class="las la-home"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-weight-bold mb-2">{{ $isArabic ? 'الزيارات المنزلية' : 'Home Visits' }}</h5>
                                            <p class="text-muted mb-0">
                                                {{ $isArabic
                                                    ? 'أخصائيون متاحون للرعاية المنزلية'
                                                    : 'Expert therapists available for home-based care' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-box bg-info text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; min-width: 50px;">
                                            <i class="las la-clinic-medical"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-weight-bold mb-2">{{ $isArabic ? 'نظام إدارة العيادات' : 'Clinic ERP' }}</h5>
                                            <p class="text-muted mb-0">
                                                {{ $isArabic
                                                    ? 'نظام إدارة شامل مع السجلات الطبية الإلكترونية والجدولة والفوترة'
                                                    : 'Complete clinic management system with EMR, scheduling, and billing' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-box bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; min-width: 50px;">
                                            <i class="las la-graduation-cap"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-weight-bold mb-2">{{ $isArabic ? 'الدورات' : 'Courses' }}</h5>
                                            <p class="text-muted mb-0">
                                                {{ $isArabic
                                                    ? 'تدريب متخصص وتعليم مستمر لأخصائيي العلاج الطبيعي'
                                                    : 'Specialized training and continuing education for PT professionals' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-box bg-danger text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; min-width: 50px;">
                                            <i class="las la-briefcase"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-weight-bold mb-2">{{ $isArabic ? 'الوظائف' : 'Jobs' }}</h5>
                                            <p class="text-muted mb-0">
                                                {{ $isArabic
                                                    ? 'فرص عمل ومطابقة وظائف في العلاج الطبيعي'
                                                    : 'Career opportunities and job matching in physical therapy' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-box bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; min-width: 50px;">
                                            <i class="las la-stream"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-weight-bold mb-2">{{ $isArabic ? 'الأخبار' : 'Feed' }}</h5>
                                            <p class="text-muted mb-0">
                                                {{ $isArabic
                                                    ? 'آخر الأخبار والمقالات والتحديثات المجتمعية'
                                                    : 'Latest news, articles, and community updates' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-box bg-dark text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; min-width: 50px;">
                                            <i class="las la-database"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-weight-bold mb-2">{{ $isArabic ? 'بنك المعلومات' : 'Data Hub' }}</h5>
                                            <p class="text-muted mb-0">
                                                {{ $isArabic
                                                    ? 'إحصائيات العلاج الطبيعي العالمية ومتطلبات الترخيص والرؤى المهنية'
                                                    : 'Global PT statistics, licensing requirements, and professional insights' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CEO Section -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5 text-center">
                            <img src="{{ asset('web/assets/images/WhatsApp Image 2025-02-07 at 15.13.29_0eded989 2.svg') }}"
                                alt="{{ $isArabic ? 'الدكتور محمود مصباح' : 'Dr. Mahmoud Mosbah' }}" 
                                class="rounded-circle mb-4" 
                                style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #02767F;">
                            <h4 class="font-weight-bold mb-2" style="color: #36415A;">Dr. Mahmoud Mosbah</h4>
                            <p class="text-muted mb-0">{{ $isArabic ? 'الرئيس التنفيذي' : 'Chief Executive Officer' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

