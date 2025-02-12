document.addEventListener("DOMContentLoaded", function () {
    // Modal de visualização de produto
    const modal = document.getElementById("wp-product-modal");
    const modalBody = document.getElementById("wp-product-modal-body");
    const closeModal = document.querySelector(".wp-product-modal-close");

    document.querySelectorAll(".view-product").forEach(button => {
        button.addEventListener("click", function () {
            const productId = this.dataset.id;

            fetch(ajaxurl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
                    action: "get_product_details",
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modalBody.innerHTML = data.data;
                    modal.style.display = "block";
                } else {
                    alert("Erro ao carregar os detalhes do produto.");
                }
            });
        });
    });

    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // Modal de importação CSV
    const openCsvBtn = document.getElementById('open-csv-import-modal');
    const csvModal = document.getElementById('wp-csv-import-modal');
    const closeCsvBtn = document.getElementById('close-csv-import-modal');

    openCsvBtn.addEventListener('click', function() {
        csvModal.style.display = 'flex';
    });

    closeCsvBtn.addEventListener('click', function() {
        csvModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === csvModal) {
            csvModal.style.display = 'none';
        }
    });
});
