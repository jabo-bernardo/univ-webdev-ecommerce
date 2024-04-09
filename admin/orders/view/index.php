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
                    <p class="text-gray-500 mb-1">Subtotal: ₱999.0</p>
                    <p class="text-gray-500 mb-1">Shipping Fee: ₱0.0</p>
                    <p class="text-lg font-semibold">Total: ₱999.0</p>
                </div>
            </div>
            <div class="mt-4">
                <h2 class="font-semibold text-xl">Shipping Address</h2>
            </div>
            <div class="mt-2">
                <div class="border rounded-md p-4">
                    <p class="text-gray-600 font-bold">Full Name</p>
                    <p class="text-gray-500">Joel-Vincent Bernardo</p>
                    <p class="text-gray-600 font-bold mt-2">Contact Number</p>
                    <p class="text-gray-500">09560564142</p>
                    <p class="text-gray-600 font-bold mt-2">Address</p>
                    <p class="text-gray-500">Block 3 Lot 7 Phase 1, Villa Zaragoza, Barangay San Mateo, City of San Jose Del Monte, Bulacan</p>
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
                    <p class="text-gray-500">Paid</p>
                </div>
            </div>
            <div class="mt-4">
                <h2 class="font-semibold text-xl">Order Status</h2>
            </div>
            <div class="mt-2">
                <div class="border rounded-md p-4">
                    <p class="text-gray-600 font-bold">Status</p>
                    <p class="text-gray-500">Awaiting Payment</p>
                </div>
                <div class="mt-2 flex flex-wrap gap-1">
                    <button class="p-2 px-6 bg-orange-600 hover:bg-orange-900 text-white font-semibold rounded-md shadow-xl text-sm">Mark as Awaiting Payment</button>
                    <button class="p-2 px-6 bg-green-600 hover:bg-green-900 text-white font-semibold rounded-md shadow-xl text-sm">Mark as Paid</button>
                    <button class="p-2 px-6 bg-blue-600 hover:bg-blue-900 text-white font-semibold rounded-md shadow-xl text-sm">Mark as Shipping</button>
                    <button class="p-2 px-6 bg-orange-600 hover:bg-orange-900 text-white font-semibold rounded-md shadow-xl text-sm">Mark as Processing</button>
                    <button class="p-2 px-6 bg-green-600 hover:bg-green-900 text-white font-semibold rounded-md shadow-xl text-sm">Mark as Completed</button>
                    <button class="p-2 px-6 bg-red-600 hover:bg-red-900 text-white font-semibold rounded-md shadow-xl text-sm">Mark as Cancelled</button>
                    <button class="p-2 px-6 bg-red-600 hover:bg-red-900 text-white font-semibold rounded-md shadow-xl text-sm">Mark as Refunded</button>
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
        console.log(order);
        console.log(orderItems);
    }

    const handlePageLoad = () => {
        handleOrderLoad();
    }

    window.addEventListener("load", handlePageLoad)
</script>
</body>
</html>