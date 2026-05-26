# Ship Spare Parts Store - System Architecture

## 2. SYSTEM ARCHITECTURE DIAGRAM

```
┌─────────────────────────────────────────────────────────────────┐
│                        USER LAYER                               │
├──────────────────────────┬──────────────────────────────────────┤
│    Web Browser           │    Mobile App                        │
│  (Vue.js/Blade)          │    (React Native/Flutter)            │
└──────────────┬───────────┴──────────────────────┬────────────────┘
               │                                  │
┌──────────────▼──────────────────────────────────▼────────────────┐
│                    CDN / API GATEWAY                             │
│              (Nginx Load Balancer)                               │
│         Rate Limiting | CORS | Caching                          │
└──────────────┬──────────────────────────────────────────────────┘
               │
┌──────────────▼──────────────────────────────────────────────────┐
│                    LARAVEL APPLICATION                          │
├───────────────┬────────────────┬───────────────┬────────────────┤
│ API Routes    │ Controllers    │ Services      │ Middleware     │
│ - Products    │ - User Auth    │ - Search      │ - Auth         │
│ - Search      │ - Checkout     │ - Payment     │ - Throttle     │
│ - Orders      │ - Admin        │ - Orders      │ - CORS         │
│ - Cart        │ - Chatbot      │ - Chatbot     │ - Validation   │
└───────────────┴────────────────┴───────────────┴────────────────┘
               │
┌──────────────▼──────────────────────────────────────────────────┐
│                    DATA LAYER                                   │
├──────────────┬────────────────┬───────────────┬────────────────┤
│ MySQL DB     │ Redis Cache    │ Elasticsearch │ File Storage   │
│ (Products,   │ (Sessions,     │ (Full-text    │ (Images,       │
│  Orders,     │  Queries,      │   Search)     │  Invoices)     │
│  Users)      │  Carts)        │               │                │
└──────────────┴────────────────┴───────────────┴────────────────┘
               │
┌──────────────▼──────────────────────────────────────────────────┐
│                  EXTERNAL SERVICES                              │
├───────────────┬────────────────┬───────────────┬────────────────┤
│ Payment       │ Chatbot        │ Email Service │ SMS/WhatsApp   │
│ Gateways      │ OpenAI API     │ (SMTP/AWS)    │ (Twilio)       │
│ (UPI/Bank)    │ + Rule Engine  │               │                │
└───────────────┴────────────────┴───────────────┴────────────────┘
```

---

## 3. DATABASE SCHEMA

### Core Tables with Relationships

```
┌─────────────────────────────────────────────────────────────────┐
│  USERS TABLE                                                    │
├─────────────────────────────────────────────────────────────────┤
│ id (PK) | email | password | name | phone | role | created_at  │
│ Roles: customer, admin, vendor                                  │
└─────────────────────────────────────────────────────────────────┘
         │
         ├──→ ADDRESSES (hasMany)
         │    │ id | user_id | type | address | city | state | zip
         │
         ├──→ ORDERS (hasMany)
         │
         └──→ CART_ITEMS (hasMany)

┌─────────────────────────────────────────────────────────────────┐
│  PRODUCTS TABLE                                                 │
├─────────────────────────────────────────────────────────────────┤
│ id (PK) | sku | part_number | name | brand_id | category_id    │
│ description | specifications | price | cost | stock_qty        │
│ vessel_type_id | weight | dimensions | image_url | is_active   │
│ created_at | updated_at                                         │
└─────────────────────────────────────────────────────────────────┘
         │
         ├──→ BRANDS (belongsTo)
         ├──→ CATEGORIES (belongsTo)
         ├──→ VESSEL_TYPES (belongsTo)
         ├──→ PRODUCT_IMAGES (hasMany)
         ├──→ INVENTORY (hasOne)
         └──→ REVIEWS (hasMany)

┌─────────────────────────────────────────────────────────────────┐
│  ORDERS TABLE                                                   │
├─────────────────────────────────────────────────────────────────┤
│ id (PK) | order_number | user_id | status | payment_method    │
│ subtotal | tax | shipping | total | payment_status | notes     │
│ shipping_address_id | billing_address_id | created_at         │
│ Status: pending, confirmed, processing, shipped, delivered     │
│ Payment Status: pending, approved, failed, refunded            │
└─────────────────────────────────────────────────────────────────┘
         │
         ├──→ ORDER_ITEMS (hasMany)
         │    │ id | order_id | product_id | qty | price
         │
         ├──→ PAYMENTS (hasMany)
         │    │ id | order_id | method | status | amount | ref
         │
         └──→ SHIPPING (hasOne)

┌─────────────────────────────────────────────────────────────────┐
│  CART TABLE                                                     │
├─────────────────────────────────────────────────────────────────┤
│ id (PK) | user_id | session_id | created_at | expires_at      │
│ (Stores cart metadata, items in CART_ITEMS)                    │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  CHATBOT_FAQS TABLE                                             │
├─────────────────────────────────────────────────────────────────┤
│ id (PK) | question | answer | category | keywords | is_active  │
│ created_at | updated_at                                         │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  CHATBOT_CONVERSATIONS TABLE                                    │
├─────────────────────────────────────────────────────────────────┤
│ id (PK) | user_id | session_id | messages (JSON) | status      │
│ escalated_to_support | support_email | created_at              │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  REFERENCE TABLES                                               │
├─────────────────────────────────────────────────────────────────┤
│ BRANDS        | id | name | logo_url                           │
│ CATEGORIES    | id | name | slug | description                 │
│ VESSEL_TYPES  | id | name | code (e.g., "CONTAINER_SHIP")     │
│ INVENTORY     | id | product_id | warehouse_id | qty | sku    │
└─────────────────────────────────────────────────────────────────┘
```

---

## 4. KEY INDEXES FOR PERFORMANCE

```sql
-- Products - Critical for search
CREATE INDEX idx_products_sku ON products(sku);
CREATE INDEX idx_products_part_number ON products(part_number);
CREATE INDEX idx_products_brand_id ON products(brand_id);
CREATE INDEX idx_products_vessel_type_id ON products(vessel_type_id);
CREATE INDEX idx_products_category_id ON products(category_id);
CREATE FULLTEXT INDEX idx_products_fulltext ON products(name, description, part_number);

-- Orders
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_created_at ON orders(created_at);

-- Cart
CREATE INDEX idx_cart_user_id ON cart(user_id);
CREATE INDEX idx_cart_items_cart_id ON cart_items(cart_id);
```

---

## 5. TECHNOLOGY STACK

### Backend
- **Framework**: Laravel 11.x
- **Database**: MySQL 8.0
- **Cache**: Redis
- **Search**: Meilisearch (alternative: Elasticsearch)
- **Queue**: Redis + Laravel Queues
- **API**: RESTful + JSON

### Frontend
- **Template**: Laravel Blade + Vue.js
- **Responsive Framework**: TailwindCSS
- **UI Components**: Livewire (real-time interactivity)
- **Image Optimization**: Intervention Image

### DevOps
- **Hosting**: Ubuntu 22.04
- **Web Server**: Nginx
- **Process Manager**: Supervisor
- **SSL**: Let's Encrypt
- **CDN**: Cloudflare / AWS CloudFront

---

## 6. DEPLOYMENT ARCHITECTURE

```
┌────────────────────────────────────────────────────┐
│         Cloudflare / CDN                           │
│      (DDoS Protection, Caching)                    │
└────────────────────┬─────────────────────────────┘
                     │
┌────────────────────▼─────────────────────────────┐
│         Nginx Load Balancer                       │
│    (SSL Termination, Rate Limiting)               │
└────────────────────┬─────────────────────────────┘
                     │
         ┌───────────┴───────────┐
         │                       │
┌────────▼──────────┐  ┌────────▼──────────┐
│ App Server 1      │  │ App Server 2      │
│ (Laravel + PHP)   │  │ (Laravel + PHP)   │
│ Supervisor        │  │ Supervisor        │
│ Queues            │  │ Queues            │
└────────┬──────────┘  └────────┬──────────┘
         │                      │
         └──────────┬───────────┘
                    │
      ┌─────────────┼─────────────┐
      │             │             │
┌─────▼─────┐  ┌────▼────┐  ┌────▼─────┐
│  MySQL    │  │  Redis  │  │ Meilisearch
│ (Primary) │  │ (Cache) │  │ (Search)
│           │  │         │  │
│(Replication)│ │ Sessions│ │
└───────────┘  └─────────┘  └──────────┘
```

---

## 7. API ENDPOINTS OVERVIEW

### Authentication
- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User login (JWT/Session)
- `POST /api/auth/logout` - Logout

### Products
- `GET /api/products` - List products (with filters)
- `GET /api/products/{id}` - Product details
- `GET /api/products/search` - Full-text search
- `GET /api/categories` - List categories
- `GET /api/brands` - List brands
- `GET /api/vessel-types` - List vessel types

### Cart
- `POST /api/cart/add` - Add to cart
- `PUT /api/cart/update/{item_id}` - Update quantity
- `DELETE /api/cart/remove/{item_id}` - Remove item
- `GET /api/cart` - Get cart contents

### Orders
- `POST /api/orders` - Create order
- `GET /api/orders` - List user orders
- `GET /api/orders/{id}` - Order details
- `GET /api/orders/{id}/status` - Track status

### Payments
- `POST /api/payments/bank-transfer` - Bank transfer
- `POST /api/payments/upi` - UPI payment
- `POST /api/payments/verify` - Webhook verification

### Chatbot
- `POST /api/chatbot/message` - Send message
- `GET /api/chatbot/faqs` - Get all FAQs

### Admin
- `GET /api/admin/dashboard` - Dashboard stats
- `POST /api/admin/products/upload-csv` - Bulk product upload
- `GET /api/admin/orders` - All orders
- `PUT /api/admin/orders/{id}/status` - Update order status

---

## 8. CACHING STRATEGY

```
Layer 1: Frontend Cache (Browser)
- Static assets (30 days)
- API responses (5 minutes)

Layer 2: Redis Cache (Application)
- Product queries (1 hour)
- Category filters (24 hours)
- Search results (30 minutes)
- Session data (2 weeks)
- Cart items (7 days)

Layer 3: Query Cache (Database)
- MySQL Query Cache (if enabled)
- Query result optimization

Layer 4: CDN Cache
- Images (30 days)
- CSS/JS (30 days)
- HTML pages (5 minutes)
```

---

## 9. SECURITY MEASURES

1. **Authentication**: JWT + HttpOnly cookies
2. **Encryption**: AES-256 for sensitive data, bcrypt for passwords
3. **CORS**: Whitelist specific domains
4. **Rate Limiting**: 100 requests/minute per IP
5. **CSRF Protection**: Token validation on POST requests
6. **Input Validation**: Whitelist approach on all inputs
7. **File Upload**: Type validation, virus scan, CDN delivery
8. **SQL Injection**: Parameterized queries (Eloquent ORM)
9. **XSS Protection**: HTML entity encoding, CSP headers
10. **Audit Logs**: All admin actions logged
