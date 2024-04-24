<!DOCTYPE html>
<html lang="en">
<?php
include_once "../../../layout/default_head.php";
?>
<body>
<!-- MAIN LAYOUT -->
<article class="w-8/12 mx-auto">
  <?php
  include_once "../../../components/topbar.php";
  ?>
  <main class="grid grid-cols-[256px_1fr] my-4">
    <?php
    include_once "../../../components/admin_navigation.php";
    ?>
    <div>
      <div class="p-4 border-b-2 border-gray-50">
        <h1 class="font-bold text-2xl">Orders > <span id="order-number">Order#789</span></h1>
      </div>
      <div>
        <div class="p-4">
          <div>
            <h2 class="font-semibold text-xl">Products</h2>
          </div>
          <div id="products-container" class="mt-2">
            <div class="border grid grid-cols-[256px_1fr] rounded-md overflow-hidden">
              <div>
                <img class="h-[150px] w-full rounded-md object-cover" src="/images/5272436.jpg" />
              </div>
              <div class="p-4">
                <h2 class="font-bold text-xl">Product Name</h2>
                <p class="text-gray-500 text-sm">Product Description</p>
                <p class="text-lg font-bold mt-4">₱999.0</p>
              </div>
            </div>
          </div>
            <div class="mt-2 border-t flex justify-between">
                <div></div>
                <div class="text-right mt-2">
                    <p class="text-gray-500 mb-1" id="payment-subtotal">Subtotal: ₱999.0</p>
                    <p class="text-gray-500 mb-1" id="payment-shipping">Shipping Fee: ₱0.0</p>
                    <p class="text-lg font-semibold" id="payment-totak">Total: ₱999.0</p>
                </div>
            </div>
            <div class="mt-4">
                <h2 class="font-semibold text-xl">Attached Files</h2>
            </div>
            <div class="mt-2 flex flex-col gap-2" id="attachment-containers">

            </div>
            <div class="mt-4">
                <h2 class="font-semibold text-xl">Shipping Address</h2>
            </div>
            <div class="mt-2">
                <div class="border rounded-md p-4">
                    <p class="text-gray-600 font-bold">Full Name</p>
                    <p class="text-gray-500" id="customer-name">Joel-Vincent Bernardo</p>
                    <p class="text-gray-600 font-bold mt-2">Contact Number</p>
                    <p class="text-gray-500" id="contact-number">09560564142</p>
                    <p class="text-gray-600 font-bold mt-2">Address</p>
                    <p class="text-gray-500" id="shipping-address">Block 3 Lot 7 Phase 1, Villa Zaragoza, Barangay San Mateo, City of San Jose Del Monte, Bulacan</p>
                </div>
            </div>
            <div class="mt-4">
                <h2 class="font-semibold text-xl">Payment Information</h2>
            </div>
            <div class="mt-2">
                <div class="border rounded-md p-4">
                    <p class="text-gray-600 font-bold">Payment Method</p>
                    <p class="text-gray-500">GCash</p>
                    <p class="text-gray-600 font-bold mt-2">Payment Status</p>
                    <p class="text-gray-500" id="payment-status">Paid</p>
                </div>
            </div>
            <div class="mt-4">
                <h2 class="font-semibold text-xl">Order Status</h2>
            </div>
            <div class="mt-2">
                <div class="border rounded-md p-4">
                    <p class="text-gray-600 font-bold">Status</p>
                    <p class="text-gray-500" id="order-status">Awaiting Payment</p>
                </div>
                <div class="mt-2 flex flex-wrap gap-1">
                    <button onclick="handleOrderStatusChange('Awaiting Payment')" class="p-2 px-6 bg-orange-600 hover:bg-orange-900 text-white font-semibold rounded-md shadow-xl text-sm">Mark as "Awaiting Payment"</button>
                    <button onclick="handleOrderStatusChange('Paid')" class="p-2 px-6 bg-green-600 hover:bg-green-900 text-white font-semibold rounded-md shadow-xl text-sm">Mark as "Paid"</button>
                    <button onclick="handleOrderStatusChange('Processing')" class="p-2 px-6 bg-orange-600 hover:bg-orange-900 text-white font-semibold rounded-md shadow-xl text-sm">Mark as "Processing"</button>
                    <button onclick="handleOrderStatusChange('Shipping')" class="p-2 px-6 bg-blue-600 hover:bg-blue-900 text-white font-semibold rounded-md shadow-xl text-sm">Mark as "Shipping"</button>
                    <button onclick="handleOrderStatusChange('Completed')" class="p-2 px-6 bg-green-600 hover:bg-green-900 text-white font-semibold rounded-md shadow-xl text-sm">Mark as "Completed"</button>
                    <button onclick="handleOrderStatusChange('Cancelled')" class="p-2 px-6 bg-red-600 hover:bg-red-900 text-white font-semibold rounded-md shadow-xl text-sm">Mark as "Cancelled"</button>
                    <button onclick="handleOrderStatusChange('Refunded')" class="p-2 px-6 bg-red-600 hover:bg-red-900 text-white font-semibold rounded-md shadow-xl text-sm">Mark as "Refunded"</button>
                </div>
            </div>
        </div>
      </div>
    </div>
  </main>
  <!-- FOOTER -->
  <?php
  include_once "../../../components/footer.php";
  ?>
</article>
<script>
    const params = new URLSearchParams(window.location.search);
    let orderId = params.get("order_id");

    const productsContainer = document.querySelector("#products-container");

    const handleOrderLoad = async () => {
        let apiUrl = `/api/orders/select/?order_id=${orderId}`;
        let response = await fetch(apiUrl);
        let data = await response.json();
        if (!data.success) {
            alert(data.reason);
            location.href = "/admin/orders";
            return;
        }
        const orderResponse = data.data;
        const order = orderResponse.order[0];
        const orderItems = orderResponse.order_items;

        const orderSubtotal = orderItems.reduce((acc, item) => {
            return acc + parseFloat(item.price);
        }, 0);
        const SHIPPING_FEE = 50;
        const orderTotal = orderSubtotal + parseFloat(SHIPPING_FEE);

        document.querySelector("#order-number").innerText = `Order#${order.id}`;
        document.querySelector("#payment-subtotal").innerText = `Subtotal: ₱${parseFloat(orderSubtotal).toFixed(1)}`;
        document.querySelector("#payment-shipping").innerText = `Shipping Fee: ₱${parseFloat(SHIPPING_FEE).toFixed(1)}`;
        document.querySelector("#payment-totak").innerText = `Total: ₱${parseFloat(orderTotal).toFixed(1)}`;

        productsContainer.innerHTML = "";
        orderItems.forEach(product => {
            const productContainer = document.createElement("div");
            productContainer.classList.add("border", "grid", "grid-cols-[256px_1fr]", "rounded-md", "overflow-hidden");
            productContainer.innerHTML = `
                <div>
                    <img class="h-[150px] w-full rounded-md object-cover" src="/uploads/${product?.images?.split(",")[0]}" />
                </div>
                <div class="p-4">
                    <h2 class="font-bold text-xl">${product.name}</h2>
                    <p class="text-gray-500 text-sm">${product.description}</p>
                    <p class="text-lg font-bold mt-4">₱${parseFloat(product.price).toFixed(1)}</p>
                </div>
            `;
            productsContainer.appendChild(productContainer);
        });

        const attachmentContainer = document.querySelector("#attachment-containers");
        attachmentContainer.innerHTML = "";
        const attachments = order.attached_files;
        attachments.split(",").forEach(attachment => {
            const attachmentElement = document.createElement("div");
            attachmentElement.classList.add("border", "rounded-md", "p-4", "flex", "justify-between", "items-center");
            attachmentElement.innerHTML = `
                <div>
                    <p class="text-gray-600 font-bold">${attachment}</p>
                    <p class="text-gray-500">${getFileType(attachment)}</p>
                </div>
                <a href="/uploads/${attachment}" download="${attachment}" target="_blank" class="text-blue-600">
                    <button class="p-2 px-6 bg-blue-600 hover:bg-blue-900 text-white font-semibold rounded-md shadow-xl text-sm">Download</button>
                </a>
            `;
            attachmentContainer.appendChild(attachmentElement);
        })

        const shippingAddressResponse = await fetch(`/api/shipping-addresses/select/?shipping_address_id=${order.shipping_address_id}`);
        const shippingAddressData = await shippingAddressResponse.json();
        const shippingAddress = shippingAddressData.data[0];

        document.querySelector("#shipping-address").innerText = `${shippingAddress.unit}, ${shippingAddress.barangay}, ${shippingAddress.city}, ${shippingAddress.province}, ${shippingAddress.region}`;

        const customerResponse = await fetch(`/api/accounts/select/?account_id=${order.account_id}`);
        const customerData = await customerResponse.json();
        const customer = customerData.data[0];

        document.querySelector("#customer-name").innerText = `${customer.first_name} ${customer.last_name}`;
        document.querySelector("#contact-number").innerText = customer.contact_number;

        const paymentStatus = order.status === "Awaiting Payment" ? "Unpaid" : "Paid";
        document.querySelector("#payment-status").innerText = paymentStatus;

        document.querySelector("#order-status").innerText = order.status;
    }

    const handleOrderStatusChange = async (status) => {
        const formData = new FormData();
        formData.append("order_id", orderId);
        formData.append("status", status);
        const apiResponse = await fetch(`/api/orders/update/?order_id=${orderId}`, {
            method: "POST",
            body: formData
        });
        const data = await apiResponse.json();
        if (!data.success) {
            alert(data.reason);
            return;
        }
        location.reload();
    }

    const handlePageLoad = () => {
        handleOrderLoad();
    }

    const getFileType = (filename) => {
        const match = filename.match(/\.([0-9a-z]+)(?:[\?#]|$)/i);
        if (match) {
            const extension = match[1];
            return extension.toUpperCase() + ' File';
        } else {
            return 'Unknown File Type';
        }
    }

    window.addEventListener("load", handlePageLoad)
</script>
</body>
</html>