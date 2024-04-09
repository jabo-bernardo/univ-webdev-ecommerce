<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once "../../layout/default_head.php"?>
</head>
<body>
	<!-- MAIN LAYOUT -->
	<article class="w-8/12 mx-auto">
		<?php include_once "../../components/topbar.php" ?>
		<main class="my-8">
            <section class="my-16 flex justify-center items-center flex-col">
                <p class="font-bold text-3xl" id="order-number">Order#0</p>
            </section>
            <div id="not-paid" class="hidden">
                <section class="my-16 flex justify-center items-center flex-col">
                    <p class="font-bold text-lg">Amount to pay</p>
                    <h3 class="text-8xl m-auto font-bold text-green-500" id="amount">₱0.0</h3>
                </section>
                <section class="mt-4">
                    <h2 class="text-lg font-bold text-center">What's next?</h2>
                    <p class="text-gray-500 text-center">You will need to send the payment on our GCash account <strong>(+63)123 123 1234</strong>, or simply scanning the QR code below</p>
                </section>
                <section class="mt-2">
                    <img src="/images/gcash-qr-code.svg" class="w-4/12 m-auto"/>
                </section>
                <section class="mt-2 flex flex-col items-center justify-center">
                    <button class="p-2 px-6 mt-4 bg-blue-700 hover:bg-blue-900 text-white font-semibold rounded-md shadow-xl">Mark Order as Paid</button>
                </section>
            </div>
            <div id="already-paid" class="hidden">
                <section class="my-16 flex justify-center items-center flex-col">
                    <p class="font-bold text-lg">Thank you for your payment!</p>
                    <p class="text-gray-500 text-center">Your payment has been received. Your order will be processed shortly</p>
                </section>
            </div>
		</main>
		<?php include_once "../../components/footer.php" ?>
	</article>

	<script>
        const orderNumber = document.getElementById("order-number");
        const amount = document.getElementById("amount");
        const markAsPaidButton = document.querySelector("button");
        const notPaid = document.getElementById("not-paid");
        const alreadyPaid = document.getElementById("already-paid");

        const handlePaymentLoad = async () => {
            const params = new URLSearchParams(window.location.search);
            const orderId = params.get("order_id");
            orderNumber.innerText = `Order#${orderId}`;

            const response = await fetch(`/api/orders/select/?order_id=${orderId}`);
            const data = await response.json();
            if (!data.success) return;


            const order = data.data;
            if (order.order[0].status?.toLowerCase() == "paid") {
                notPaid.style.display = "none";
                alreadyPaid.style.display = "block";
            } else {
                notPaid.style.display = "block";
                alreadyPaid.style.display = "none";
            }

            const SHIPPING_FEE = 50;
            const sumOfOrder = order.order_items.reduce((acc, item) => acc + parseFloat(item.price), 0) + parseFloat(SHIPPING_FEE);

            amount.innerText = `₱${parseFloat(sumOfOrder).toFixed(1)}`;
        }

        const handleMarkAsPaid = async () => {
            const params = new URLSearchParams(window.location.search);
            const orderId = params.get("order_id");

            const formData = new FormData();
            formData.append("order_id", orderId);
            formData.append("status", "Paid");

            const response = await fetch(`/api/orders/update/`, {
                method: "POST",
                body: formData
            });
            const data = await response.json();
            if (!data.success) {
                alert(`Failed to mark order as paid: ${data.reason}`);
            }

            notPaid.style.display = "none";
            alreadyPaid.style.display = "block";
        }

        const handlePageLoad = () => {
            notPaid.style.display = "none";
            alreadyPaid.style.display = "none";
            handlePaymentLoad();
        }

        window.addEventListener("load", handlePageLoad);
        markAsPaidButton.addEventListener("click", handleMarkAsPaid);
    </script>
</body>
</html>