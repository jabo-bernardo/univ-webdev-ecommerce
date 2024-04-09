<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once "../layout/default_head.php";?>
</head>
<body>
<!-- MAIN LAYOUT -->
<article class="w-8/12 mx-auto">
    <!-- TOPBAR -->
    <?php include_once "../components/topbar.php"?>
    <main class="py-8">
        <div class="grid grid-cols-[350px_1fr] gap-4">
            <div>
                <section class="border-2 overflow-hidden rounded-md">
                    <div class="relative">
                        <div class="w-full h-[128px] bg-blue-700">

                        </div>
                        <div>
                            <img class="w-16 h-16 pt-4 pl-4" src="/images/icons/user-circle-svgrepo-com.svg"/>
                        </div>
                    </div>
                    <div class="p-4">
                        <div>
                            <p class="font-semibold">Name</p>
                            <p class="text-gray-500" id="account-name">Joel-Vincent G. Bernardo</p>
                        </div>
                        <div class="mt-4">
                            <p class="font-semibold">Join Date</p>
                            <p class="text-gray-500" id="account-join-date">March 8, 2024</p>
                        </div>
                    </div>
                </section>
                <section>
                    <a href="/admin">
                        <div class="mt-4">
                            <button class="p-2 px-6 bg-gray-700 hover:bg-gray-900 text-white font-semibold rounded-md shadow-xl w-full">Go to Admin Page</button>
                        </div>
                    </a>
                    <div class="mt-2">
                        <button id="logout-button" class="p-2 px-6 bg-red-700 hover:bg-red-900 text-white font-semibold rounded-md shadow-xl w-full">Log Out</button>
                    </div>
                </section>
            </div>
            <div>
                <div class="border-b-2 p-4">
                    <h2 class="font-bold text-2xl">My Orders</h2>
                </div>
                <div id="orders-container" class="mt-2 flex flex-col gap-2">
                    <div class="border grid grid-cols-[128px_1fr] rounded-md overflow-hidden">
                        <div>
                            <img src="/images/5272436.jpg" />
                        </div>
                        <div class="p-4">
                            <div class="flex gap-2 flex-wrap">
                                <h2 class="font-bold text-xl">Order#123</h2>
                                <div class="bg-orange-400 p-1 px-2 flex items-center justify-center rounded-md">
                                    <p class="text-white text-sm font-semibold">Processing</p>
                                </div>
                            </div>
                            <p class="text-gray-500 text-sm">Custom Shit, Jabo Sticker</p>
                            <p class="text-lg font-bold mt-4">₱250.0</p>
                            <button class="bg-red-100 px-2 py-1 rounded-sm hover:bg-red-200 text-red-500">
                                Cancel Order
                            </button>
                        </div>
                    </div>
                </div>
                <div class="border-b-2 p-4 mt-4">
                    <h2 class="font-bold text-2xl">Shipping Addresses</h2>
                </div>
                <div class="mt-2">
                    <div id="addresses-container">
                        <div class="flex justify-between rounded-md overflow-hidden border">
                            <div class="flex items-center p-4 flex-grow-0">
                                <img class="w-12" src="/images/icons/point-on-map-svgrepo-com.svg"/>
                            </div>
                            <div class="p-4 flex-grow">
                                <p class="font-semibold">Sandico St, Bundukan, Bocaue, Bulacan</p>
                                <p class="text-sm text-gray-500">Region III (Central Luzon)</p>
                            </div>
                            <div class="flex items-center p-4 flex-grow-0">
                                <img class="w-8" src="/images/icons/trash-bin-trash-svgrepo-com.svg"/>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="p-2 px-6 bg-blue-700 hover:bg-blue-900 text-white font-semibold rounded-md shadow-xl w-full">+ Add New Address</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include_once "../components/footer.php"?>
</article>

<script>
    const ordersContainer = document.getElementById("orders-container");
    const addressesContainer = document.getElementById("addresses-container");
    let accountId = null;

    const handleUserLoad = async () => {
        const accessToken = localStorage.getItem("access_token");
        if (!accessToken) {
            alert("You are not logged in");
            location.href = "/auth/login/";
            return;
        }
        const formData = new FormData();
        formData.append("access_token", accessToken);

        const apiResponse = await fetch("/api/auth/authenticated/", {
            method: "POST",
            body: formData
        });
        const data = await apiResponse.json();
        if (!data.success) {
            alert(data.reason);
            return;
        }
        const user = data.data[0];
        accountId = user.account_id;

        document.getElementById("account-name").innerText = `${user.first_name} ${user.last_name}`;
        document.getElementById("account-join-date").innerText = new Date(user.created_at).toDateString();
    }

    const handleOrdersLoad = async () => {
        const apiResponse = await fetch(`/api/orders/?account_id=${accountId}`);
        const data = await apiResponse.json();
        if (!data.success) {
            alert(data.reason);
            return;
        }

        console.log(data);

        ordersContainer.innerHTML = "";
        for (let order of data.data) {
            const orderElement = document.createElement("div");

            const SHIPPING_FEE = 50;

            const orderResponse = await fetch(`/api/orders/select/?order_id=${order.id}`);
            const orderData = await orderResponse.json();
            if (!orderData.success) {
                continue;
            }
            const _order = orderData.data.order[0];
            const _order_items = orderData.data.order_items;
            const sumOfOrder = _order_items.reduce((acc, item) => acc + parseFloat(item.price), 0) + parseFloat(SHIPPING_FEE);

                console.log(_order);

            orderElement.classList.add("border", "grid", "grid-cols-[128px_1fr]", "rounded-md", "overflow-hidden");
            orderElement.innerHTML = `
                <div>
                    <img class="h-full rounded-md object-cover" src="/images/5272436.jpg" />
                </div>
                <div class="p-4">
                    <div class="flex gap-2 flex-wrap">
                        <h2 class="font-bold text-xl">Order#${_order.id}</h2>
                        <div class="bg-orange-400 p-1 px-2 flex items-center justify-center rounded-md">
                            <p class="text-white text-sm font-semibold">${_order.status}</p>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm">${_order_items.map(item => item.name).join(", ")}</p>
                    <p class="text-lg font-bold mt-4">₱${parseFloat(sumOfOrder).toFixed(1)}</p>
                    <button class="bg-gray-100 px-2 py-1 rounded-md font-semibold hover:bg-gray-200 text-gray-500 mt-2">
                        Pay Order
                    </button>
                    <button class="bg-red-100 px-2 py-1 rounded-md font-semibold hover:bg-red-200 text-red-500 mt-2">
                        Cancel Order
                    </button>
                </div>

            `;
            ordersContainer.appendChild(orderElement);
        }
    }

    const handleLogout = async () => {
        localStorage.removeItem("access_token");
        location.href = "/auth/login/";
    }

    const handleAddressesLoad = async () => {
        const accessToken = localStorage.getItem("access_token");
        const apiResponse = await fetch(`/api/accounts/shipping-addresses/?access_token=${accessToken}`);
        const data = await apiResponse.json();
        if (!data.success) {
            alert(data.reason);
            return;
        }

        if (data.data.length === 0) {
            location.href = "/onboarding";
            return;
        }

        addressesContainer.innerHTML = "";
        for (let address of data.data) {
            const addressElement = document.createElement("div");
            addressElement.classList.add("flex", "justify-between", "rounded-md", "overflow-hidden", "border");
            addressElement.innerHTML = `
                <div class="flex items center p-4 flex-grow-0">
                    <img class="w-12" src="/images/icons/point-on-map-svgrepo-com.svg"/>
                </div>
                <div class="p-4 flex-grow">
                    <p class="font-semibold">${address.province}, ${address.city}, ${address.barangay}, ${address.unit}</p>
                    <p class="text-sm text-gray-500">${address.region}</p>
                </div>
                <div class="flex items-center p-4 flex-grow-0">
                    <img class="w-8" src="/images/icons/trash-bin-trash-svgrepo-com.svg"/>
                </div>
            `;
            addressesContainer.appendChild(addressElement);
        }
    }

    const handlePageLoad = async () => {
        document.getElementById("logout-button").addEventListener("click", handleLogout);
        if (!localStorage.getItem("access_token")) {
            location.href = "/auth/login/";
            return;
        }
        await handleUserLoad();
        handleOrdersLoad();
        handleAddressesLoad();
    }

    window.addEventListener("load", handlePageLoad)
</script>
</body>
</html>