window.addEventListener('DOMContentLoaded', () => {
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        card.style.opacity = '0';
        setTimeout(() => {
            card.style.transition = 'opacity 1s ease-in';
            card.style.opacity = '1';
        }, 200);
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const filters = ['search', 'category', 'material', 'min_price', 'max_price'];
    
    filters.forEach(filterId => {
        document.getElementById(filterId).addEventListener('input', filterProducts);
    });
});

function filterProducts() {
    const params = {
        search: document.getElementById('search').value,
        category: document.getElementById('category').value,
        material: document.getElementById('material').value,
        min_price: document.getElementById('min_price').value,
        max_price: document.getElementById('max_price').value
    };

    fetch(`filter_products.php?${new URLSearchParams(params)}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('products-container').innerHTML = html;
        });
}
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('apply-filters').addEventListener('click', filterProducts);
    
    document.getElementById('reset-filters').addEventListener('click', function() {
        document.getElementById('search').value = '';
        document.getElementById('category').value = '';
        document.getElementById('material').value = '';
        document.getElementById('min_price').value = '';
        document.getElementById('max_price').value = '';
        
        filterProducts();
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const applyBtn = document.getElementById('apply-filters');
    if(applyBtn) {
        applyBtn.addEventListener('click', function() {
           
            filterProducts();
        });
    }
});

async function filterProducts() {
    try {
        const params = new URLSearchParams({
            search: document.getElementById('search').value,
            category: document.getElementById('category').value,
            material: document.getElementById('material').value,
            min_price: document.getElementById('min_price').value,
            max_price: document.getElementById('max_price').value
        });

        // console.log('Отправляем параметры:', params.toString());
        
        const response = await fetch(`filter_products.php?${params}`);
        // console.log('Ответ получен, статус:', response.status);
        
        const html = await response.text();
        document.getElementById('products-container').innerHTML = html;
    } catch(error) {
        console.error('Ошибка фильтрации:', error);
    }
}