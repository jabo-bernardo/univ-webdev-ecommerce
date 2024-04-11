<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PrintEase Creations</title>

	<!-- CSS IMPORTS -->
	<link rel="stylesheet" href="/css/font.css"/>
	<link rel="stylesheet" href="/css/index.css"/>
	<link rel="stylesheet" href="/css/tw.css"/>
</head>
<body>
	<!-- MAIN LAYOUT -->
	<article class="w-8/12 mx-auto">
		<?php include_once "../components/topbar.php"?>
		<main>
			<section class="grid grid-cols-[1fr_320px] gap-8">
				<div class="flex flex-col gap-4 p-4" id="cart-container">
					<div class="border-2 rounded-md p-4 grid grid-cols-[128px_1fr] gap-4 items-start relative">
						<div>
							<img class="h-28 rounded-md object-cover w-full" src="/images/5272436.jpg"/>
						</div>
						<div>
							<h3 class="font-bold text-lg">A very long product name</h3>
							<p class="text-sm text-gray-500 mt-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis reprehenderit amet atque? Similique labore...</p>
						</div>
						<div class="bg-red-500 p-2 rounded-lg absolute -top-2 -right-2 cursor-pointer hover:bg-red-800">
							<p class="text-xs text-white font-bold">Remove</p>
						</div>
					</div>

				</div>
				<div>
					<div class="">
						<h3 class="text-2xl">In Your Cart</h3>
						<hr class="my-4"/>
						<div class="flex justify-between">
							<div>
								<p>Subtotal</p>
							</div>
							<div>
								<p id="subtotal" class="text-gray-500">₱0.0</p>
							</div>
						</div>
						<div class="flex justify-between">
							<div>
								<p>Shipping</p>
							</div>
							<div>
								<p id="shipping" class="text-gray-500">₱0.0</p>
							</div>
						</div>
						<div class="flex justify-between mt-4">
							<div>
								<p class="font-bold">Total</p>
							</div>
							<div>
								<p id="total" class="text-gray-500">₱0.0</p>
							</div>
						</div>
						<button id="checkout-button" class="p-2 px-6 w-full mt-4 bg-blue-700 hover:bg-blue-900 text-white font-semibold rounded-md shadow-xl">Proceed to Checkout</button>
					</div>
				</div>
			</section>
		</main>
		<?php include_once "../components/footer.php"?>
	</article>
    <script>
        const checkoutButton = document.getElementById("checkout-button");
        let products = [];

        const handleCartRemove = (cartIndex) => {
            const cart = JSON.parse(localStorage.getItem("cart") || "[]");
            cart.splice(cartIndex, 1);
            localStorage.setItem("cart", JSON.stringify(cart));
            handleCartProducts();
        }

        const handleCartProducts = () => {
            const cart = JSON.parse(localStorage.getItem("cart") || "[]");

            const cartContainer = document.getElementById("cart-container");
            cartContainer.innerHTML = "";
            if (cart.length === 0) {
                cartContainer.innerHTML = "<p class='text-center text-gray-500'>No products in your cart</p>";
                return;
            }

            products = [];
            cart.forEach(async (productId, index) => {
                const productElement = document.createElement("div");

                // Get product from server
                const response = await fetch(`/api/products/select/?product_id=${productId}`);
                const data = await response.json();
                if (!data.success) return;

                const product = data.data[0];
                if (!product) return;
                products.push(product);

                productElement.classList.add("border-2", "rounded-md", "p-4", "grid", "grid-cols-[128px_1fr]", "gap-4", "items-start", "relative");
                productElement.innerHTML = `
                    <div>
                        <img class="h-28 rounded-md object-cover w-full" src="/images/5272436.jpg"/>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">${product.name}</h3>
                        <p class="text-sm text-gray-500 mt-2">${product.description}</p>
                        <h3 class="font-bold text-lg">₱${parseFloat(product.price).toFixed(1)}</h3>

                    </div>

                `;

                const removeButton = document.createElement("div");
                removeButton.classList.add("bg-red-500", "p-2", "rounded-lg", "absolute", "-top-2", "-right-2", "cursor-pointer", "hover:bg-red-800");
                removeButton.innerHTML = `<p class="text-xs text-white font-bold">Remove</p>`;
                removeButton.addEventListener("click", () => handleCartRemove(index));
                productElement.appendChild(removeButton);

                cartContainer.appendChild(productElement);
                handleCalculation();
            });

        }

        const handleCalculation = () => {
            // const subtotal = products.reduce((acc, product) => acc + parseFloat(product.price), 0);
            let subtotal = 0;
            products.forEach(product => {
                console.log(product);
                subtotal += parseFloat(product.price);
            });
            const shipping = 50;
            const total = subtotal + shipping;

            document.getElementById("subtotal").innerText = `₱${parseFloat(subtotal).toFixed(1)}`;
            document.getElementById("shipping").innerText = `₱${parseFloat(shipping).toFixed(1)}`;
            document.getElementById("total").innerText = `₱${parseFloat(total).toFixed(1)}`;
        }

        const handleCheckout = () => {
            const cart = JSON.parse(localStorage.getItem("cart") || "[]");
            if (cart.length === 0) {
                alert("No products in your cart");
                return;
            }

            localStorage.setItem("to_checkout", JSON.stringify(cart));

            // Redirect to checkout page
            window.location.href = "/checkout";
        }

        const handlePageLoad = () => {
            handleCartProducts();
        }

        window.addEventListener("load", handlePageLoad);
        checkoutButton.addEventListener("click", handleCheckout)
    </script>
</body>
</html>