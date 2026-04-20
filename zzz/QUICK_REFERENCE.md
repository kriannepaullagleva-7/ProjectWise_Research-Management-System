# ProjectWise3 - Quick Reference Guide

## 🚀 Quick Start

### Setup
```bash
cd c:\xampp1\htdocs\projectwise3
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

### Access
- **URL**: http://localhost:8000
- **Admin**: admin@example.com / password
- **Faculty**: john.smith@example.com / password
- **Student**: alice.johnson@example.com / password

---

## 📁 Project Structure

```
projectwise3/
├── app/
│   ├── Http/Controllers/         # 7 controllers
│   ├── Models/                   # 4 models
│   ├── Services/                 # 4 service classes
│   ├── Policies/                 # Authorization policies
│   └── Providers/                # Service providers
├── database/
│   ├── migrations/               # 9 migrations
│   ├── factories/                # Test factories
│   └── seeders/                  # Database seeders
├── resources/
│   ├── views/                    # Blade templates
│   ├── css/                      # Stylesheets
│   └── js/                       # JavaScript
├── routes/
│   └── web.php                   # All routes
├── tests/
│   ├── Feature/                  # Feature tests
│   └── Unit/                     # Unit tests
└── storage/                      # File storage
```

---

## 🗄️ Database Reference

### Users Table
```
id, name, email, password, email_verified_at, 
department, bio, phone, address, role (student|faculty|admin), 
created_at, updated_at
```

### ResearchProjects Table
```
id, user_id, title, description, keywords, category, 
file_path, status (pending|under_review|approved|rejected),
assigned_faculty_id, views_count, downloads_count, 
submission_date, created_at, updated_at
```

### FacultyReviews Table
```
id, research_project_id, faculty_id, feedback, rating (1-5), 
recommendation (approve|revise|reject), created_at, updated_at
```

### SavedItems Table
```
id, user_id, research_project_id, created_at, updated_at
```

---

## 🛣️ Routes Quick Reference

### Auth Routes
```
POST   /login           # User login
POST   /logout          # User logout
GET    /register        # Registration form
POST   /register        # User registration
```

### Project Routes (Authenticated)
```
GET    /research                  # List projects
POST   /research                  # Create project
GET    /research/{id}             # View project
PUT    /research/{id}             # Update project
DELETE /research/{id}             # Delete project
GET    /research/{id}/download    # Download file
```

### Library Routes
```
GET    /library                    # User's saved items
POST   /library/{id}/toggle        # Save/unsave project
```

### Faculty Routes
```
GET    /faculty/explorer           # Review queue
POST   /faculty/review/{id}        # Submit review
```

### Admin Routes
```
GET    /admin/users                # User management
POST   /admin/users                # Create user
GET    /admin/reports              # Dashboard
```

---

## 🎯 Common Tasks

### Create a User Programmatically
```php
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
    'role' => 'student'
]);
```

### Create a Research Project
```php
$project = ResearchProject::create([
    'user_id' => auth()->id(),
    'title' => 'My Research',
    'description' => 'Description here',
    'category' => 'Computer Science',
    'status' => 'pending'
]);
```

### Query Approved Projects
```php
$projects = ResearchProject::where('status', 'approved')
    ->orderBy('created_at', 'desc')
    ->paginate(15);
```

### Use Service Layer
```php
// In controller
$service = new ResearchProjectService();
$projects = $service->getApprovedProjects(15);
$trending = $service->getTrendingProjects(10);
```

### Standard API Response
```php
// Success
return ApiResponse::success($data, 'Project created', 201);

// Error
return ApiResponse::error('Failed to create', $errors, 400);

// Paginated
return ApiResponse::paginated($projects);
```

---

## 🧪 Testing

### Run All Tests
```bash
php artisan test
```

### Run Specific Test
```bash
php artisan test tests/Feature/CrudOperationsTest
```

### Verbose Output
```bash
php artisan test --testdox
```

### Current Status
- ✅ 6/6 tests pass
- ✅ 24 assertions verified
- ✅ 0 failures

---

## 🔐 Authorization Checks

### In Controllers
```php
// Check policy
$this->authorize('update', $project);

// Check role
if (auth()->user()->role !== 'admin') {
    abort(403);
}

// Using middleware
Route::put('/research/{id}', 'ResearchProjectController@update')
    ->middleware('can:update,research_project');
```

### In Policies
```php
public function update(User $user, ResearchProject $project)
{
    return $user->id === $project->user_id || $user->role === 'admin';
}
```

---

## 📊 Common Queries

### Get User's Projects
```php
$projects = ResearchProject::where('user_id', auth()->id())->get();
```

### Get Pending Reviews
```php
$pending = FacultyReview::whereNull('recommendation')->get();
```

### Get Most Viewed Projects
```php
$trending = ResearchProject::where('status', 'approved')
    ->orderBy('views_count', 'desc')
    ->limit(10)
    ->get();
```

### Get User Statistics
```php
$stats = [
    'total_projects' => ResearchProject::where('user_id', $user->id)->count(),
    'approved' => ResearchProject::where('user_id', $user->id)
                    ->where('status', 'approved')->count(),
    'saved_items' => SavedItem::where('user_id', $user->id)->count()
];
```

---

## 📝 File Locations

| Component | Location |
|-----------|----------|
| Controllers | `app/Http/Controllers/` |
| Models | `app/Models/` |
| Services | `app/Services/` |
| Policies | `app/Policies/` |
| Migrations | `database/migrations/` |
| Seeders | `database/seeders/` |
| Views | `resources/views/` |
| Routes | `routes/web.php` |
| Tests | `tests/` |

---

## 🔧 Development Commands

```bash
# Create migration
php artisan make:migration name_of_migration

# Create model
php artisan make:model ModelName

# Create controller
php artisan make:controller ControllerName

# Create service class
php artisan make:class Services/ServiceName

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Run seeders
php artisan db:seed

# Clear cache
php artisan cache:clear

# View active routes
php artisan route:list

# Tinker (interactive shell)
php artisan tinker
```

---

## 🐛 Debugging

### Enable Debug Mode
```
APP_DEBUG=true
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

### SQL Query Logging
```php
DB::enableQueryLog();
// ... your code
dd(DB::getQueryLog());
```

### Use Laravel Debugbar (Optional)
```bash
composer require barryvdh/laravel-debugbar --dev
```

---

## 📦 Dependencies

### Key Packages
- laravel/framework (11.x)
- barryvdh/laravel-dompdf (for PDF export)
- phpunit/phpunit (12.5.21)

### Installation
```bash
composer install
npm install
npm run build
```

---

## 🌐 Environment Variables

### Important `.env` Settings
```
APP_NAME=ProjectWise3
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=projectwise3
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

---

## 📊 Performance Tips

### Query Optimization
- Use eager loading: `with(['relationship'])`
- Avoid N+1 queries
- Use pagination for large datasets
- Index frequently queried columns ✅ Already done

### Caching
- Cache service responses
- Cache config values
- Cache database queries

### Use Service Layer
- Query logic centralized
- Reusable across controllers
- Easy to optimize

---

## 🔄 Common Workflows

### To Add New Feature
1. Create migration: `php artisan make:migration`
2. Run migration: `php artisan migrate`
3. Create model: `php artisan make:model`
4. Create controller: `php artisan make:controller`
5. Add routes in `routes/web.php`
6. Create service class if needed
7. Create views in `resources/views/`
8. Write tests in `tests/`

### To Deploy
1. Update `.env` for production
2. Run migrations: `php artisan migrate`
3. Clear cache: `php artisan config:cache`
4. Optimize autoloader: `composer install --optimize-autoloader --no-dev`
5. Set permissions: `chmod -R 775 storage bootstrap/cache`
6. Enable debug: `APP_DEBUG=false`

---

## 🚨 Troubleshooting

### Database Connection Error
```
1. Check DB_HOST, DB_PORT in .env
2. Verify MySQL is running
3. Check DB_DATABASE name (projectwise3)
4. Verify DB_USERNAME and DB_PASSWORD
```

### Permission Denied Error
```
chmod -R 775 storage bootstrap/cache
chmod -R 775 public
```

### Composer Issues
```
composer install
composer update
composer dump-autoload
```

### Clear Everything
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

---

## 📚 Additional Resources

- **Laravel Docs**: https://laravel.com/docs
- **Project README**: README.md
- **Database ERD**: DATABASE_ERD_DOCUMENTATION.md
- **Architecture**: ARCHITECTURE_DESIGN.md
- **Deployment**: DEPLOYMENT_GUIDE.md

---

**Last Updated**: April 18, 2026
**Status**: ✅ Ready to Use
