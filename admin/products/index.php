<!DOCTYPE html>
<html lang="en">
<?php
include_once "../../layout/default_head.php";
?>
<body>
<!-- MAIN LAYOUT -->
<article class="w-8/12 mx-auto">
    <?php
    include_once "../../components/topbar.php";
    ?>
    <main class="grid grid-cols-[256px_1fr] my-4">
        <?php
        include_once "../../components/admin_navigation.php";
        ?>
        <div>
            <div class="p-4 border-b-2 border-gray-50 flex justify-between items-center">
                <h1 class="font-bold text-2xl">Products</h1>
                <a href="/admin/add-product">
                    <button class="p-2 px-6 bg-blue-700 hover:bg-blue-900 text-white font-semibold rounded-md shadow-xl">+ Add Product</button>
                </a>
            </div>
            <div>
                <table
                        class="w-full text-sm text-left rtl:text-right text-gray-500"
                >
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Product name</th>
                        <th scope="col" class="px-6 py-3">Price</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="products-container">

                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <!-- FOOTER -->
    <?php
    include_once "../../components/footer.php";
    ?>
</article>
<script>
    const params = new URLSearchParams(window.location.search);
    const categoryId = params.get("category_id");
    let products = [];
    const productsContainer = document.querySelector("#products-container");

    const handleProductsLoad = async () => {
        productsContainer.innerHTML = ``;
        try {
            const apiUrl = categoryId ? `/api/products/?category_id=${categoryId}` : "/api/products/";
            const response = await fetch(apiUrl);
            const _products = await response.json();
            if (!_products.success)
                throw new Error(_products.reason);
            products = _products.data;

            for (let product of products) {
                productsContainer.innerHTML += `
                    <tr class="bg-white border-b">
                        <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                        >
                            ${product.name}
                        </th>
                        <td class="px-6 py-4">₱${parseFloat(product.price).toFixed(2)}</td>
                        <td class="px-6 py-4 flex gap-2 flex-wrap">
                            <a href="/admin/edit-product/?product_id=${product.id}">
                                <button
                                        class="bg-gray-100 px-2 py-1 rounded-sm hover:bg-gray-200"
                                >
                                    Edit
                                </button>

                            </a>
<button
                                    class="bg-red-100 rounded-md px-2 py-1 rounded-sm text-red-500 hover:bg-red-200"
                                    data-product-id="${product.id}" id="delete-product-${product.id}"
                                >
                                Delete
                            </button>
                        </td>
                    </tr>
`;
                const deleteButton = document.querySelector(`#delete-product-${product.id}`);
                deleteButton.addEventListener("click", () => handleProductDelete(product.id));
            }
        } catch (error) {
            console.error(error);
        }
    }

    const handleProductDelete = async (productId) => {
        const product = products.find(product => product.id == productId);
        if (!product) {
            alert("Product not found");
            return;
        }
        const confirmation = confirm(`Are you sure you want to delete ${product.name}?`);
        if (!confirmation) return;
        const accessToken = localStorage.getItem("access_token");
        const apiResponse = await fetch(`/api/products/?product_id=${productId}&access_token=${accessToken}`, {
            method: "DELETE"
        });
        const data = await apiResponse.json();
        if (!data.success) {
            alert("Unable to delete that product. You must first delete all the orders associated with that product.");
            return;
        }
        products = products.filter(product => product.id !== productId);
        handleProductsLoad();
    }

    const handlePageLoad = () => {
        handleProductsLoad();
        document.addEventListener('click', function(event) {
            if (event.target.matches('[data-product-id]')) {
                handleProductDelete(event.target.getAttribute('data-product-id'));
            }
        });
    }

    window.addEventListener("load", handlePageLoad);
</script>
</body>
</html>
