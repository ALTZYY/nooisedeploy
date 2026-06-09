/**
 * Nooise Album Music Store - Interactive Search and Filter Script
 * Author: Antigravity Fullstack Developer
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. DOM Elements
    const searchInput = document.getElementById('searchInput');
    const categoryButtons = document.querySelectorAll('.cat-btn');
    const priceCheckboxes = document.querySelectorAll('.price-check');
    const productCards = document.querySelectorAll('.product-card');
    const noResults = document.getElementById('noResults');

    let currentCategory = 'all';

    // 2. Main Filtering Function
    function filterProducts() {
        const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const activePriceFilters = Array.from(priceCheckboxes).filter(cb => cb.checked);
        
        let visibleCount = 0;

        productCards.forEach(card => {
            const title = card.getAttribute('data-title') ? card.getAttribute('data-title').toLowerCase() : '';
            const sku = card.getAttribute('data-id') ? card.getAttribute('data-id').toLowerCase() : '';
            const cat = card.getAttribute('data-cat') ? card.getAttribute('data-cat') : '';
            const price = parseFloat(card.getAttribute('data-price')) || 0;

            // Check if card matches search text
            const matchesSearch = !query || title.includes(query) || sku.includes(query);

            // Check if card matches category
            const matchesCategory = currentCategory === 'all' || cat === currentCategory;

            // Check if card matches price range
            let matchesPrice = false;
            if (activePriceFilters.length === 0) {
                // If no price checkboxes are selected, show nothing
                matchesPrice = false;
            } else {
                matchesPrice = activePriceFilters.some(cb => {
                    const min = parseFloat(cb.getAttribute('data-min')) || 0;
                    const max = parseFloat(cb.getAttribute('data-max')) || Infinity;
                    return price >= min && price <= max;
                });
            }

            // Determine if the product card should be visible
            const shouldShow = matchesSearch && matchesCategory && matchesPrice;

            if (shouldShow) {
                // Apply a subtle fade-in micro-animation
                card.style.display = 'flex';
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // 3. Handle No Results State
        if (noResults) {
            if (visibleCount === 0) {
                noResults.style.display = 'block';
                noResults.style.opacity = '1';
                noResults.style.transition = 'opacity 0.3s ease';
            } else {
                noResults.style.display = 'none';
            }
        }
    }

    // 4. Bind Search Input Events
    if (searchInput) {
        searchInput.addEventListener('input', filterProducts);
        
        // Clear search behavior if user hits escape key
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                filterProducts();
            }
        });
    }

    // 5. Bind Category Button Events
    categoryButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            
            // Remove active class from all buttons
            categoryButtons.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            btn.classList.add('active');
            
            // Update active category and trigger filter
            currentCategory = btn.getAttribute('data-cat') || 'all';
            filterProducts();
        });
    });

    // 6. Bind Price Checkbox Events
    priceCheckboxes.forEach(cb => {
        cb.addEventListener('change', filterProducts);
    });

    // 7. Initial Search Check (e.g. from URL Query Parameters: ?search=daniel)
    const urlParams = new URLSearchParams(window.location.search);
    const initialSearch = urlParams.get('search');
    
    if (initialSearch && searchInput) {
        searchInput.value = initialSearch;
    }

    // Run initial filter to apply active state on page load
    filterProducts();
});
