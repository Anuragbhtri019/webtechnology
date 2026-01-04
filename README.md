# Web Technology Learning Repository

A comprehensive collection of web development examples and projects covering HTML, CSS, JavaScript, PHP, and XML technologies.

## Author

**Anurag Bhattarai**

## 📋 Table of Contents

- [Overview](#overview)
- [Technologies Covered](#technologies-covered)
- [Repository Structure](#repository-structure)
- [Prerequisites](#prerequisites)
- [Getting Started](#getting-started)
- [Project Descriptions](#project-descriptions)
- [Usage](#usage)
- [Contributing](#contributing)

## Overview

This repository serves as a learning resource and practical reference for web development technologies. It contains hands-on examples, projects, and implementations covering fundamental to intermediate concepts in web development.

## Technologies Covered

- **HTML5** - Markup and structure
- **CSS3** - Styling and layouts
- **JavaScript** - Client-side scripting and DOM manipulation
- **jQuery** - JavaScript library for simplified scripting
- **PHP** - Server-side scripting
- **XML/XSL/XSD** - Data representation and transformation

## Repository Structure

```
webtechnology/
├── html css/          # HTML and CSS examples
├── js/                # JavaScript and jQuery examples
├── php/               # PHP server-side applications
│   └── Serverside/    # Advanced PHP implementations
└── xml/               # XML, XSL, and XSD examples
```

## Prerequisites

To run and test the files in this repository, you'll need:

- **Web Browser** (Chrome, Firefox, Safari, or Edge)
- **Text Editor/IDE** (VS Code, Sublime Text, or any preferred editor)
- **Local Web Server** (for PHP files):
  - XAMPP, WAMP, MAMP, or
  - PHP built-in server (`php -S localhost:8000`)
- **Basic understanding** of web development concepts

## Getting Started

### For HTML, CSS, and JavaScript files:

1. Clone the repository:
   ```bash
   git clone https://github.com/Anuragbhtri019/webtechnology.git
   cd webtechnology
   ```

2. Open any HTML file directly in your web browser:
   ```bash
   # Navigate to the desired directory and open files
   cd "html css"
   # Open any .html file in your browser
   ```

### For PHP files:

1. Ensure PHP is installed on your system
2. Navigate to the PHP directory:
   ```bash
   cd php/Serverside
   ```
3. Start the PHP built-in server:
   ```bash
   php -S localhost:8000
   ```
4. Access the files via `http://localhost:8000/filename.php`

### For XML files:

XML files can be opened directly in a web browser or processed using appropriate tools for validation and transformation.

## Project Descriptions

### HTML & CSS (`html css/`)

Contains various HTML and CSS examples demonstrating:

- **2basic.html** - Basic HTML structure
- **3homepage.html** - Homepage design example
- **4department.html** - Department page layout
- **5text.html** - Text formatting examples
- **6tufaculty.html** - Faculty page design
- **7imagemap.html** - Image mapping implementation
- **8routine.html** - Schedule/routine table
- **9frameset.html** - Frameset examples
- **10csstypes.html** - Different CSS implementation types
- **11imagegallery.html** - Image gallery implementation
- **12cv.html** - CV/Resume template
- **13navbar.html** - Navigation bar designs
- **14registration_form.html** - User registration form
- **15layout.html** - Page layout examples
- **16loksewa.html** - Loksewa-related page
- **17gradesheet.html** - Grade sheet template
- **18invoice.html** - Invoice template
- **Frame examples** (1stframe.html through 4thframe.html) - Iframe implementations
- **style.css** - Stylesheet file
- **imagemap.png** - Image resource for image mapping

### JavaScript (`js/`)

JavaScript examples covering:

- **1dom.html** - DOM manipulation and To-Do List
- **2number.html** - Number operations
- **3popupbox.html** - Alert, confirm, and prompt boxes
- **4paragraph.html** - Paragraph manipulation
- **5array.html** - Array operations
- **6object.html** - Object-oriented JavaScript
- **7calculator.html** - Calculator implementation
- **8form.html** - Form validation and handling
- **9frame&window.html** - Frame and window operations
- **10dateandtime.html** - Date and time manipulation
- **11cookie.html** - Cookie management
- **12animation.html** - JavaScript animations
- **13chessboard.html** - Chessboard pattern creation
- **14jquery.html** - jQuery library usage
- **home.html, about.html, contact.html** - Navigation pages

### PHP (`php/Serverside/`)

Server-side PHP applications including:

- **1calculator.php** - Server-side calculator
- **2interest.php** - Interest calculation
- **auth_system.php** - Authentication system
- **login.php** - User login functionality
- **register.php** - User registration
- **registration.php** - Registration form handler
- **session_manager.php** - Session management
- **cookie_manager.php** - Cookie operations
- **book_store.php** - Book storage (CRUD - Create)
- **book_retrive.php** - Book retrieval (CRUD - Read)
- **book_modify.php** - Book modification (CRUD - Update)
- **book_delete.php** - Book deletion (CRUD - Delete)
- **uploads/** - Directory for file uploads
- **note.txt** - Additional notes/documentation

### XML (`xml/`)

XML examples demonstrating:

- **books.xml, book1.xml** - Book data in XML format
- **books.xsd, book1.xsd** - XML Schema definitions for validation
- **topics.xml, 6data.xml, song.xml, oceans.xml** - Various XML data examples
- **topics.xsl, 6data.xsl, oceans.xsl** - XSL transformation stylesheets

## Usage

### Example 1: Running HTML/CSS Examples

```bash
# Simply open any HTML file in your browser
open "html css/2basic.html"  # macOS
start "html css/2basic.html" # Windows
xdg-open "html css/2basic.html" # Linux
```

### Example 2: Running JavaScript Examples

```bash
# Open JavaScript files directly in browser
open js/1dom.html
```

### Example 3: Running PHP Examples

```bash
# Start PHP server in the Serverside directory
cd php/Serverside
php -S localhost:8000

# Then access via browser:
# http://localhost:8000/1calculator.php
```

### Example 4: Viewing XML with XSL

```bash
# Open XML files with associated XSL in browser
open xml/topics.xml
```

## Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the issues page and submit pull requests.

### How to Contribute:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Learning Path

Recommended learning sequence:

1. **Start with HTML & CSS** (`html css/`) - Learn basic structure and styling
2. **Move to JavaScript** (`js/`) - Add interactivity
3. **Explore PHP** (`php/Serverside/`) - Understand server-side processing
4. **Study XML** (`xml/`) - Learn data representation and transformation

## Notes

- All files are educational and designed for learning purposes
- Some examples may require a local server environment (especially PHP)
- Ensure proper file permissions when working with file upload features
- Database connectivity in PHP examples may require additional configuration

## License

This project is created for educational purposes. Please refer to the repository license for more details.

---

**Happy Learning! 🚀**

For questions or suggestions, please open an issue in the repository.
