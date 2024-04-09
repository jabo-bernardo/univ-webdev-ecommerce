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
        <h1 class="font-bold text-2xl">Customers > <span id="customer-name"></span></h1>
      </div>
      <div class="p-4">
        <div>
          <h2 class="font-semibold text-xl">Personal Information</h2>
        </div>
        <div class="w-full bg-gray-100 rounded-lg p-4 mt-2">
          <div class="grid gap-2 grid-cols-2">
            <div class="border-r-2 border-gray-200 mr-4">
              <p class="text-gray-600 font-bold">First Name</p>
              <p class="text-gray-500" id="first-name">Joel-Vincent</p>
            </div>
            <div class="">
              <p class="text-gray-600 font-bold">Last Name</p>
              <p class="text-gray-500" id="last-name">Bernardo</p>
            </div>
          </div>
          <div class="grid gap-2 grid-cols-3 mt-8">
            <div class="border-r-2 border-gray-200 mr-4">
              <p class="text-gray-600 font-bold">Contact Number</p>
              <p class="text-gray-500" id="phone-number">09560564142</p>
            </div>
            <div class="border-r-2 border-gray-200 mr-4">
              <p class="text-gray-600 font-bold">Email Address</p>
              <p class="text-gray-500" id="email-address">789@789.789</p>
            </div>
            <div class="">
              <p class="text-gray-600 font-bold">Account Role</p>
              <p class="text-gray-500" id="role">Admin</p>
            </div>
          </div>
        </div>
        <div class="mt-4">
          <h2 class="font-semibold text-xl">Shipping Addresses</h2>
        </div>
        <div class="mt-2" id="addresses-container">

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
    const customerId = params.get("account_id");

    const addressesContainer = document.querySelector("#addresses-container");

    const handleCustomerLoad = async () => {
      const customerName = document.querySelector("#customer-name");
      const apiResponse = await fetch(`/api/accounts/select/?account_id=${customerId}`);
      const data = await apiResponse.json();
      if (!data.success) {
          alert(data.reason);
          return;
      }
      const customer = data.data[0];
      customerName.innerText = `${customer.first_name || "(NOT_SET)"} ${customer.last_name || "(NOT_SET)"}`;
      document.querySelector("#first-name").innerText = customer.first_name || "(NOT_SET)";
      document.querySelector("#last-name").innerText = customer.last_name || "(NOT_SET)";
      document.querySelector("#phone-number").innerText = customer.contact_number || "(NOT_SET)";
      document.querySelector("#email-address").innerText = customer.email_address || "(NOT_SET)";
      document.querySelector("#role").innerText = customer.role || "(NOT_SET)";
    }

    const handleAddressesLoad = async () => {
        const apiResponse = await fetch(`/api/shipping-addresses/?account_id=${customerId}`);
        const data = await apiResponse.json();
        if (!data.success) {
            alert(data.reason);
            return;
        }

        addressesContainer.innerHTML = "";
        for (let address of data.data) {
            const addressElement = document.createElement("div");
            addressElement.classList.add("flex", "justify-between", "rounded-md", "overflow-hidden", "bg-gray-100");
            addressElement.innerHTML = `
                <div class="flex items center p-4 flex-grow-0">
                    <img class="w-12" src="/images/icons/point-on-map-svgrepo-com.svg"/>
                </div>
                <div class="p-4 flex-grow">
                    <p class="font-semibold">${address.province}, ${address.city}, ${address.barangay}, ${address.unit}</p>
                    <p class="text-sm text-gray-500">${address.region}</p>
                </div>
            `;
            addressesContainer.appendChild(addressElement);
        }
    }

    const handleWindowLoad = () => {
        handleCustomerLoad();
        handleAddressesLoad();
    }

    window.addEventListener("load", handleWindowLoad);
</script>
</body>
</html>