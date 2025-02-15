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
                    modal.style.display = "flex"; // Change to "flex" to match CSS
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

    // Modal de edição em massa
    const openBulkEditBtn = document.getElementById('open-bulk-edit-modal');
    const bulkEditModal = document.getElementById('wp-bulk-edit-modal');
    const closeBulkEditBtn = document.getElementById('close-bulk-edit-modal');

    openBulkEditBtn.addEventListener('click', function() {
        const selectedProductIds = Array.from(document.querySelectorAll('input[name="product[]"]:checked')).map(input => input.value);
        document.getElementById('selected-product-ids').value = selectedProductIds.join(',');
        bulkEditModal.style.display = 'flex';
    });

    closeBulkEditBtn.addEventListener('click', function() {
        bulkEditModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === bulkEditModal) {
            bulkEditModal.style.display = 'none';
        }
    });

    // Coletar IDs dos produtos selecionados
    const bulkEditForm = document.querySelector('#wp-bulk-edit-modal form');
    bulkEditForm.addEventListener('submit', function() {
        const selectedProductIds = Array.from(document.querySelectorAll('input[name="product[]"]:checked')).map(input => input.value);
        document.getElementById('selected-product-ids').value = selectedProductIds.join(',');
    });

    // Seleção de imagem para edição em massa
    const selectBulkImageBtn = document.getElementById('select_bulk_image');
    const bulkPhotoUrlInput = document.getElementById('bulk_photo_url');

    selectBulkImageBtn.addEventListener('click', function(e) {
        e.preventDefault();
        // Se já existir um frame aberto, usa ele
        var frame = wp.media({
            title: "Selecione uma Imagem",
            library: { type: "image" }, // Filtra apenas imagens
            multiple: false, // Apenas uma imagem por vez
            button: { text: "Usar esta imagem" }
        });

        // Ao selecionar uma imagem
        frame.on("select", function () {
            var attachment = frame.state().get("selection").first().toJSON();
            bulkPhotoUrlInput.value = attachment.url; // Coloca a URL no input
        });

        frame.open();
    });
});
