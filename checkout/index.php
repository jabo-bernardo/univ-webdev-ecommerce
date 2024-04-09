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
            <section class="mt-2 flex flex-col items-center justify-center w-full">
                <div class="flex flex-col gap-2 w-8/12">
                    <div>
                        <div  class="border-4 rounded-xl p-4 w-full h-[256px] bg-gray-100 border-dashed border-gray-400 flex items-center justify-center flex-col mt-2">
                            <img class="w-20" src="/images/icons/cloud-upload-svgrepo-com.svg"/>
                            <label for="file-upload" class="font-bold text-gray-400 mt-2">Drag or Click here to upload file</label>
                            <input type="file" class="hidden" id="file-upload"/>
                        </div>
                    </div>
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


        <!--			<section class="my-16 flex justify-center items-center flex-col">-->
        <!--				<p class="font-bold text-lg">Amount to pay</p>-->
        <!--				<h3 class="text-8xl m-auto font-bold text-green-500">₱500.00</h3>-->
        <!--			</section>-->
        <!--			<section class="mt-4">-->
        <!--				<h2 class="text-lg font-bold text-center">What's next?</h2>-->
        <!--				<p class="text-gray-500 text-center">You will need to send the payment on our GCash account <strong>(+63)123 123 1234</strong>, or simply scanning the QR code below</p>-->
        <!--			</section>-->
        <!--			<section class="mt-2">-->
        <!--				<img src="/images/gcash-qr-code.svg" class="w-4/12 m-auto"/>-->
        <!--			</section>-->
		<!-- FOOTER -->
		<?php include_once "../components/footer.php" ?>
	</article>

	<script>
        const productsContainer = document.getElementById("products-container");
        const confirmButton = document.getElementById("confirm-button");

        const handleToCheckoutProductsLoad = async () => {
            const productsToCheckout = JSON.parse(localStorage.getItem("to_checkout") || "[]");

            productsContainer.innerHTML = "";
            for (let productId of productsToCheckout) {
                const response = await fetch(`/api/products/select/?product_id=${productId}`);
                const data = await response.json();
                console.log(data);
                if (!data.success) return;

                const product = data.data[0];
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

            var fileInput = document.getElementById('file-upload');
            var file = fileInput.files[0];

            var formData = new FormData();
            formData.append('fileToUpload', file);
            formData.append('access_token', accessToken);

            fetch('/api/files/upload/', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => console.log(data))
                .catch(error => console.error(error));

            return;

            try {
                const formData = new FormData();
                formData.append("order_items", productsToCheckout.join(","));
                formData.append("shipping_address_id", shippingAddressId);
                formData.append("access_token", accessToken);

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
        }

        window.addEventListener("load", handlePageLoad);
        confirmButton.addEventListener("click", handleOrderConfirmation);
    </script>
</body>
</html>