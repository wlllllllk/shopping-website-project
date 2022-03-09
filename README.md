# IERG4210 Assignment Phase 3

This is the Assignment of IERG4210 Web Programming and Security (2022 Spring)

## Features

### Phase 3

#### Dynamic Shopping List

- **Add to Cart** Button

  - When it is pressed, the button will be temporarily disabled until the adding process is completed, mainly designed because of the asynchronicity of AJAX requests.

- Quantity Input

  - If the value entered by users in the shopping list is not a number, visible warning will be shown.
  - ~~If value drops below 1, users will be prompt to delete the product. If they do not intend to delete the item, the value will be automatically reset to 1.~~
  - If value drops below 1, item will be deleted automatically without prompting. This is because users can easily disable the browser's built-in prompt and think there is something wrong with the website.

- Auto Restore

  - The shopping list is automatically restored if user browse another page.

- Pagination
  - Pagination is implemented in the main page, when the page is loaded, the first page is automatically loaded.
  - Users can manually choose to browser another page.

## Contents

### Folder

- css (for storing all the scss files, as well as the auto-compiled css files)
- icon (for storing favicon)
- images (for storing all images to be used in this assignment)
- js (for input validations, drag-and-drop feature, and AJAX request)
- lib (contains most of the PHP functions)
- old (HTML files from Phase 1, for development testing only)

### Files

- admin-process.php (the given PHP file)
- admin.php (the admin panel, with multiple forms for interacting with the database)
- cart-process.php (for fetching products in shopping list)
- category.php (the category page, displays products under the selected category)
- index.php (the main page of the website)
- page.php (for getting the total number of products, useful for the pagination feature in the main page)
- product-fetch.php (for fetching a specific number of products, useful for the pagination feature in the main page)
- product.php (the product page)
- cart.db (the database)
- README.md (this file)

_Everything else are auto-generated_

## How to open the website

- Open index.php
- Visit http://52.205.54.184 in the browser

### To access the Admin Panel

- Click the **Admin** button on the top right corner

#### To interact with the database

1. Click on the action you want to perform listed on the left section
2. A corresponding form will show up
3. Enter the required information
4. Submit the form
5. Wait for the page to refresh
6. If no error is shown, the action is performed successfully
7. Check if the store content is updated as expected (you can see all categories and products from the home page)
   - index.php
   - http://52.205.54.184

---

_Last Updated: 09-03-2022_
