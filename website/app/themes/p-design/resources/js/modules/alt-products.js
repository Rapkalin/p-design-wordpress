$('.alt-product').on('click', function (e) {
    e.preventDefault();
    const mainProduct = document.getElementById('image');
    let img = mainProduct.getElementsByTagName('img')[0];

    // We reset all items status
    const currentItems = document.querySelectorAll('.alt-product');
    currentItems.forEach((item) => {
        item.classList.remove('alt-active');
    })

    // We update the image of the main with the current selected target
    const currentItem = e.currentTarget;
    currentItem.classList.add('alt-active');

    img.src = currentItem.src;
});