document.addEventListener("DOMContentLoaded", function () {
    // Implementação de busca dinâmica
    // let searchInput = document.querySelector(".elementor-search-form__input");
    // ...existing code...

    // Busca de produto pelo código
    const searchButton = document.getElementById("wp-product-search-button");
    const searchInputField = document.getElementById("wp-product-search-input");
    // const searchResult = document.getElementById("wp-product-search-result");
    const modal = document.getElementById("wp-product-modal");
    const modalBody = document.getElementById("wp-product-modal-body");

    if (searchButton && searchInputField) {
        searchButton.addEventListener("click", function () {
            const productCode = searchInputField.value.trim();

            if (productCode === "") {
                alert("Por favor, digite um código de produto.");
                return;
            }

            fetch(wpProductAjax.ajaxurl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
                    action: "search_product_by_code",
                    product_code: productCode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modalBody.innerHTML = data.data;
                    modal.style.display = "flex";
                } else {                    
                    alert(`${data.data}`);
                    // searchResult.innerHTML = `<p>${data.data}</p>`;
                }
            });
        });
    }

    // Fechar modal
    const closeModal = document.querySelector(".wp-product-modal-close");
    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});
