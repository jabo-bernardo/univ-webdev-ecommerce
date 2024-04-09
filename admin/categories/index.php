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
                <h1 class="font-bold text-2xl">Categories</h1>
            </div>
            <div class="p-4">
                <div>
                    <form id="add-category-form" class="flex items-center gap-2">
                        <div class="flex-grow">
                            <label for="category-name" class="font-semibold">Category Name</label>
                            <input id="category-name" class="mt-1 bg-gray-100 w-full p-3 px-4 text-sm rounded-md" type="text" placeholder="Please enter a category name"/>
                        </div>
                        <div class="mt-4 flex-grow-0">
                            <button class="p-2 px-6 bg-blue-700 hover:bg-blue-900 text-white font-semibold rounded-md shadow-xl">Create Category</button>
                        </div>
                    </form>
                </div>
                <div>
                    <table
                        class="w-full text-sm mt-2 text-left rtl:text-right text-gray-500"
                    >
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Category Name</th>
                            <th scope="col" class="px-6 py-3">Product Count</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                        </thead>
                        <tbody id="categories-container">

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
    const form = document.querySelector("#add-category-form");
    let categories = [];

    const handleAddCategory = async () => {
        const categoryName = document.querySelector("#category-name").value;
        const formData = new FormData();
        formData.append("name", categoryName);
        const response = await fetch("/api/categories/", {
            method: "POST",
            body: formData
        });
        const data = await response.json();
        if (!data.success) {
            alert(data.reason);
            return;
        }
        alert("Category created successfully");
        window.location.reload();
    }

    const handleCategoriesLoad = async () => {
        try {
            const response = await fetch("/api/categories/");
            const _categories = await response.json();
            if (!_categories.success)
                throw new Error(_categories.reason);
            categories = _categories.data;
            const categoriesContainer = document.querySelector("#categories-container");
            categoriesContainer.innerHTML = "";
            for (let category of categories) {
                categoriesContainer.innerHTML += `
                    <tr class="bg-white border-b">
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                        >
                            ${category.name}
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                        >
                            ${category.product_count}
                        </th>
                        <td class="px-6 py-4 flex gap-2 flex-wrap">
                           <a href="/admin/products/?category_id=${category.id}">
                             <button
                                class="bg-gray-100 px-2 py-1 rounded-sm hover:bg-gray-200"
                            >
                                View Products
                            </button>
</a>
                            <button
                                class="bg-red-100 px-2 py-1 rounded-sm hover:bg-red-200 text-red-500"
                                data-category-id="${category.id}" id="delete-category-${category.id}"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                `;
            }
        } catch (error) {
            alert(error.message);
        }
    };

    const handleCategoryDelete = async (categoryId) => {
        const category = categories.find(category => category.id === categoryId);
        const confirmDelete = confirm(`Are you sure you want to delete this "${category.name}" category?`);
        if (!confirmDelete) return;

        const response = await fetch(`/api/categories/?category_id=${categoryId}`, {
            method: "DELETE"
        });
        const data = await response.json();
        if (!data.success) {
            alert("Please delete all the products in this category first.");
            return;
        }
        alert("Category deleted successfully");
        window.location.reload();
    }

    const handleWindowLoad = () => {
        handleCategoriesLoad();
        form.addEventListener("submit", (evt) => {
            evt.preventDefault();
            handleAddCategory();
        });
        document.addEventListener("click", async (evt) => {
            if (event.target.matches('[data-category-id]')) {
                const categoryId = evt.target.getAttribute("data-category-id");
                handleCategoryDelete(categoryId);
            }
        });
    };

    window.addEventListener("load", handleWindowLoad);
</script>
</body>
</html>