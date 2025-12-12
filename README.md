# Phyzioline Platform

A comprehensive Laravel-based platform for physiotherapy services, including e-commerce, appointment booking, clinic ERP, and learning hub.

## Quick Start

### Local Development

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

4. **Run Development Server**
   ```bash
   .\scripts\local-dev.ps1
   # OR manually:
   php artisan serve
   npm run dev
   ```

For detailed setup instructions, see [Quick Start Guide](docs/deployment/quick-start.md).

## Project Structure

```
├── app/                    # Application code
├── docs/                   # Documentation
│   ├── deployment/         # Deployment guides
│   ├── guides/             # User guides
│   └── archive/            # Archived documentation
├── scripts/                # Automation scripts
│   ├── deploy.ps1          # Main deployment script
│   ├── local-dev.ps1       # Local development
│   └── utils/              # Utility scripts
├── resources/
│   └── views/
│       ├── web/            # Public website
│       ├── dashboard/      # User dashboard
│       ├── therapist/      # Therapist module
│       ├── clinic/         # Clinic ERP module
│       └── instructor/     # Learning Hub module
└── public/                 # Public assets
```

## Key Features

- **E-Commerce**: Product catalog, shopping cart, payment integration (Paymob)
- **Appointments**: Book sessions with therapists
- **Therapist Dashboard**: Manage appointments, patients, earnings
- **Clinic ERP**: Complete clinic management system
- **Learning Hub**: Course marketplace and instructor tools
- **Analytics**: Business insights and reporting

## Documentation

- **[Deployment Guide](docs/deployment/deployment-guide.md)** - Production deployment
- **[CloudPanel Setup](docs/deployment/cloudpanel-deployment.md)** - CloudPanel-specific instructions
- **[Email Configuration](docs/guides/email-configuration.md)** - Email setup
- **[Product Customization](docs/guides/product-customization.md)** - Product management
- **[Image Management](docs/guides/image-management.md)** - Working with images

## Deployment

For production deployment:

```bash
.\scripts\deploy.ps1
```

See the [Deployment Guide](docs/deployment/deployment-guide.md) for detailed instructions.

## Future Development

This project is prepared for future mobile app development with Python (Flutter backend/API integration).

## License

Proprietary - All rights reserved.
