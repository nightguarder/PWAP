# Notes Manager Documentation

## Version 1.0 - Initial Release

This document outlines the current state of the Notes Manager system and plans for future updates.

### Current Implementation (v1.0)

The Notes Manager v1.0 provides a simple but powerful Markdown-based note-taking system with the following features:

1. **User Authentication**
   - Secure login and registration
   - Session management with timeouts
   - Password hashing and security measures

2. **Note Management**
   - Create, edit, and delete notes
   - Full Markdown support with EasyMDE editor
   - Markdown preview and rendering using Parsedown

3. **Simple Organization**
   - Chronological listing of notes by update time
   - Full-text search functionality
   - Clean, responsive user interface

### Database Schema (v1.0)

The current database schema is intentionally simplified for the initial release:

```sql
CREATE TABLE IF NOT EXISTS `Notes` (
  `note_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` LONGTEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_encrypted` BOOLEAN DEFAULT FALSE,
  FOREIGN KEY (`user_id`) REFERENCES `Accounts`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Files Structure

The Notes Manager follows a simple MVC-inspired structure:

- `/lib/`
  - `login.php` - User login interface
  - `register.html` - User registration interface
  - `notes.php` - Note creation and editing interface
  - `vault.php` - Notes listing and search interface
  - `view.php` - Note viewing with Markdown rendering
  
- `/config/`
  - `authenticate.php` - Login authentication logic
  - `register.php` - User registration logic
  - `authCheck.php` - Authentication verification
  - `timeout.php` - Session management
  - `logout.php` - Session termination
  
- `/vendor/`
  - `/parsedown/` - Markdown parsing library

### Planned Updates for Version 2.0

The next major update will include:

1. **Categories System**
   - Organize notes by user-defined categories
   - Color-coding for visual organization
   - Filtering notes by category

2. **Tags System**
   - Apply multiple tags to notes
   - Filter and search by tags
   - Tag cloud visualization

3. **Subjects Hierarchy**
   - Group categories under subjects (e.g., "Mathematics", "Computer Science")
   - Provides an additional layer of organization
   - Perfect for students organizing notes by courses

4. **Additional Features**
   - Note sharing capabilities
   - Export to PDF/HTML
   - More advanced search options
   - Enhanced styling options for notes

### Implementation Plan for v2.0

The future update will require the following changes:

1. **Database Updates**
   - Create the Categories, Tags, NoteTags, and Subjects tables
   - Add category_id column to Notes table
   - Add subject_id column to Categories table

2. **UI Updates**
   - Category and tag selection on note creation/editing page
   - Category and tag filtering on the vault page
   - Subject selection and management interfaces

3. **Backend Updates**
   - Modify queries to include category and tag information
   - Add management interfaces for categories, tags, and subjects
   - Update search functionality to include filters

Refer to the schema file `notes_future_update.sql` for the detailed database changes planned for v2.0.

## Installation Instructions

1. Set up a PHP-enabled web server with MySQL
2. Import the database schema from `schema/notes_schema.sql`
3. Configure your database connection in the config files
4. Download and place Parsedown in the vendor directory
5. Ensure write permissions are set correctly for the server

## Contributing

Contributions to the Notes Manager are welcome. Please follow these steps:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is licensed under the MIT License.
