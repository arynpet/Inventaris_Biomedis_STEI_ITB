# 📦 Sistem Inventaris Biomedis STEI ITB

Aplikasi manajemen inventaris biomedis berbasis Laravel dengan fitur AI assistant, QR code generation, dan sistem peminjaman terintegrasi.

## 🎯 Fitur Utama

### 📌 Core Features
- **Manajemen Item** - CRUD lengkap dengan QR code otomatis
- **Peminjaman Barang** - Tracking status real-time
- **Peminjaman Ruangan** - Schedule & approval workflow
- **3D Printing Service** - Material tracking & queue management
- **Activity Logging** - Audit trail semua aktivitas

### 🤖 AI Assistant (N.A.R.A)
- Natural language inventory management
- Batch item creation & deletion
- Smart serial number generation
- Powered by Google Gemini API

### 🔐 Security
- ✅ SQL Injection protection
- ✅ Authentication & authorization
- ✅ Input validation & sanitization
- ✅ Transaction safety
- ✅ CSRF protection

## 📊 Tech Stack

- **Backend:** Laravel 11.x
- **Frontend:** Blade Templates + Alpine.js
- **Database:** MySQL
- **Authentication:** Laravel Breeze
- **QR Code:** SimpleSoftwareIO/QrCode
- **PDF:** DomPDF
- **AI:** Google Gemini API

## 🚀 Setup

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM

### Installation

```bash
# Clone repository
git clone https://github.com/arynpet/Inventaris_Biomedis_STEI_ITB.git
cd Inventaris_Biomedis_STEI_ITB

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Build assets
npm run dev

# Start server
php artisan serve
```

### Environment Variables

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventaris_biomedis
DB_USERNAME=root
DB_PASSWORD=

# Gemini API (untuk fitur Nara)
GEMINI_API_KEY=your_api_key_here
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter NaraControllerTest

# With coverage
php artisan test --coverage
```

**Test Coverage:** 135/135 tests (100% ✅)

## 👥 User Roles

- **Superadmin** - Full access + user management
- **Admin** - Manage inventory, approve borrowing
- **Peminjam** - Borrow items & rooms

## 📁 Project Structure

```
├── app/
│   ├── Http/Controllers/
│   │   ├── BorrowingController.php
│   │   ├── ItemController.php
│   │   ├── NaraController.php
│   │   ├── PrintController.php
│   │   └── RoomController.php
│   ├── Models/
│   └── Policies/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
└── tests/
    └── Feature/
```

## 🔒 Security Improvements (Jan 2026)

### Critical Fixes
- ✅ **N1:** SQL Injection via LIKE wildcards
- ✅ **N2:** Missing authorization on Nara endpoints
- ✅ **N3:** No validation on batch operations
- ✅ **N4:** Transaction safety for batch delete

### High Priority Fixes
- ✅ **T1:** Race condition in borrowing
- ✅ **T2:** Filename sanitization
- ✅ **T3:** Bulk action transactions
- ✅ **T4:** File validation
- ✅ **T5:** Cascade delete protection
- ✅ **T6:** Error handling consistency
- ✅ **T7:** QR batch limiting

**Security Score:** 100% (Critical/High priority)

## 📖 API Endpoints

### Nara AI Assistant
```
POST /nara/chat          - Chat with AI
POST /nara/destroy       - Batch delete items
POST /nara/store-batch   - Batch create items
```

### Items
```
GET    /items           - List items
POST   /items           - Create item
GET    /items/{id}      - Show item
PUT    /items/{id}      - Update item
DELETE /items/{id}      - Delete item
POST   /items/regenerate_qr - Regenerate all QR codes
```

### Borrowings
```
GET    /borrowings      - List borrowings
POST   /borrowings      - Create borrowing
PUT    /borrowings/{id} - Update/return
POST   /borrowings/scan - QR scan API
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📝 License

This project is licensed under the MIT License.

## 👨‍💻 Developers

- **Team:** STEI ITB Biomedical Engineering
- **Repository:** [github.com/arynpet/Inventaris_Biomedis_STEI_ITB](https://github.com/arynpet/Inventaris_Biomedis_STEI_ITB)

## 📞 Support

For issues and questions, please open an issue on GitHub.

---

**Last Updated:** January 7, 2026  
**Version:** 2.0.0  
**Status:** 🟢 Production Ready
