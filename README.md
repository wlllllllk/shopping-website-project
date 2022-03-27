# IERG4210 Assignment Phase 4

This is the Assignment of IERG4210 Web Programming and Security (2022 Spring)

## Features (focused on those that users can interact and feel directly)

### Phase 4
 
#### Account Manipulation

- **Account** Button
  - Located at the top right corner of the website
  - Users will be redirected to **login.php** upon clicking it, some features are available including
    - Account Registration (before logging in)
    - Account Login (before logging in)
    - Password Changing (after logging in)
    - Logout (after logging in)
    - Admin Panel Access (for admin account only)

#### Authentication

- Admin Panel & Admin Features Execution
  - Server will check if the current user is on admin account
    - if yes, admin panel will be shown or the execution will be process normally
    - if not, users will be redirected to login page (they won't be logout automatically)


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

- admin.php (the admin panel, with multiple forms for interacting with the database)
- admin-process.php (the given PHP file)
- auth.php (for checking if the current account is admin account)
- auth-process.php (for account manipulation, e.g., register, login, change password)
- cart-process.php (for fetching products in shopping list)
- category.php (the category page, displays products under the selected category)
- index.php (the main page of the website)
- login.php (the account page)
- nonce.php (for generating and verifying the nonce to be used by the forms)
- page.php (for getting the total number of products, useful for the pagination feature in the main page)
- product.php (the product page)
- product-fetch.php (for fetching a specific number of products, useful for the pagination feature in the main page)
- cart.db (the database for products)
- user.db (the database for users)
- README.md (this file)

_Everything else are auto-generated_

## How to open the website

- Open index.php
- Visit https://secure.s67.ierg4210.ie.cuhk.edu.hk/index.php in the browser

### To access the Admin Panel

1. If you haven't logged in
   1. Click the **Account** button on the top right corner
   2. Enter credentials for an admin account
   3. Click **Login**
   
2. If you have logged in
   1. Click the **Account** button on the top right corner
   2. Click the **Admin Panel** button at the top right corner 
   
3. You should then be redirected to **admin.php**, i.e., the admin panel, provided that the credentials are valid

#### To interact with the database

1. Click on the action you want to perform listed on the left section
2. A corresponding form will show up
3. Enter the required information
4. Submit the form
5. Wait for the page to refresh
6. If no error is shown, the action is performed successfully
7. Check if the store content is updated as expected (you can see all categories and products from the home page)
   - index.php
   - https://secure.s67.ierg4210.ie.cuhk.edu.hk/index.php

---

_Last Updated: 27-03-2022_
