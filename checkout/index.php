<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once "../layout/default_head.php"?>
</head>
<body>
	<!-- MAIN LAYOUT -->
	<article class="w-8/12 mx-auto">
		<?php include_once "../components/topbar.php" ?>
		<main class="my-8">
			<!-- CONTENT -->
			<section class="mt-4">
				<h2 class="text-2xl font-bold text-center">Order Confirmation</h2>
			</section>
			<section class="mt-2 flex flex-col items-center justify-center w-full">
                <div class="flex flex-col gap-2 w-8/12" id="products-container">
                    <div class="border-2 rounded-md p-4 grid grid-cols-[128px_1fr] gap-4 items-start">
                        <div>
                            <img class="h-28 rounded-md object-cover w-full" src="/images/5272436.jpg"/>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">A very long product name</h3>
                            <p class="text-sm text-gray-500 mt-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis reprehenderit amet atque? Similique labore...</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="mt-4">
                <h2 class="text-2xl font-bold text-center">Please attach your designs here</h2>
                <p class="text-gray-500 text-center">This is an optional step. Only provide if you ordered a custom product</p>
            </section>
            <section class="mt-2 mx-auto w-8/12">
                <div id="designs-container" class="w-full grid grid-cols-1 gap-2">
                    <div class="w-full h-[64px] bg-gray-200 rounded-md">

                    </div>
                </div>
                <div>
                    <label id="upload-dummy" for="design-images">
                        <button type="button" class="w-full bg-blue-600 p-2 px-4 font-semibold text-white rounded-md hover:bg-blue-700 mt-2">Upload a Design</button>
                    </label>
                    <input id="design-images" type="file" class="hidden" multiple/>
                </div>
            </section>
            <section class="mt-4">
                <h2 class="text-2xl font-bold text-center">Shipping Address</h2>
                <p class="text-gray-500 text-center">Please provide your shipping address</p>
            </section>
            <section class="mt-2 flex flex-col items-center justify-center w-full">
                <div class="flex flex-col gap-2 w-8/12">
                    <select class="p-2 px-4 bg-gray-100 rounded-md" id="shipping-addresses-option">
                    </select>
                </div>
            </section>
            <section class="mt-2 flex flex-col items-center justify-center w-full">
                <div class="flex flex-col gap-2 w-8/12">
                    <button id="confirm-button" class="p-2 px-6 mt-4 bg-blue-700 hover:bg-blue-900 text-white font-semibold rounded-md shadow-xl">Confirm Order, Proceed to Payment</button>
                </div>
            </section>
		</main>
		<?php include_once "../components/footer.php" ?>
	</article>

	<script defer>
        const productsContainer = document.getElementById("products-container");
        const confirmButton = document.getElementById("confirm-button");

        let designImages = [];
        const uploadInput = document.querySelector("#design-images");
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
                designImages.push(data.data.file_name);
                handleDesignImagesChange();
            }
        }

        const handleDesignImagesChange = () => {
            const designImagesContainer = document.getElementById("designs-container");
            designImagesContainer.innerHTML = "";

            designImages.forEach(image => {
                const imageElement = document.createElement("div");
                imageElement.classList.add("overflow-hidden", "rounded-lg", "flex", "gap-2");
                imageElement.innerHTML = `
                    <div class="flex-grow-0">
                        <img class="w-16" src="/images/icons/cloud-upload-svgrepo-com.svg"/>
                    </div>
                    <div class="flex-grow">
                        <p class="text-gray-500">${image}</p>
                    </div>
                    <div class="flex-grow-0 flex">
                        <button type="button" class="w-full mt-1 p-2 px-4 bg-white-600 rounded-lg font-semibold hover:bg-gray-100 border-2">View</button>
                        <button type="button" class="w-full mt-1 p-2 px-4 bg-red-600 rounded-lg font-semibold text-white hover:bg-red-700" data-attachment-value="${image}">Remove</button>
                    </div>
                `;
                designImagesContainer.appendChild(imageElement);
            });

            if (designImages.length == 0) {
                designImagesContainer.innerHTML = `
                <div class="w-full h-[64px] bg-gray-200 rounded-md">

                </div>
            `
            }
        }

        const handleToCheckoutProductsLoad = async () => {
            const productsToCheckout = JSON.parse(localStorage.getItem("to_checkout") || "[]");

            productsContainer.innerHTML = "";
            for (let productId of productsToCheckout) {
                const response = await fetch(`/api/products/select/?product_id=${productId}`);
                const data = await response.json();
                if (!data.success) return;

                const product = data.data[0];
                if (!product) continue;

                const productElement = document.createElement("div");
                productElement.classList.add("border-2", "rounded-md", "p-4", "grid", "grid-cols-[128px_1fr]", "gap-4", "items-start");
                productElement.innerHTML = `
                    <div>
                        <img class="h-28 rounded-md object-cover w-full" src="/images/5272436.jpg"/>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">${product.name}</h3>
                        <p class="text-sm text-gray-500 mt-2">${product.description.substring(0, 128)}...</p>
                        <h3 class="font-bold text-lg">₱${parseFloat(product.price).toFixed(1)}</h3>
                    </div>
                `;

                productsContainer.appendChild(productElement);
            }
        }

        const handleShippingAddressLoad = async () => {

            const shippingAddressesOption = document.getElementById("shipping-addresses-option");
            const accessToken = localStorage.getItem("access_token");

            try {
                const apiResponse = await fetch(`/api/accounts/shipping-addresses/?access_token=${accessToken}`);
                const data = await apiResponse.json();
                if (!data.success)
                    throw new Error("Failed to load shipping addresses");

                if(data.data.length == 0) {
                    alert("Please complete our onboarding process first before you can make an order.");
                    window.location.href = "/onboarding/";
                    return;
                }

                data.data.forEach(shippingAddress => {
                    const option = document.createElement("option");
                    option.value = shippingAddress.id;
                    option.textContent = `${shippingAddress.province}, ${shippingAddress.city}, ${shippingAddress.barangay}, ${shippingAddress.unit}`;
                    shippingAddressesOption.appendChild(option);
                });
            } catch (e) {
                console.error(e);
            }

        }

        const handleOrderConfirmation = async () => {
            const productsToCheckout = JSON.parse(localStorage.getItem("to_checkout") || "[]");
            const shippingAddressId = document.getElementById("shipping-addresses-option").value;
            const accessToken = localStorage.getItem("access_token");

            try {
                const formData = new FormData();
                formData.append("order_items", productsToCheckout.join(","));
                formData.append("shipping_address_id", shippingAddressId);
                formData.append("access_token", accessToken);
                formData.append("attached_files", designImages.join(","));

                const apiResponse = await fetch(`/api/orders/`, {
                    method: "POST",
                    body: formData
                });
                const data = await apiResponse.json();
                if (!data.success)
                    throw new Error("Failed to confirm order");

                alert("Order has been confirmed! Please proceed to payment");
                localStorage.removeItem("to_checkout");
                window.location.href = "/checkout/payment/?order_id=" + data.data.order_id;
            } catch (e) {
                console.error(e);
            }
        }

        const handlePageLoad = () => {
            handleToCheckoutProductsLoad();
            handleShippingAddressLoad();
            document.addEventListener('click', function(event) {
                if (event.target.matches('[data-attachment-value]')) {
                    const attachmentValue = event.target.getAttribute('data-attachment-value');
                    designImages = designImages.filter(image => image !== attachmentValue);
                    handleDesignImagesChange();
                }
            });
        }

        window.addEventListener("load", handlePageLoad);
        confirmButton.addEventListener("click", handleOrderConfirmation);
        uploadInput.addEventListener("change", handleFileUpload);
        uploadDummy.addEventListener("click", () => uploadInput.click());
    </script>
</body>
</html>