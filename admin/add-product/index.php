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
            <div class="p-4 border-b-2 border-gray-50">
                <h1 class="font-bold text-2xl">Add Product</h1>
            </div>
            <div class="p-4">
                <form id="add-product-form">
                    <div id="product-images-container" class="grid grid-cols-4 gap-2">
                        <div class="w-full h-[128px] bg-gray-200 rounded-md">

                        </div>
                        <div class="w-full h-[128px] bg-gray-200 rounded-md">

                        </div>
                        <div class="w-full h-[128px] bg-gray-200 rounded-md">

                        </div>
                        <div class="w-full h-[128px] bg-gray-200 rounded-md">

                        </div>
                    </div>
                    <div>
<!--                        <label for="product-images" class="font-semibold mb-2">Upload Product Images</label>-->
                        <label id="upload-dummy" for="product-images">
                            <button type="button" class="w-full bg-blue-600 p-2 px-4 font-semibold text-white rounded-md hover:bg-blue-700 mt-2">Upload a Product Image</button>
                        </label>
                        <input id="product-images" type="file" class="hidden" multiple/>
<!--                        <div  class="border-4 rounded-xl p-4 w-full h-[256px] bg-gray-100 border-dashed border-gray-400 flex items-center justify-center flex-col mt-2">-->
<!--                            <img class="w-20" src="/images/icons/cloud-upload-svgrepo-com.svg"/>-->
<!--                            <p class="font-bold text-gray-400 mt-2">Drag or Click here to upload file</p>-->
<!--                        </div>-->
                    </div>
                    <div class="mt-2">
                        <label for="product-name" class="font-semibold mb-2">Product Name</label>
                        <input id="product-name" class="p-2 px-4 bg-gray-100 rounded-md w-full text-sm" placeholder="Enter product name here"/>
                    </div>
                    <div class="grid grid-cols-2 mt-4 items-center gap-2">
                        <div>
                            <label for="product-category" class="font-semibold mb-2">Product Category</label>
                            <select id="product-category" class="p-2 px-4 bg-gray-100 rounded-md w-full text-sm">

                            </select>
                        </div>
                        <div>
                            <label for="product-price" class="font-semibold mb-2">Price</label>
                            <input id="product-price" class="p-2 px-4 bg-gray-100 rounded-md w-full text-sm" type="number" placeholder="Enter Price"/>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="product-description" class="font-semibold mb-2">Product Description</label>
                        <textarea id="product-description" class="p-2 px-4 bg-gray-100 rounded-md w-full min-h-[256px] text-sm" placeholder="Enter product description here"></textarea>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="p-2 px-6 bg-blue-700 hover:bg-blue-900 text-white font-semibold rounded-md shadow-xl w-full">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <!-- FOOTER -->
    <?php
    include_once "../../components/footer.php";
    ?>
</article>
<script defer>
    const form = document.querySelector("#add-product-form");

    const productImagesContainer = document.querySelector("#product-images-container");
    let productImages = [];

    const uploadInput = document.querySelector("#product-images");
    const uploadDummy = document.querySelector("#upload-dummy");

    const handleFileUpload = async () => {
        const files = uploadInput.files;
        const accessToken = localStorage.getItem("access_token");
        console.log(files);
        for (let i = 0; i < files.length; i++) {
            const formData = new FormData();
            formData.append("fileToUpload", files[i]);
            formData.append("access_token", accessToken);
            const apiResponse = await fetch("/api/files/upload/", {
                method: "POST",
                body: formData
            });
            const data = await apiResponse.json();
            if (!data.success) {
                alert(`Failed to upload file ${files[i].name}: ${data.reason}`);
                continue;
            }
            console.log(data.data);
            productImages.push(data.data.file_name);
            handleProductImagesChange();
        }
    }

    const handleProductImagesChange = () => {
        productImagesContainer.innerHTML = "";

        productImages.forEach(image => {
            const imageElement = document.createElement("div");
            imageElement.classList.add("overflow-hidden", "rounded-lg");
            imageElement.innerHTML = `
                <img src="/uploads/${image}" class="w-full object-cover rounded-lg"/>
            `;
            const removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.classList.add("w-full", "mt-1", "p-2", "px-4", "bg-red-600", "rounded-lg", "font-semibold", "text-white", "hover:bg-red-700");
            removeButton.textContent = "Remove";
            removeButton.addEventListener("click", () => {
                productImages = productImages.filter(img => img !== image);
                handleProductImagesChange();
            });
            imageElement.appendChild(removeButton);
            productImagesContainer.appendChild(imageElement);
        });
        if (productImages.length == 0) {
            productImagesContainer.innerHTML = `
                <div class="w-full h-[128px] bg-gray-200 rounded-md">

                </div>
                <div class="w-full h-[128px] bg-gray-200 rounded-md">

                </div>
                <div class="w-full h-[128px] bg-gray-200 rounded-md">

                </div>
                <div class="w-full h-[128px] bg-gray-200 rounded-md">

                </div>
            `
            return;
        }
    }

    const handleCategoryLoad = async () => {
        const categorySelect = document.querySelector("#product-category");
        try {
            const apiResponse = await fetch("/api/categories/");
            const data = await apiResponse.json();
            if (!data.success)
                throw new Error("Failed to load categories");
            data.data.forEach(category => {
                const option = document.createElement("option");
                option.value = category.id;
                option.textContent = category.name + ` (${category.product_count})`;
                categorySelect.appendChild(option);
            });
        } catch (e) {
            console.error(e);
        }
    }

    const handleProductUpload = async (evt) => {
        evt.preventDefault();
        const productName = document.querySelector("#product-name");
        const productCategory = document.querySelector("#product-category");
        const productPrice = document.querySelector("#product-price");
        const productDescription = document.querySelector("#product-description");

        if (!productName.value || !productCategory.value || !productPrice.value || !productDescription.value || productImages.length == 0) {
            alert("Please fill up all fields");
            return;
        }

        const formData = new FormData();
        formData.append("name", productName.value);
        formData.append("category_id", productCategory.value);
        formData.append("price", productPrice.value);
        formData.append("description", productDescription.value);
        formData.append("images", productImages.join(","));

        const apiResponse = await fetch("/api/products/", {
            method: "POST",
            body: formData
        });
        const data = await apiResponse.json();
        if (data.success) {
            alert("Product added successfully");
            productName.value = "";
            productCategory.value = "";
            productPrice.value = "";
            productDescription.value = "";
            productImages.value = "";
            location.href = "/admin/products";
        } else {
            alert("Failed to add product");
        }
    }

    const handlePageLoad = () => {
        handleCategoryLoad();
    }

    form.addEventListener("submit", handleProductUpload);
    window.addEventListener("load", handlePageLoad);
    uploadInput.addEventListener("change", handleFileUpload);
    uploadDummy.addEventListener("click", () => uploadInput.click());

</script>
</body>
</html>