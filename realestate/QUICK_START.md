# 🚀 Quick Start Guide - Real Estate Website

## Get Your Website Running in 5 Minutes

### Step 1: Navigate to Project
```bash
cd /var/www/html/freelancer/realestate
```

### Step 2: Install Dependencies (First Time Only)
```bash
composer install
npm install
```

### Step 3: Environment Setup (First Time Only)
```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Database Setup (First Time Only)
```bash
# Update .env with your database credentials
# Then run:
php artisan migrate
php artisan db:seed
```

### Step 5: Start the Website
```bash
php artisan serve
```

✅ **Open in Browser**: http://localhost:8000

---

## 📌 Essential Commands

| Task | Command |
|------|---------|
| Start website | `php artisan serve` |
| Run migrations | `php artisan migrate` |
| Seed sample data | `php artisan db:seed` |
| View all routes | `php artisan route:list` |
| Clear cache | `php artisan cache:clear` |
| Database reset | `php artisan migrate:refresh --seed` |

---

## 🔗 Website Pages

Once running, access these pages:

| Page | URL |
|------|-----|
| 🏠 Home | http://localhost:8000 |
| 📜 About Us | http://localhost:8000/about |
| 🏘️ Properties | http://localhost:8000/properties |
| 📧 Contact Us | http://localhost:8000/contact |
| ⭐ Special Requests | http://localhost:8000/special-requests |
| 💼 Investors | http://localhost:8000/investors |
| 📋 Investment Inquiry | http://localhost:8000/investors/inquiry |

---

## ✨ Sample Properties Already Added

The website comes with 8 sample properties:
1. Beautiful Downtown Apartment - $350,000
2. Modern Office Complex - $750,000
3. Spacious Suburban Home - $425,000
4. Prime Development Land - $500,000
5. Luxury Penthouse - $890,000
6. Cozy Studio Apartment - $180,000
7. Retail Shop Space - $300,000
8. Waterfront Property - $1,200,000

---

## 🛠️ Common Tasks

### Add a New Property
```bash
php artisan tinker

# Paste this code:
App\Models\Property::create([
    'title' => 'Awesome Property',
    'description' => 'Beautiful home in great location',
    'price' => 500000,
    'type' => 'residential',
    'location' => 'Downtown',
    'address' => '123 Main St',
    'area' => 2000,
    'bedrooms' => 3,
    'bathrooms' => 2,
    'parking_spaces' => 2,
    'is_featured' => true,
    'is_available' => true,
    'agent_name' => 'John Doe',
    'agent_contact' => '+1-555-1234'
]);

# Type 'exit' to quit
exit
```

### View Contact Inquiries
```bash
php artisan tinker

# View all contact inquiries
App\Models\ContactInquiry::all();

# View special requests
App\Models\SpecialRequest::all();

# View investment inquiries
App\Models\InvestmentInquiry::all();

exit
```

### Update Contact Information
Edit these files to update contact details:
- `resources/views/layouts/app.blade.php` - Footer
- `resources/views/contact/create.blade.php` - Contact page
- `resources/views/home.blade.php` - Homepage

Search for phone numbers and emails to update.

---

## 🎯 Key Features to Explore

### 1. **Property Listing**
   - Browse all properties
   - Filter by type, location, price
   - View detailed property information
   - See agent contact details

### 2. **Contact System**
   - Send inquiries to the office
   - All messages stored in database
   - Customers can request specific properties

### 3. **Special Requests**
   - Customers describe their ideal property
   - Team can search for matches
   - Track request status

### 4. **Investment Opportunities**
   - Learn about investment options
   - Submit investment inquiries
   - Get contacted by investment team

### 5. **Direct Communication**
   - WhatsApp integration
   - Direct phone call buttons
   - Email contact forms

---

## 🌐 Customization

### Update Company Information
Find and replace these in the project:
- `Real Estate Pro` → Your company name
- `+1 (555) 123-4567` → Your phone number
- `info@realestatepro.com` → Your email
- `https://wa.me/1234567890` → Your WhatsApp link

### Change Colors
Edit `resources/views/layouts/app.blade.php`:
```css
/* Change this gradient */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* To your colors */
background: linear-gradient(135deg, #FF6B35 0%, #004E89 100%);
```

---

## ❓ Troubleshooting

**Website won't start:**
- Make sure PHP 8.2+ is installed: `php -v`
- Check if port 8000 is available
- Run: `php artisan cache:clear`

**Database error:**
- Check `.env` database settings
- Verify database exists: `mysql -u root -p`
- Run: `php artisan migrate`

**Can't see properties:**
- Run: `php artisan db:seed`
- Check database: `php artisan tinker` then `App\Models\Property::count()`

---

## 📚 Next Steps

1. ✅ Start the website
2. ✅ Explore all pages
3. ✅ Test the forms
4. ✅ Add your own properties
5. ✅ Customize with your company info
6. ✅ Update contact information
7. ✅ Configure email notifications (optional)
8. ✅ Deploy to live server

---

## 📖 Full Documentation

For detailed information, see: `WEBSITE_README.md`

---

**Happy Hosting! 🎉**

For questions or issues, refer to the detailed documentation or consult the Laravel documentation at laravel.com
