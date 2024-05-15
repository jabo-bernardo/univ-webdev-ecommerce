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
                <h1 class="font-bold text-2xl">Customers</h1>
            </div>
            <div>
                <table
                        class="w-full text-sm text-left rtl:text-right text-gray-500"
                >
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Customer name</th>
                        <th scope="col" class="px-6 py-3">Joined</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="customers-container">

                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <!-- FOOTER -->
    <?php
    include_once "../../components/footer.php";
    ?>
</article>
<script>
    const handleCustomersLoad = async () => {
        const customersContainer = document.querySelector("#customers-container");
        const apiResponse = await fetch("/api/accounts/");
        const data = await apiResponse.json();
        console.log(data);
        if (!data.success) {
            alert(data.reason);
            return;
        }
        for (let customer of data.data) {
            customersContainer.innerHTML += `
                    <tr class="bg-white border-b">
                        <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                        >
                            ${customer.first_name || "(NOT_SET)"} ${customer.last_name || "(NOT_SET)"}
                        </th>
                        <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                        >
                            <p class="text-gray-400">${new Date(customer.created_at).toDateString()}</p>
                        </th>
                        <td class="px-6 py-4 flex gap-2 flex-wrap">
                            <a href="/admin/customers/view/?account_id=${customer.id}">
                                <button
                                    class="bg-gray-100 px-2 py-1 rounded-sm hover:bg-gray-200"
                                >
                                    View Information
                                </button>
                            </a>
                            <a href="/admin/orders/?account_id=${customer.id}">
                                <button
                                    class="bg-gray-100 px-2 py-1 rounded-sm hover:bg-gray-200"
                                >
                                    View Orders
                                </button>
                            </a>
                            
                        </td>
                    </tr>
            `;
        }
    }

    const handleWindowLoad = () => {
        handleCustomersLoad();
    }

    window.addEventListener("load", handleWindowLoad);
</script>
</body>
</html>