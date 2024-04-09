<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once "../layout/default_head.php"?>
</head>
<body>
	<!-- MAIN LAYOUT -->
	<article class="w-8/12 mx-auto">
		<?php include_once "../components/topbar.php"?>
		<main>
			<!-- PRODUCT DETAILS -->
			<section class="grid grid-cols-2 gap-8">
				<div>
					<img class="h-96 object-contain bg-gray-100 w-full rounded-md" src="/images/5272436.jpg"/>
				</div>
				<div>
					<h1 class="font-bold text-3xl" id="product-name">...</h1>
					<p class="text-sm text-gray-400" id="category-name">...</p>
					<h2 class="mt-2 font-bold text-xl" id="product-price">₱0.0</h2>
					<p class="mt-2 text-gray-800" id="product-description">...</p>
					<div class="flex flex-wrap gap-4 mt-4">
						<button id="buy-now-button" class="p-2 px-6 bg-blue-700 hover:bg-blue-900 text-white font-semibold rounded-md shadow-xl">Buy Now</button>
						<button id="cart-button" class="p-2 px-6 bg-gray-100 hover:bg-gray-300 font-semibold rounded-md">Add to Cart</button>
					</div>
				</div>
			</section>
			<!-- RECOMMENDATIONS -->
			<section class="mt-8" id="other-products-base">
				<h2 class="font-bold text-xl">You might also like ✨</h2>
				<div class="flex flex-wrap gap-4 mt-4" id="other-products-container">
					<div class="w-72 h-[18rem] rounded-md shadow-md overflow-hidden relative cursor-pointer">
						<div>
							<img class="h-48 object-cover w-full" src="/images/5272436.jpg"/>
						</div>
						<div class="p-4">
							<h4 class="font-bold">A very long product name</h4>
							<p class="text-xs">Sticker</p>
							<p class="absolute bottom-4 right-4 font-semibold">₱250.0</p>
						</div>
					</div>
				</div>
			</section>
		</main>
		<?php include_once "../components/footer.php"?>
	</article>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get("id");
        let productName = "";

        const addToCartButton = document.getElementById("cart-button");
        const buyNowButton = document.getElementById("buy-now-button");

        if (!productId) {
            window.location.href = "/";
        }

        const handleAddToCartButton = () => {
            try {
                const cart = JSON.parse(localStorage.getItem("cart") || "[]");
                if (cart.includes(productId)) {
                    alert(`${productName} is already your cart!`);
                    return;
                }
                cart.push(productId);
                localStorage.setItem("cart", JSON.stringify(cart));
                alert(`${productName} has been added to your cart!`);
            } catch (err) {
                console.error(err);
                localStorage.setItem("cart", JSON.stringify([]));
                handleAddToCartButton();
            }
        }

        const handleBuyNowButton = () => {
            localStorage.setItem("to_checkout", JSON.stringify([productId]));
            window.location.href = "/checkout/";
        }

        const handleProductLoad = async () => {
            const response = await fetch(`/api/products/select/?product_id=${productId}`);
            const data = await response.json();
            if (!data.success) return;

            const product = data.data[0];
            document.getElementById("product-name").innerText = product.name;
            document.getElementById("category-name") .innerText = product.category_name;
            document.getElementById("product-price").innerText = `₱${parseFloat(product.price).toFixed(1)}`;
            document.getElementById("product-description").innerText = product.description;
            productName = product.name;

            // Handle other products
            const otherProductsResponse = await fetch(`/api/products/?category_id=${product.category_id}`);
            const otherProductsData = await otherProductsResponse.json();
            if (!otherProductsData.success) return;

            if (otherProductsData.data.filter(product => product.id != productId).length === 0) {
                document.getElementById("other-products-base").style.display = "none";
                return;
            }

            const otherProductsContainer = document.getElementById("other-products-container");
            otherProductsContainer.innerHTML = "";
            otherProductsData.data.filter((product, index) => product.id != productId && index < 5).forEach(otherProduct => {
                const productElement = document.createElement("a");
                productElement.href = `/products/?id=${otherProduct.id}`;
                productElement.classList.add("w-72", "h-[18rem]", "rounded-md", "shadow-md", "overflow-hidden", "relative", "cursor-pointer");
                productElement.innerHTML = `
                    <div>
                        <img class="h-48 object-cover w-full" src="/images/5272436.jpg"/>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold">${otherProduct.name}</h4>
                        <p class="text-xs">${otherProduct.category_id}</p>
                        <p class="absolute bottom-4 right-4 font-semibold">₱${parseFloat(otherProduct.price).toFixed(1)}</p>
                    </div>
                `;
                otherProductsContainer.appendChild(productElement);
            });
        }

        const handleWindowLoad = async () => {
            await handleProductLoad();
        }

        window.addEventListener("load", handleWindowLoad);
        addToCartButton.addEventListener("click", handleAddToCartButton);
        buyNowButton.addEventListener("click", handleBuyNowButton);
    </script>
</body>
</html>