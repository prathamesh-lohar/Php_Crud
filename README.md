# Product CRUD Application

A comprehensive PHP CRUD (Create, Read, Update, Delete) application with MySQL database, responsive Bootstrap UI, and Chart.js visualization.

## Features

✅ **Full CRUD Operations**
- Create new products with form validation
- Read and display all products in a table
- Update existing products inline
- Delete products with confirmation

✅ **Database**
- MySQL database with PDO for secure connections
- Server-side validation
- Sample data included

✅ **Frontend**
- Responsive Bootstrap 5 UI
- Clean and modern design
- Real-time statistics
- Chart.js bar chart visualization

✅ **Security**
- PDO prepared statements (prevents SQL injection)
- Input validation and sanitization
- XSS protection with HTML escaping
- Server-side error handling

✅ **Code Quality**
- Modularized code structure
- Well-documented with comments
- Separate files for config, models, and APIs
- Clean separation of concerns

## Project Structure

```
new/
├── config/
│   └── Database.php           # Database connection class
├── classes/
│   └── Product.php            # Product model class
├── api/
│   └── products.php           # API endpoints for CRUD operations
├── css/
│   └── style.css              # Custom styling
├── js/
│   └── app.js                 # Frontend JavaScript
├── index.php                  # Main application file
├── database.sql               # Database schema and sample data
└── README.md                  # This file
```

## Installation & Setup

### Prerequisites
- XAMPP or similar (PHP 7.4+, MySQL 5.7+)
- Web browser (Chrome, Firefox, Safari, Edge)
- Basic knowledge of PHP and MySQL

### Step 1: Database Setup

1. **Open phpMyAdmin**
   - Navigate to: `http://localhost/phpmyadmin`
   - Login with your credentials (default: root/empty password)

2. **Create Database**
   - Click "New" in the left sidebar
   - Create a database named `crud_app`
   - Click "Create"

3. **Import SQL File**
   - Select the `crud_app` database
   - Go to "Import" tab
   - Choose `database.sql` from the project folder
   - Click "Import"

   **Alternative Method (SQL Query):**
   - Click on the `crud_app` database
   - Go to "SQL" tab
   - Copy and paste the contents of `database.sql`
   - Click "Go"

### Step 2: Configure PHP Application

1. **Update Database Credentials (if needed)**
   - Open `config/Database.php`
   - Modify these values if your database credentials differ:
     ```php
     private $host = 'localhost';      // Your MySQL host
     private $db_name = 'crud_app';    // Database name
     private $db_user = 'root';        // MySQL username
     private $db_password = '';        // MySQL password
     ```

2. **Save Changes**
   - Ensure all files are saved

### Step 3: Access the Application

1. **Start XAMPP Services**
   - Start Apache and MySQL from XAMPP Control Panel

2. **Open in Browser**
   - Navigate to: `http://localhost/new/`
   - The application should load successfully

3. **Verify Database Connection**
   - You should see sample products in the table
   - Chart should display product categories

## How to Use

### Adding a Product

1. Fill in the form fields:
   - **Product Name**: Enter a name (minimum 3 characters)
   - **Price**: Enter a positive number
   - **Category**: Enter category name (minimum 2 characters)
   - **Description**: Enter description (minimum 5 characters)

2. Click "Add Product" button
3. Product will be added to the database and displayed in the table
4. Chart will update automatically

### Viewing Products

- All products are displayed in the table below the form
- Table shows: ID, Name, Price, Category, Description
- Click "Edit" button to modify a product
- Click "Delete" button to remove a product

### Editing a Product

1. Click the "Edit" button on any product row
2. Form fields will auto-populate with product data
3. Modify the fields as needed
4. Click "Update Product" button (button text changes when editing)
5. Product will be updated and changes reflected immediately

### Deleting a Product

1. Click the "Delete" button on any product row
2. Confirm the deletion in the popup
3. Product will be removed from the database
4. Table and chart update automatically

### Viewing Statistics

- **Total Products**: Total count of products in database
- **Total Value**: Sum of all product prices
- **Bar Chart**: Visual representation of products by category

## Data Model

### Products Table

| Field | Type | Description |
|-------|------|-------------|
| id | INT | Primary key, auto-increment |
| name | VARCHAR(255) | Product name |
| price | DECIMAL(10,2) | Product price |
| category | VARCHAR(100) | Product category |
| description | TEXT | Product description |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Record last update date |

## Validation Rules

### Product Name
- Required field
- Minimum 3 characters
- Maximum 255 characters

### Price
- Required field
- Must be a positive number
- Decimal allowed (up to 2 places)

### Category
- Required field
- Minimum 2 characters
- Maximum 100 characters

### Description
- Required field
- Minimum 5 characters
- Can contain up to 65,535 characters

## API Endpoints

All API calls go to `api/products.php` with different actions:

### Get All Products
```
GET api/products.php?action=read
Response: { success: true, data: [...] }
```

### Get Single Product
```
GET api/products.php?action=get&id=1
Response: { success: true, data: {...} }
```

### Get Products by Category
```
GET api/products.php?action=read_by_category
Response: { success: true, data: [{category, count}, ...] }
```

### Create Product
```
POST api/products.php?action=create
Body: { name, price, category, description }
Response: { success: true, message: "..." }
```

### Update Product
```
POST api/products.php?action=update
Body: { id, name, price, category, description }
Response: { success: true, message: "..." }
```

### Delete Product
```
GET api/products.php?action=delete&id=1
Response: { success: true, message: "..." }
```

## Error Handling

The application includes comprehensive error handling:

- **Database Errors**: Connection issues are caught and displayed
- **Validation Errors**: Input validation with user-friendly messages
- **API Errors**: JSON responses with success/error status
- **Frontend Errors**: Toast notifications for user feedback

## Security Features

1. **PDO Prepared Statements**: Protects against SQL injection
2. **Input Validation**: Server-side validation for all inputs
3. **XSS Protection**: HTML escaping for user inputs
4. **Error Handling**: Secure error messages without exposing sensitive info
5. **CORS Headers**: Set for API access control

## Customization

### Modify Product Fields

To add more fields to the product model:

1. **Update Database Schema**
   - Modify `database.sql` to add new columns

2. **Update Product Model**
   - Add properties to `classes/Product.php`
   - Update validation methods

3. **Update API Endpoints**
   - Add new fields to `api/products.php`

4. **Update Frontend**
   - Add form fields to `index.php`
   - Update JavaScript in `js/app.js`

### Customize Colors & Styling

- Modify `css/style.css` for color schemes
- Update Bootstrap theme if desired
- Customize Bootstrap classes in `index.php`

### Change Database Credentials

- Edit `config/Database.php` with your database details
- Ensure the user has proper permissions

## Troubleshooting

### Database Connection Error
- Verify MySQL is running
- Check credentials in `config/Database.php`
- Ensure `crud_app` database exists
- Verify user has database access

### Products Not Loading
- Check browser console for errors (F12)
- Verify `api/products.php` is accessible
- Check PHP error logs in XAMPP

### Chart Not Displaying
- Ensure Chart.js CDN is accessible
- Verify products have categories
- Check browser console for JavaScript errors

### Form Not Submitting
- Check browser console for errors
- Verify all required fields are filled
- Check input validation rules

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Performance Tips

1. **Database Optimization**
   - Indexes are already set on category column
   - Consider adding more indexes for large datasets

2. **Caching**
   - Consider implementing browser caching
   - Add PHP caching for frequently accessed data

3. **Pagination**
   - For large datasets, implement pagination in `Product.php`
   - Update API to support limit/offset parameters

## Future Enhancements

- User authentication and login system
- Product search and filtering
- Pagination for large datasets
- Export to CSV/PDF
- Image upload for products
- Product ratings and reviews
- Admin dashboard
- Mobile app

## License

This project is open source and available for educational purposes.

## Support

For issues or questions, refer to the code comments and documentation within each file.

---

**Created:** December 2024  
**Version:** 1.0.0  
**Status:** Production Ready
