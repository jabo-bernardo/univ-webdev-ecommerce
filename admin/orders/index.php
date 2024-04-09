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
                <h1 class="font-bold text-2xl">Orders</h1>
            </div>
            <div>
                <div class="p-4">
                    <table
                            class="w-full text-sm text-left rtl:text-right text-gray-500"
                    >
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Order ID</th>
                            <th scope="col" class="px-6 py-3">Customer</th>
                            <th scope="col" class="px-6 py-3">Total Amount</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                        </thead>
                        <tbody id="orders-container">

                        </tbody>
                    </table>
                </div>
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
    let accountId = params.get("account_id");
    let status = params.get("status");

    const ordersContainer = document.querySelector("#orders-container");

    const handleOrdersLoad = async () => {
        let apiUrl = `/api/orders/?`;
        if (accountId) {
            apiUrl += `account_id=${accountId}&`;
        }
        if (status) {
            apiUrl += `status=${status}&`;
        }
        const apiResponse = await fetch(apiUrl);
        const data = await apiResponse.json();
        console.log(data);
        if (!data.success) {
            alert(data.reason);
            return;
        }
        for (let order of data.data) {
            console.log(order);
            const orderResponse = await fetch(`/api/orders/select/?order_id=${order.id}`);
            const orderData = await orderResponse.json();
            if (!orderData.success) {
                continue;
            }

            const SHIPPING_FEE = 50;
            const sumOfOrder = orderData.data.order_items.reduce((acc, item) => acc + parseFloat(item.price), 0) + parseFloat(SHIPPING_FEE);

            ordersContainer.innerHTML += `
                    <tr class="bg-white border-b">
                        <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                        >
                            ${order.id}
                        </th>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${order.first_name || "(NOT_SET)"} ${order.last_name}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">₱${parseFloat(sumOfOrder).toFixed(1)}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                            >
                                ${order.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="/admin/orders/view/?order_id=${order.id}" >
<button
                                    class="bg-gray-100 px-2 py-1 rounded-sm hover:bg-gray-200 text-gray-500"
                            >
                                View
                            </button>
</a>
<button
                                    class="bg-red-100 px-2 py-1 rounded-sm hover:bg-red-200 text-red-500"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                `;
        }
    }

    const handlePageLoad = () => {
        handleOrdersLoad();
    }

    window.addEventListener("load", handlePageLoad)
</script>
</body>
</html>