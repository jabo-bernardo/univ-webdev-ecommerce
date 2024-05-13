<?php
    $is_dashboard = $_SERVER["REQUEST_URI"] == "/admin/";
    $is_products = strpos($_SERVER["REQUEST_URI"], "/admin/products") !== false;
    $is_customers = strpos($_SERVER["REQUEST_URI"], "/admin/customers") !== false;
    $is_orders = strpos($_SERVER["REQUEST_URI"], "/admin/orders") !== false;
    $is_categories = strpos($_SERVER["REQUEST_URI"], "/admin/categories") !== false;

    function is_should_be_active($condition) {
        echo $condition ?
            "p-4 rounded-md cursor-pointer bg-gray-100 -translate-x-2 transition-all flex flex-wrap gap-1" :
            "p-4 rounded-md cursor-pointer bg-gray-50 hover:bg-gray-100 hover:-translate-x-2 transition-all flex flex-wrap gap-1";
    }
?>

<div class="flex flex-col gap-1 pr-2 border-r-2 border-gray-50">
    <a href="/admin">
        <div class="<?php is_should_be_active($is_dashboard); ?>">
            <img class="w-5 h-5" src="/images/icons/chart-svgrepo-com.svg"/>
            <p class="font-bold">Dashboard</p>
        </div>
    </a>
    <a href="/admin/products">
        <div class="<?php is_should_be_active($is_products); ?>">
            <img class="w-5 h-5" src="/images/icons/bag-2-svgrepo-com.svg"/>
            <p class="font-bold">Products</p>
        </div>
    </a>
    <a href="/admin/categories">
        <div class="<?php is_should_be_active($is_categories); ?>">
            <img class="w-5 h-5" src="/images/icons/tag-svgrepo-com.svg"/>
            <p class="font-bold">Categories</p>
        </div>
    </a>
    <a href="/admin/customers">
        <div class="<?php is_should_be_active($is_customers); ?>">
            <img class="w-5 h-5" src="/images/icons/users-group-rounded-svgrepo-com.svg"/>
            <p class="font-bold">Customers</p>
        </div>
    </a>
    <a href="/admin/orders">
        <div class="<?php is_should_be_active($is_orders); ?>">
            <img class="w-5 h-5" src="/images/icons/bill-list-svgrepo-com.svg"/>
            <p class="font-bold">Orders</p>
        </div>
    </a>
</div>