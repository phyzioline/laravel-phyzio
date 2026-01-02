<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpCenterController extends BaseClinicController
{
    /**
     * Show help center index
     */
    public function index()
    {
        $categories = $this->getHelpCategories();
        $articles = $this->getHelpArticles();
        
        return view('web.clinic.help-center.index', compact('categories', 'articles'));
    }

    /**
     * Show article
     */
    public function show($slug)
    {
        $article = $this->getArticleBySlug($slug);
        
        if (!$article) {
            abort(404);
        }

        $relatedArticles = $this->getRelatedArticles($article);
        
        return view('web.clinic.help-center.show', compact('article', 'relatedArticles'));
    }

    /**
     * Search articles
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $articles = $this->searchArticles($query);
        
        return view('web.clinic.help-center.search', compact('articles', 'query'));
    }

    /**
     * Get help categories
     */
    protected function getHelpCategories()
    {
        return [
            'getting-started' => [
                'title' => __('Getting Started'),
                'icon' => 'las la-rocket',
                'description' => __('Learn the basics of using Phyzioline')
            ],
            'patients' => [
                'title' => __('Managing Patients'),
                'icon' => 'las la-user-injured',
                'description' => __('Patient registration and management')
            ],
            'appointments' => [
                'title' => __('Appointments'),
                'icon' => 'las la-calendar-check',
                'description' => __('Scheduling and managing appointments')
            ],
            'billing' => [
                'title' => __('Billing & Payments'),
                'icon' => 'las la-money-bill-wave',
                'description' => __('Financial management and invoicing')
            ],
            'reports' => [
                'title' => __('Reports & Analytics'),
                'icon' => 'las la-chart-bar',
                'description' => __('Understanding your clinic data')
            ],
            'settings' => [
                'title' => __('Settings & Configuration'),
                'icon' => 'las la-cog',
                'description' => __('System configuration and preferences')
            ]
        ];
    }

    /**
     * Get help articles
     */
    protected function getHelpArticles()
    {
        return [
            [
                'slug' => 'getting-started-welcome',
                'title' => __('Welcome to Phyzioline'),
                'category' => 'getting-started',
                'content' => __('Learn how to get started with Phyzioline Clinic Management System.'),
                'video_url' => null
            ],
            [
                'slug' => 'register-first-patient',
                'title' => __('How to Register Your First Patient'),
                'category' => 'patients',
                'content' => __('Step-by-step guide to registering a new patient in the system.'),
                'video_url' => null
            ],
            [
                'slug' => 'create-appointment',
                'title' => __('Creating Appointments'),
                'category' => 'appointments',
                'content' => __('Learn how to schedule and manage patient appointments.'),
                'video_url' => null
            ],
            [
                'slug' => 'partial-payments',
                'title' => __('Processing Partial Payments'),
                'category' => 'billing',
                'content' => __('How to record partial payments and track outstanding balances.'),
                'video_url' => null
            ],
            [
                'slug' => 'financial-reports',
                'title' => __('Understanding Financial Reports'),
                'category' => 'reports',
                'content' => __('Learn how to read and export your financial reports.'),
                'video_url' => null
            ],
            [
                'slug' => 'role-permissions',
                'title' => __('User Roles and Permissions'),
                'category' => 'settings',
                'content' => __('Understanding different user roles: Doctor, Receptionist, Accountant, and Admin.'),
                'video_url' => null
            ]
        ];
    }

    /**
     * Get article by slug
     */
    protected function getArticleBySlug($slug)
    {
        $articles = $this->getHelpArticles();
        
        foreach ($articles as $article) {
            if ($article['slug'] === $slug) {
                return $article;
            }
        }
        
        return null;
    }

    /**
     * Get related articles
     */
    protected function getRelatedArticles($article)
    {
        $articles = $this->getHelpArticles();
        
        return array_filter($articles, function($a) use ($article) {
            return $a['category'] === $article['category'] && $a['slug'] !== $article['slug'];
        });
    }

    /**
     * Search articles
     */
    protected function searchArticles($query)
    {
        $articles = $this->getHelpArticles();
        
        if (empty($query)) {
            return $articles;
        }

        return array_filter($articles, function($article) use ($query) {
            return stripos($article['title'], $query) !== false || 
                   stripos($article['content'], $query) !== false;
        });
    }
}

