# ProjectWise Database Structure

## Database Connection Details

- **Database Name:** `projectwise3`
- **Host:** `127.0.0.1` (localhost)
- **Port:** `3306`
- **Username:** `root`
- **Password:** (empty)
- **Type:** MySQL
- **Charset:** utf8mb4

## How to Access in phpMyAdmin

1. **Start XAMPP:**
   - Open XAMPP Control Panel
   - Click "Start" for Apache and MySQL

2. **Open phpMyAdmin:**
   - Go to: http://localhost/phpmyadmin
   - Or: http://127.0.0.1/phpmyadmin

3. **View ProjectWise Database:**
   - In the left sidebar, click on `projectwise3`
   - You will see all tables listed below

## Database Tables Overview

### 1. **users** - User Management
Stores all user information (students, faculty, admins)

| Field | Type | Notes |
|-------|------|-------|
| id | INT (PK) | Auto-increment |
| name | VARCHAR | Username |
| email | VARCHAR (UNIQUE) | User email |
| password | VARCHAR | Hashed password |
| role | ENUM | 'student', 'faculty', 'admin' |
| full_name | VARCHAR | Full name |
| department | VARCHAR | Department/Faculty |
| bio | TEXT | User biography |
| avatar_url | VARCHAR | Profile picture URL |
| student_id | VARCHAR | Student ID number |
| email_verified_at | TIMESTAMP | Email verification |
| created_at | TIMESTAMP | Created date |
| updated_at | TIMESTAMP | Last updated |

**Relationships:**
- `1` user creates `Many` research_projects
- `1` user can review `Many` faculty_reviews
- `1` user can save `Many` saved_items

---

### 2. **research_projects** - Research Project Storage
Stores all research project submissions and metadata

| Field | Type | Notes |
|-------|------|-------|
| id | INT (PK) | Auto-increment |
| user_id | INT (FK) | References users.id |
| title | VARCHAR | Project title |
| description | TEXT | Project description |
| abstract | TEXT | Project abstract |
| category | VARCHAR | Research category |
| field_of_study | VARCHAR | Field of study |
| keywords | TEXT | Comma-separated keywords |
| status | ENUM | 'pending', 'under_review', 'approved', 'rejected' |
| reviewer_feedback | TEXT | Feedback from faculty |
| assigned_faculty_id | INT (FK) | References users.id (faculty) |
| file_path | VARCHAR | Path to uploaded file |
| view_count | INT | Number of views |
| views_count | INT | Alternative views counter |
| downloads_count | INT | Number of downloads |
| submission_date | TIMESTAMP | Date submitted |
| approval_date | TIMESTAMP | Date approved |
| created_at | TIMESTAMP | Created date |
| updated_at | TIMESTAMP | Last updated |

**Relationships:**
- Belongs to `1` user (creator)
- Has `Many` faculty_reviews
- Is in `Many` saved_items

**Status Workflow:**
```
pending → under_review → approved/rejected
                      ↓
                    revise → under_review (again)
```

---

### 3. **faculty_reviews** - Research Project Reviews
Stores faculty reviews and feedback for research projects

| Field | Type | Notes |
|-------|------|-------|
| id | INT (PK) | Auto-increment |
| research_project_id | INT (FK) | References research_projects.id |
| faculty_id | INT (FK) | References users.id (faculty member) |
| feedback | TEXT | Review feedback |
| recommendation | ENUM | 'approve', 'reject', 'revise' |
| rating | INT | Numerical rating (1-5) |
| created_at | TIMESTAMP | Review created date |
| updated_at | TIMESTAMP | Review updated date |

**Relationships:**
- Belongs to `1` research_project
- Belongs to `1` faculty user

---

### 4. **saved_items** - User Saved Research (Junction Table)
Tracks which research projects users have saved

| Field | Type | Notes |
|-------|------|-------|
| id | INT (PK) | Auto-increment |
| user_id | INT (FK) | References users.id |
| research_project_id | INT (FK) | References research_projects.id |
| created_at | TIMESTAMP | Save date |
| updated_at | TIMESTAMP | Last updated |

**Constraints:**
- Unique constraint on (user_id, research_project_id) - prevents duplicate saves

**Relationships:**
- Belongs to `1` user
- Belongs to `1` research_project

---

### 5. **notifications** (System Table)
Stores system notifications and alerts

| Field | Type |
|-------|------|
| id | INT (PK) |
| type | VARCHAR |
| notifiable_type | VARCHAR |
| notifiable_id | INT |
| data | JSON |
| read_at | TIMESTAMP |
| created_at | TIMESTAMP |

---

### 6. **cache** (System Table)
Caches frequently accessed data for performance

| Field | Type |
|-------|------|
| key | VARCHAR (PK) |
| value | LONGTEXT |
| expiration | INT |

---

### 7. **jobs** (System Table)
Queue system for background jobs

| Field | Type |
|-------|------|
| id | INT (PK) |
| queue | VARCHAR |
| payload | LONGTEXT |
| attempts | INT |
| reserved_at | INT |
| available_at | INT |
| created_at | INT |

---

### 8. **password_reset_tokens** (System Table)
Temporary tokens for password reset functionality

| Field | Type |
|-------|------|
| email | VARCHAR (PK) |
| token | VARCHAR |
| created_at | TIMESTAMP |

---

### 9. **sessions** (System Table)
User session management

| Field | Type |
|-------|------|
| id | VARCHAR (PK) |
| user_id | INT (FK) |
| ip_address | VARCHAR |
| user_agent | TEXT |
| payload | LONGTEXT |
| last_activity | INT |

---

## Database Relationships (ERD Summary)

```
USERS (1) ──────────── (Many) RESEARCH_PROJECTS
  │
  ├──────── (Many) FACULTY_REVIEWS
  │
  └──────── (Many) SAVED_ITEMS
  
RESEARCH_PROJECTS (1) ──────────── (Many) FACULTY_REVIEWS
                    │
                    └──────────── (Many) SAVED_ITEMS
```

### Key Relationships:

1. **User → Research Projects** (One-to-Many)
   - One user creates many research projects
   - Foreign Key: `research_projects.user_id` → `users.id`

2. **User → Faculty Reviews** (One-to-Many)
   - One faculty member writes many reviews
   - Foreign Key: `faculty_reviews.faculty_id` → `users.id`

3. **Research Project → Faculty Reviews** (One-to-Many)
   - One project receives many reviews
   - Foreign Key: `faculty_reviews.research_project_id` → `research_projects.id`

4. **User → Saved Items** (One-to-Many, via junction)
   - One user saves many research projects
   - Foreign Keys: `saved_items.user_id` → `users.id`, `saved_items.research_project_id` → `research_projects.id`

---

## Database Statistics

- **Total Tables:** 9 (6 application + 3 system)
- **Total Migrations:** 10 (all applied)
- **Performance Indices:** 7 added for optimization
- **Total Foreign Keys:** 5 main relationships
- **Unique Constraints:** 2 (email in users, user_id + project_id in saved_items)

---

## How to Verify Database in phpMyAdmin

### Step 1: Open phpMyAdmin
- URL: http://localhost/phpmyadmin

### Step 2: Look for projectwise3
- Check left sidebar under "Database" section
- You should see `projectwise3` database listed

### Step 3: View Tables
- Click on `projectwise3` to expand
- You will see all 9 tables:
  - cache
  - failed_jobs
  - job_batches
  - jobs
  - notifications
  - password_reset_tokens
  - research_projects
  - saved_items
  - sessions
  - users

### Step 4: Browse Data
- Click on any table to view its contents
- Click "Browse" to see the data
- Click "Structure" to see the table schema

---

## Sample Query Examples

### Get all users
```sql
SELECT * FROM users;
```

### Get all research projects with student names
```sql
SELECT r.*, u.name as student_name FROM research_projects r 
JOIN users u ON r.user_id = u.id;
```

### Get pending research projects assigned to faculty
```sql
SELECT r.* FROM research_projects r 
WHERE r.status = 'pending' AND r.assigned_faculty_id IS NOT NULL;
```

### Get user's saved research projects
```sql
SELECT p.* FROM research_projects p
JOIN saved_items s ON p.id = s.research_project_id
WHERE s.user_id = 1;
```

### Get faculty reviews for a project
```sql
SELECT fr.*, u.name as reviewer_name FROM faculty_reviews fr
JOIN users u ON fr.faculty_id = u.id
WHERE fr.research_project_id = 1;
```

---

## Database Configuration File

**Location:** `.env` file in project root

```env
DB_CONNECTION=mysql
DB_DATABASE=projectwise3
DB_USERNAME=root
DB_PASSWORD=
```

**Location:** `config/database.php`
- Default connection: mysql
- Host: 127.0.0.1
- Port: 3306
- Charset: utf8mb4

---

## Backup & Restore

### Backup Database
```bash
mysqldump -u root projectwise3 > projectwise3_backup.sql
```

### Restore Database
```bash
mysql -u root projectwise3 < projectwise3_backup.sql
```

---

## Additional Notes

✅ All 10 migrations successfully applied  
✅ Database is fully initialized and ready to use  
✅ All foreign key constraints are active  
✅ All indices are created for optimal performance  
✅ Data persistence is guaranteed with MySQL  

Last Updated: April 20, 2026
