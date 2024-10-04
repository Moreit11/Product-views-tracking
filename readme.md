# Product View Tracking Integration

## Description
This WordPress plugin tracks and stores product view data in compliance with GDPR regulations. It integrates with the Complianz plugin to ensure user consent before collecting any data. The plugin uses local storage and cookies to track product views and allows for easy retrieval of this information.

## Author
Moritz Reitz  
Email: [plugins@moritzreitz.com](mailto:plugins@moritzreitz.com)  
GitHub: [Moreit11](https://github.com/Moreit11)

## Features
- Tracks product views and stores data securely.
- Complies with GDPR using the Complianz plugin for user consent.
- Supports integration with various post types.
- Allows customization to track additional product data.

## Installation
1. Download the plugin files and unzip them.
2. Upload the `product-view-tracking-integration` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Ensure that the Complianz plugin is installed and configured to manage cookie consent.

## Usage
To display recently viewed products or items, use the following shortcode in any post or page:
```
[recently_viewed_products]
```

## Adding Your Own Post Type
To track and display your own custom post types:
1. Open the `your-plugin.php` file.
2. Locate the `WP_Query` section inside the function that handles displaying viewed products.
3. Change the `'post_type' => 'any'` line to include your custom post type(s), for example:
   ```php
   'post_type' => array('your_custom_post_type', 'another_product_type')
   ```
4. Save the changes and refresh your site.

## Integration with Other Cookie Tracking Plugins/Services
This plugin checks for user consent using the Complianz plugin. If you are using other cookie consent management plugins or services, you may need to modify the consent checking function to align with their consent checks.

### Example Integration
If using a different plugin, update the consent checking function as follows:
```php
function hasConsentForProductTracking() {
    // Example for a different plugin
    return isset($_COOKIE['another_plugin_cookie']) && $_COOKIE['another_plugin_cookie'] === 'allow';
}
```

## Security Considerations
- The plugin ensures all user data is sanitized and validated.
- Cookies are set with the `Secure`, `HttpOnly`, and `SameSite=Strict` attributes for enhanced security.
- It checks for user consent before tracking any product views.

## License
This plugin is proprietary software. It may not be redistributed or sold without the explicit written consent of the author.