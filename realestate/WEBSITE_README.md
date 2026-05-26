# Real Estate Pro - Professional Real Estate Website

A modern, responsive, and feature-rich real estate website built with Laravel 12, designed to showcase properties and facilitate investment opportunities.

## 🌟 Features

### Core Features
- **Modern & Responsive Design** - Works perfectly on mobile, tablet, and desktop
- **Property Listing** - Browse available properties with filters
- **Property Details** - Comprehensive property information pages
- **Contact Management** - Customer inquiry forms
- **Special Requests** - Custom property search requests
- **Investor Portal** - Investment opportunity inquiries
- **Direct Communication** - WhatsApp and phone call integration

### Property Management
- Filter properties by:
  - Type (Residential, Commercial, Land)
  - Location
  - Price range
- Property details including:
  - Bedrooms, bathrooms, parking spaces
  - Total area in square meters
  - Agent information
  - Detailed descriptions
- Featured properties section
- Latest properties display

### Contact Features
- Contact form with validation
- Special request submission
- Investment inquiry form
- All inquiries stored in database
- Status tracking (new, read, replied)

## 📁 Project Structure

```
app/
├── Http/Controllers/
│   ├── PropertyController.php      # Property listing & filtering
│   ├── ContactController.php       # Contact form handling
│   ├── SpecialRequestController.php # Special requests
│   ├── InvestmentController.php    # Investment inquiries
│   └── PageController.php          # Static pages
│
├── Models/
│   ├── Property.php                # Property model
│   ├── PropertyImage.php           # Property images
│   ├── ContactInquiry.php          # Contact inquiries
│   ├── SpecialRequest.php          # Special requests
│   └── InvestmentInquiry.php       # Investment inquiries

routes/
├── web.php                         # All route definitions

database/
├── migrations/
│   ├── create_properties_table
│   ├── create_property_images_table
│   ├── create_contact_inquiries_table
│   ├── create_special_requests_table
│   └── create_investment_inquiries_table
└── seeders/
    └── PropertySeeder.php          # Sample data

resources/views/
├── layouts/app.blade.php           # Base layout template
├── home.blade.php                  # Homepage
├── about.blade.php                 # About Us page
├── properties/
│   ├── index.blade.php             # Properties listing
│   └── show.blade.php              # Property details
├── contact/
│   └── create.blade.php            # Contact form
├── special-requests/
│   └── create.blade.php            # Special request form
└── investors/
    ├── index.blade.php             # Investment opportunities
    └── create.blade.php            # Investment inquiry form
```

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Laravel 12
- MySQL/SQLite database

### Installation

1. **Clone and Navigate**
   ```bash
   cd /var/www/html/freelancer/realestate
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Database**
   Edit `.env` file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=realestate
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run Migrations & Seeds**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build Assets** (optional)
   ```bash
   npm run build
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```
   Visit: http://localhost:8000

## 📄 Pages & Routes

| Page | URL | Route Name |
|------|-----|-----------|
| Home | / | home |
| About Us | /about | about |
| Properties | /properties | properties.index |
| Property Details | /properties/{id} | properties.show |
| Contact | /contact | contact.create |
| Special Requests | /special-requests | special-requests.create |
| Investors | /investors | investors.index |
| Investment Inquiry | /investors/inquiry | investors.create |

## 🎨 Design & Styling

- **Color Scheme**: Purple gradient (#667eea to #764ba2)
- **Typography**: Modern sans-serif (Segoe UI)
- **Components**: Card-based design
- **Responsive**: Mobile-first approach with grid layouts
- **Icons**: Font Awesome 6.4.0

## 💾 Database Structure

### Properties Table
- id, title, description, price
- type (residential/commercial/land)
- location, address, area
- bedrooms, bathrooms, parking_spaces
- is_featured, is_available
- agent_name, agent_contact
- timestamps

### Contact Inquiries Table
- id, name, email, phone
- message, subject
- status (new/read/replied)
- timestamps

### Special Requests Table
- id, name, email, phone
- request_details, budget_range
- location_preference, property_type_preference
- status (pending/contacted/closed)
- timestamps

### Investment Inquiries Table
- id, company_name, contact_person
- email, phone, inquiry_details
- investment_amount, investment_type
- preferred_location, status
- timestamps

### Property Images Table
- id, property_id (FK), image_path
- alt_text, order
- timestamps

## 🔧 Customization

### Update WhatsApp Number
Edit the WhatsApp links in views:
```
https://wa.me/1234567890?text=...
```
Replace `1234567890` with your actual WhatsApp number.

### Update Contact Information
Update footer and contact pages with:
- Company name
- Phone numbers
- Email addresses
- Physical address
- Social media links

### Add Property Images
Upload images to `storage/app/public/properties/` and update:
1. PropertyImage model records
2. View templates to display images

### Modify Color Scheme
Change gradient colors in `layouts/app.blade.php`:
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

## 📊 Managing Properties

### Add a New Property
Use Laravel Tinker:
```bash
php artisan tinker
```

```php
$property = new App\Models\Property([
    'title' => 'Property Title',
    'description' => 'Property description...',
    'price' => 500000,
    'type' => 'residential',
    'location' => 'City Name',
    'address' => '123 Street Name',
    'area' => 2000,
    'bedrooms' => 3,
    'bathrooms' => 2,
    'parking_spaces' => 2,
    'is_featured' => false,
    'is_available' => true,
    'agent_name' => 'Agent Name',
    'agent_contact' => '+1 555-1234'
]);
$property->save();
```

### View All Inquiries
View received inquiries:
```bash
php artisan tinker

# View all contact inquiries
App\Models\ContactInquiry::all();

# View special requests
App\Models\SpecialRequest::all();

# View investment inquiries
App\Models\InvestmentInquiry::all();
```

## 🔐 Security

- Input validation on all forms
- CSRF protection enabled
- SQL injection prevention via Eloquent ORM
- Environment variables for sensitive data

## ⚡ Performance

- Pagination (12 items per page)
- Database indexing on frequently queried columns
- Optimized database queries
- CSS and JavaScript loaded efficiently
- Responsive images (no unnecessary sizes)

## 📱 Mobile Optimization

- Responsive grid layouts
- Touch-friendly buttons
- Optimized navigation
- Fast loading times
- Mobile-first design approach

## 🚀 Deployment

1. Set up hosting environment
2. Update `.env` for production
3. Run `php artisan migrate --force`
4. Run `npm run build`
5. Configure web server (Apache/Nginx)
6. Update URL in `APP_URL` in `.env`

## 📧 Email Notifications (Optional)

To enable email notifications for inquiries:

1. Configure mail settings in `.env`:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=your-host
   MAIL_PORT=587
   MAIL_USERNAME=your-email
   MAIL_PASSWORD=your-password
   MAIL_FROM_ADDRESS=noreply@realestate.com
   ```

2. Create mailable classes:
   ```bash
   php artisan make:mail NewContactInquiry
   ```

3. Send emails in controllers

## 🆘 Troubleshooting

### Database Connection Error
- Check `.env` database credentials
- Ensure database server is running
- Verify database exists

### 404 Page Not Found
- Run `php artisan route:list` to verify routes
- Check route definitions in `routes/web.php`
- Clear cache: `php artisan cache:clear`

### Images Not Loading
- Check file permissions in `storage/` directory
- Verify image paths in database
- Run: `php artisan storage:link`

## 📝 Future Enhancements

- [ ] Admin dashboard
- [ ] Property image gallery with upload
- [ ] User authentication
- [ ] Property favorites/wishlist
- [ ] Advanced search filters
- [ ] Property ratings and reviews
- [ ] Virtual tours/3D walkthrough
- [ ] Email notifications
- [ ] Analytics and reports
- [ ] Multi-language support
- [ ] SEO optimization
- [ ] API for mobile app

## 📞 Contact & Support

For support or questions about the website, contact:
- Phone: +1 (555) 123-4567
- Email: info@realestatepro.com
- WhatsApp: https://wa.me/1234567890

## 📄 License

This project is proprietary and confidential. All rights reserved.

---

**Created**: May 2026
**Framework**: Laravel 12
**PHP Version**: 8.2+
