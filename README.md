# Student Placement Management System

A web-based application designed to manage and track student placement records efficiently. This system helps educational institutions maintain and monitor student placement data, including company placements, package details, and placement statistics.

## Features

- **Student Management**
  - Add new student records
  - Edit existing student information
  - View comprehensive student details
  - Track placement status

- **Department Management**
  - Support for multiple departments:
    - Computer Science
    - Information Technology
    - Electronics
    - Mechanical
    - Civil
    - Data Science
    - Cyber Security
    - AI & ML
    - IoT

- **Placement Tracking**
  - Record company placements
  - Track placement status (Placed/Not Placed)
  - Manage package details
  - Monitor placement statistics

- **Data Import**
  - Import student records from CSV files
  - Bulk data management

- **Dashboard**
  - View total number of students
  - Track placement statistics
  - Monitor average package details
  - Interactive UI with animations

## Technology Stack

- PHP
- MySQL
- HTML5
- CSS3
- Bootstrap 5
- JavaScript
- XAMPP Server

## Setup Instructions

1. **Prerequisites**
   - Install XAMPP (Apache and MySQL)
   - Web browser
   - Git

2. **Installation Steps**
   ```bash
   # Clone the repository
   git clone https://github.com/Ashokkalluri26/student-placement-magement-system.git

   # Move to xampp/htdocs directory
   cd /xampp/htdocs/

   # Import the database
   # Open phpMyAdmin and import the SQL file from the database folder
   ```

3. **Configuration**
   - Update database credentials in `config.php`
   - Start Apache and MySQL services in XAMPP
   - Access the application through: `http://localhost/placement_student/`

## Database Structure

The system uses a MySQL database with the following main table:

- **students**
  - id (Primary Key)
  - student_name
  - roll_number
  - email
  - phone
  - department
  - cgpa
  - company
  - package
  - placement_status

## Usage

1. **Adding a Student**
   - Navigate to "Add Student" page
   - Fill in student details
   - Submit the form

2. **Viewing Records**
   - Go to "View Records" page
   - Browse through student records
   - Use filters if needed

3. **Importing Data**
   - Access "Import from CSV" page
   - Upload CSV file with student data
   - Review and confirm import

4. **Editing Records**
   - Click on "Edit" button next to student record
   - Update necessary information
   - Save changes

## Contributing

Feel free to fork this repository and submit pull requests for any improvements.

## License

This project is open source and available under the [MIT License](LICENSE). 