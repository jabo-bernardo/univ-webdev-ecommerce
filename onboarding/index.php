<!DOCTYPE html>
<html lang="en">
<?php
include_once "../layout/default_head.php";
?>
<body>
<!-- MAIN LAYOUT -->
<article class="w-8/12 mx-auto">
    <?php
    include_once "../components/topbar.php";
    ?>
    <main class="w-full min-h-[60vh] flex flex-col py-24 items-center justify-center">
        <div class="min-w-[540px]">
            <div id="onboarding-1">
                <div class="flex flex-col items-center">
                    <h2 class="font-bold text-2xl text-center">Howdy!</h2>
                    <p class="text-gray-500 text-sm w-[360px] text-center">Welcome to PrintEase Creations Online Store!, Let's get you started. Please fill out the form below</p>
                </div>
                <div class="mt-4">
                    <label for="first-name" class="font-semibold mb-2">First Name</label>
                    <input id="first-name" class="p-2 px-4 bg-gray-100 rounded-md w-full text-sm" placeholder="John"/>
                </div>
                <div class="mt-2">
                    <label for="last-name" class="font-semibold mb-2">Last Name</label>
                    <input id="last-name" class="p-2 px-4 bg-gray-100 rounded-md w-full text-sm" placeholder="Doe"/>
                </div>
                <div class="mt-2">
                    <button id="next-step-button" class="p-2 px-6 w-full mt-4 bg-blue-700 hover:bg-blue-900 text-white font-semibold rounded-md shadow-xl">Proceed to Shipping Details</button>
                </div>
            </div>
            <div id="onboarding-2" style="display: none;">
                <div class="flex flex-col items-center">
                    <h2 class="font-bold text-2xl text-center">Great! Last step,</h2>
                    <p class="text-gray-500 text-sm w-[360px] text-center">Please provide us on where you want your package to be delivered, and how to contact you!</p>
                </div>
                <div class="mt-4">
                    <label for="contact-number" class="font-semibold mb-2">Contact Number</label>
                    <input id="contact-number" class="p-2 px-4 bg-gray-100 rounded-md w-full text-sm" placeholder="+639X XXX XXXX"/>
                </div>
                <div class="mt-2">
                    <label for="region" class="font-semibold mb-2">Region</label>
                    <select id="region" class="p-2 px-4 bg-gray-100 rounded-md w-full text-sm">
                        <option>Please Select...</option>
                    </select>
                </div>
                <div class="mt-2">
                    <label for="province" class="font-semibold mb-2">Province</label>
                    <select id="province" class="p-2 px-4 bg-gray-100 rounded-md w-full text-sm">
                        <option>Please Select...</option>
                    </select>
                </div>
                <div class="mt-2">
                    <label for="city" class="font-semibold mb-2">City</label>
                    <select id="city" class="p-2 px-4 bg-gray-100 rounded-md w-full text-sm">
                        <option>Please Select...</option>
                    </select>
                </div>
                <div class="mt-2">
                    <label for="barangay" class="font-semibold mb-2">Barangay</label>
                    <select id="barangay" class="p-2 px-4 bg-gray-100 rounded-md w-full text-sm">
                        <option>Please Select...</option>
                    </select>
                </div>
                <div class="mt-2">
                    <label for="unit-number" class="font-semibold mb-2">Unit/House Number, Street</label>
                    <input id="unit-number" class="p-2 px-4 bg-gray-100 rounded-md w-full text-sm" placeholder="XXX, XXX St. "/>
                </div>
                <div class="mt-2">
                    <label for="notes" class="font-semibold mb-2">Notes to Delivery Rider</label>
                    <textarea id="notes" class="p-2 px-4 bg-gray-100 rounded-md w-full text-sm"></textarea>
                </div>
                <div class="mt-2 grid grid-cols-2 gap-2">
                    <button id="back-button" type="submit" class="p-2 px-6 bg-gray-100 hover:bg-gray-300 font-semibold rounded-md w-full shadow-xl">Go Back</button>
                    <button id="submit-button" type="submit" class="p-2 px-6 w-full bg-blue-700 hover:bg-blue-900 text-white font-semibold rounded-md shadow-xl">Finish</button>
                </div>
            </div>
        </div>
    </main>
    <!-- FOOTER -->
    <?php
    include_once "../components/footer.php";
    ?>
</article>
<script>
    const firstForm = document.querySelector("#onboarding-1");
    const secondForm = document.querySelector("#onboarding-2");

    let regions;
    let provinces;
    let cities;
    let barangays;

    const firstName = document.querySelector("#first-name");
    const lastName = document.querySelector("#last-name");
    const contactNumber = document.querySelector("#contact-number");
    const unitNumber = document.querySelector("#unit-number");
    const notes = document.querySelector("#notes");

    const regionSelect = document.querySelector("#region");
    const provinceSelect = document.querySelector("#province");
    const citySelect = document.querySelector("#city");
    const barangaySelect = document.querySelector("#barangay");

    const handleNextStep = () => {
        firstForm.style.display = "none";
        secondForm.style.display = "block";
    }

    const handleBack = () => {
        firstForm.style.display = "block";
        secondForm.style.display = "none";
    }

    const handleSubmit = async () => {
        const accessToken = localStorage.getItem("access_token");

        const authenticatedForm = new FormData();
        authenticatedForm.append("access_token", accessToken);

        const authenticatedResponse = await fetch("/api/auth/authenticated/", {
            method: "POST",
            body: authenticatedForm
        })
        const authenticatedData = await authenticatedResponse.json();
        if (!authenticatedData.success) {
            alert("You are not authenticated. Please login again.");
            location.href = "/auth/login";
            return;
        }
        console.log(authenticatedData);
        const account = authenticatedData.data[0];
        const accountId = account.account_id;

        // Handle form submission here
        if (!firstName.value || !lastName.value || !contactNumber.value) {
            alert("Please fill out all fields");
            return;
        }

        const accountFormData = new FormData();
        accountFormData.append("account_id", accountId);
        accountFormData.append("first_name", firstName.value);
        accountFormData.append("last_name", lastName.value);
        accountFormData.append("contact_number", contactNumber.value);

        const response = await fetch("/api/accounts/update/", {
            method: "POST",
            body: accountFormData
        });
        const data = await response.json();
        if (!data.success) {
            alert("Something went wrong while updating your profile. Please try again.");
            return;
        }

        const shippingFormData = new FormData();
        shippingFormData.append("region", regions.find(region => region.id == regionSelect.value).region_name);
        shippingFormData.append("province", provinces.find(province => province.province_code == provinceSelect.value).province_name);
        shippingFormData.append("city", cities.find(city => city.city_code == citySelect.value).city_name);
        shippingFormData.append("barangay", barangays.find(barangay => barangay.brgy_code == barangaySelect.value).brgy_name);
        shippingFormData.append("unit", unitNumber.value);
        shippingFormData.append("notes", notes.value);
        shippingFormData.append("access_token", accessToken);

        console.log(shippingFormData);

        const shippingResponse = await fetch("/api/shipping-addresses/", {
            method: "POST",
            body: shippingFormData
        });
        const shippingData = await shippingResponse.json();
        if (!shippingData.success) {
            alert("Something went wrong while updating your shipping details. Please try again.");
            return;
        }

        location.href = "/profile";

    }

    const handlePageLoad = async () => {
        const regionResponse = await fetch("/js/data/region.json");
        regions = await regionResponse.json();
        const regionSelect = document.querySelector("#region");
        regions.forEach(region => {
            const option = document.createElement("option");
            option.value = region.id;
            option.textContent = region.region_name;
            regionSelect.appendChild(option);
        });
    }

    const handleRegionSelect = async () => {
        const regionId = regionSelect.value;
        const provinceResponse = await fetch(`/js/data/province.json`);
        provinces = await provinceResponse.json();
        const provinceSelect = document.querySelector("#province");
        provinces.filter(province => parseInt(province.region_code) == parseInt(regionId)).forEach(province => {
            const option = document.createElement("option");
            option.value = province.province_code;
            option.textContent = province.province_name;
            provinceSelect.appendChild(option);
        });
    }

    const handleProvinceSelect = async () => {
        const provinceId = provinceSelect.value;
        const cityResponse = await fetch(`/js/data/city.json`);
        cities = await cityResponse.json();
        const citySelect = document.querySelector("#city");
        cities.filter(city => parseInt(city.province_code) == parseInt(provinceId)).forEach(city => {
            const option = document.createElement("option");
            option.value = city.city_code;
            option.textContent = city.city_name;
            citySelect.appendChild(option);
        });
    }

    const handleCitySelect = async () => {
        const cityId = citySelect.value;
        const barangayResponse = await fetch(`/js/data/barangay.json`);
        barangays = await barangayResponse.json();
        const barangaySelect = document.querySelector("#barangay");
        barangays.filter(barangay => parseInt(barangay.city_code) == parseInt(cityId)).forEach(barangay => {
            const option = document.createElement("option");
            option.value = barangay.brgy_code;
            option.textContent = barangay.brgy_name;
            barangaySelect.appendChild(option);
        });
    }

    const handleBarangaySelect = async () => {

    }

    document.querySelector("#next-step-button").addEventListener("click", handleNextStep);
    document.querySelector("#back-button").addEventListener("click", handleBack);
    document.querySelector("#submit-button").addEventListener("click", handleSubmit);

    regionSelect?.addEventListener("change", handleRegionSelect);
    provinceSelect?.addEventListener("change", handleProvinceSelect);
    citySelect?.addEventListener("change", handleCitySelect);
    barangaySelect?.addEventListener("change", handleBarangaySelect);

    window.addEventListener("load", handlePageLoad);
</script>
</body>
</html>