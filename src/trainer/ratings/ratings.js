// JavaScript for Ratings Page
document.addEventListener('DOMContentLoaded', function () {
    // Initialize the "Read More" functionality
    initReadMoreButtons();

    // Initialize pagination
    initPagination();
});

// Function to initialize "Read More" buttons
function initReadMoreButtons() {
    const reviewContents = document.querySelectorAll('.review-content');

    reviewContents.forEach(content => {
        const paragraph = content.querySelector('p');

        // Check if the content is overflowing and needs a "Read More" button
        if (paragraph && paragraph.scrollHeight > paragraph.clientHeight) {
            // Create read more button
            const readMoreBtn = document.createElement('span');
            readMoreBtn.className = 'read-more';
            readMoreBtn.textContent = 'Read More';
            content.appendChild(readMoreBtn);

            // Add click event to toggle expansion
            readMoreBtn.addEventListener('click', function () {
                content.classList.toggle('expanded');
                readMoreBtn.textContent = content.classList.contains('expanded') ? 'Read Less' : 'Read More';
            });
        }
    });
}

// Function to initialize pagination
function initPagination() {
    const reviewsPerPage = 5;
    const reviewItems = document.querySelectorAll('.review-item');
    const totalReviews = reviewItems.length;
    const totalPages = Math.ceil(totalReviews / reviewsPerPage);

    // If we have only one page or no reviews, don't show pagination
    if (totalPages <= 1) return;

    // Create pagination container if it doesn't exist
    let paginationContainer = document.querySelector('.pagination');
    if (!paginationContainer) {
        paginationContainer = document.createElement('div');
        paginationContainer.className = 'pagination';
        document.querySelector('.reviews-list').after(paginationContainer);
    }

    // Initialize with page 1
    let currentPage = 1;

    // Function to update pagination UI
    function updatePagination() {
        paginationContainer.innerHTML = '';

        // Previous button
        const prevButton = document.createElement('button');
        prevButton.className = 'pagination-button';
        prevButton.textContent = '←';
        prevButton.disabled = currentPage === 1;
        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
                showReviewsForPage(currentPage);
            }
        });
        paginationContainer.appendChild(prevButton);

        // Page numbers
        const maxVisiblePages = 5;
        const startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        const endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        // First page if not in range
        if (startPage > 1) {
            addPageButton(1);
            if (startPage > 2) {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'pagination-ellipsis';
                ellipsis.textContent = '...';
                paginationContainer.appendChild(ellipsis);
            }
        }

        // Page buttons
        for (let i = startPage; i <= endPage; i++) {
            addPageButton(i);
        }

        // Last page if not in range
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'pagination-ellipsis';
                ellipsis.textContent = '...';
                paginationContainer.appendChild(ellipsis);
            }
            addPageButton(totalPages);
        }

        // Next button
        const nextButton = document.createElement('button');
        nextButton.className = 'pagination-button';
        nextButton.textContent = '→';
        nextButton.disabled = currentPage === totalPages;
        nextButton.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
                showReviewsForPage(currentPage);
            }
        });
        paginationContainer.appendChild(nextButton);
    }

    // Helper to add a page button
    function addPageButton(pageNum) {
        const pageButton = document.createElement('button');
        pageButton.className = 'pagination-button' + (pageNum === currentPage ? ' active' : '');
        pageButton.textContent = pageNum;
        pageButton.addEventListener('click', () => {
            currentPage = pageNum;
            updatePagination();
            showReviewsForPage(currentPage);
        });
        paginationContainer.appendChild(pageButton);
    }

    // Function to show reviews for the current page
    function showReviewsForPage(page) {
        const startIdx = (page - 1) * reviewsPerPage;
        const endIdx = startIdx + reviewsPerPage;

        reviewItems.forEach((item, index) => {
            item.style.display = (index >= startIdx && index < endIdx) ? 'block' : 'none';
        });

        // Scroll to the top of the reviews section
        document.querySelector('.reviews-section').scrollIntoView({ behavior: 'smooth' });
    }

    // Initialize pagination
    updatePagination();
    showReviewsForPage(currentPage);
}