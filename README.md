# Laravel CSV Import Task

## Project Overview
This Laravel project is designed to:
1. Parse and normalize data from a `.csv` file and populate a MySQL database.
2. Expose REST API endpoints for accessing and managing the data.
3. Optionally generate a `.csv` file containing products of a specific category (Bonus Task).

## Features

### CSV Import and Database Population
- Parses a `.csv` file and normalizes the data.
- Populates two database tables:
    - **categories** (unique category names).
    - **products** (products linked to categories).
- Implemented as an Artisan command that can be run via CLI:

```bash
php artisan csv:import path/to/file.csv
```

### REST API Endpoints
The project provides the following REST API routes:

#### Categories Endpoints
- **GET /api/categories**: Retrieve all categories.
- **PUT /api/categories/{id}**: Update a category's name.
- **DELETE /api/categories/{id}**: Delete a category.

#### Products Endpoints
- **GET /api/products**: Retrieve all products.
- **GET /api/products/category/{category_id}**: Retrieve all products belonging to a specific category.
- **PUT /api/products/{id}**: Update a product's details (e.g., name, price).
- **DELETE /api/products/{id}**: Delete a product.

#### Bonus Endpoint
- **GET /api/categories/{id}/export**: Generate and download a `.csv` file containing all products in a specific category.
    - Filename format: `category_name_YYYY_MM_DD-HH_MM.csv`.
    - Non-alphanumeric characters in the category name are replaced with `_`.

## Getting Started

### Prerequisites
- PHP 8.x
- Composer
- MySQL
- Laravel 10.x

### Installation
1. Clone the repository:
   ```bash
   git clone <repo_url>
   cd <repo_name>
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Set up the environment:
    - Copy the `.env.example` file to `.env`:
      ```bash
      cp .env.example .env
      ```
    - Configure database credentials in the `.env` file.

4. Run database migrations:
   ```bash
   php artisan migrate
   ```

5. Serve the application locally:
   ```bash
   php artisan serve
   ```

### Importing the CSV File
Run the following command to import a `.csv` file:
```bash
php artisan csv:import path/to/file.csv
```
Ensure the `.csv` file has headers matching the expected format (e.g., `category,product_name,price`).

### API Usage
Use tools like Postman or cURL to test the API endpoints. Ensure the server is running (`php artisan serve`) and send requests to the appropriate routes (e.g., `http://localhost:8000/api/categories`).

### Bonus Task: Exporting a CSV File
To generate a `.csv` file for products in a specific category, send a GET request to:
```
GET /api/categories/{id}/export
```
The file will be downloaded automatically.

## Development Notes

### Database Schema
- **categories**:
    - `id`: Primary key.
    - `name`: Unique name of the category.
    - `created_at`, `updated_at`: Timestamps.

- **products**:
    - `id`: Primary key.
    - `name`: Name of the product.
    - `category_id`: Foreign key referencing `categories(id)`.
    - `price`: Price of the product.
    - `created_at`, `updated_at`: Timestamps.

### Git Workflow
- Make granular commits with descriptive messages.
- Example:
  ```bash
  git add .
  git commit -m "Implemented CSV import command"
  git commit -m "Added API endpoints for categories"
  git commit -m "Implemented bonus task: CSV export"
  ```

## License
This project is licensed under the MIT License. See the LICENSE file for details.

---
If you have any questions or need further assistance, feel free to contact me.
