// Check Complianz consent status for statistics or marketing cookies
function hasConsentForStatisticsOrMarketing() {
    let statisticsConsent = getCookie('cmplz_statistics');
    let marketingConsent = getCookie('cmplz_marketing');
    return (statisticsConsent === 'allow' || marketingConsent === 'allow');
}

// Get the value of a specific cookie
function getCookie(name) {
    let value = "; " + document.cookie;
    let parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
}

// Track the recently viewed product if consent is given
function trackProductView(productId) {
    if (hasConsentForStatisticsOrMarketing()) {
        let viewedProducts = JSON.parse(localStorage.getItem('recentlyViewedProducts')) || [];
        
        if (!viewedProducts.includes(productId)) {
            viewedProducts.push(productId);

            // Limit the stored products (e.g., store only the last 5 products)
            if (viewedProducts.length > 5) {
                viewedProducts.shift(); // Remove the oldest viewed product
            }

            localStorage.setItem('recentlyViewedProducts', JSON.stringify(viewedProducts));

            // Store the product IDs in a cookie to be used on the server side
            const cookieValue = JSON.stringify(viewedProducts);
            document.cookie = `recently_viewed_products=${cookieValue}; path=/; Secure; HttpOnly; SameSite=Strict;`;
        }
    }
}

// Run the tracking code after consent has been checked
cmplz_run_after_all_consents_are_checked(function() {
    if (typeof myProduct !== 'undefined' && myProduct.productId) {
        trackProductView(myProduct.productId);
    }
});

// Clear recently viewed products if consent is revoked
document.addEventListener('cmplz_revoke', function() {
    localStorage.removeItem('recentlyViewedProducts');
    document.cookie = 'recently_viewed_products=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/; Secure; HttpOnly;'; // Clear cookie with security attributes
});