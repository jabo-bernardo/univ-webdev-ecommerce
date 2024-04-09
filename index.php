<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once "./layout/default_head.php";?>
</head>
<body>
	<!-- MAIN LAYOUT -->
	<article class="w-8/12 mx-auto">
		<!-- TOPBAR -->
		<?php include_once "./components/topbar.php"?>
		<main class="py-8">
            <div class="flex flex-col items-center">
                <h1 class="text-3xl font-bold">Welcome to our online store!</h1>
                <p id="help-guide" class="text-lg text-gray-500">What do you need?</p>
            </div>
            <div id="categories-container" class="mt-4 flex flex-col gap-4 items-center">

            </div>
            <div>
                <div id="back-container">
                    <button id="back-button" class="p-2 px-6 bg-gray-100 hover:bg-gray-300 font-semibold rounded-md">Select Another Category?</button>
                </div>
                <div id="products-container" class="mt-4 flex flex-wrap gap-4 items-center">

                </div>
            </div>
		</main>
		<?php include_once "./components/footer.php"?>
	</article>

    <script defer>
        const categoriesContainer = document.getElementById("categories-container");
        const productsContainer = document.getElementById("products-container");
        const helpGuide = document.getElementById("help-guide");
        const backButton = document.getElementById("back-button");

        const handleCategoryClick = async (category, categoryName) => {
            categoriesContainer.style.display = "none";
            backButton.style.display = "block";
            productsContainer.style.display = "flex";
            helpGuide.innerText = `Here are the products under ${categoryName} category`;

            const response = await fetch(`/api/products/?category_id=${category}`);
            const data = await response.json();
            if (!data.success) return;

            productsContainer.innerHTML = "";
            if (data.data.length === 0) {
                productsContainer.innerHTML = "<p class='text-center text-gray-500'>No products found</p>";
                return;
            }

            data.data.forEach(product => {
                const productElement = document.createElement("a");
                productElement.href = `/products/?id=${product.id}`;

                productElement.classList.add("w-72", "h-[18rem]", "rounded-md", "shadow-md", "overflow-hidden", "relative", "cursor-pointer");
                productElement.innerHTML = `
                    <div>
                        <img class="h-48 object-cover w-full" src="/images/5272436.jpg"/>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold">${product.name}</h4>
                        <p class="text-xs">${product.description.substring(0, 128)}...</p>
                        <p class="absolute bottom-4 right-4 font-semibold">₱${parseFloat(product.price).toFixed(1)}</p>
                    </div>
                `;
                productsContainer.appendChild(productElement);
            });
        }

        const handleCategoriesLoad = async () => {
            productsContainer.style.display = "none";
            backButton.style.display = "none";
            categoriesContainer.style.display = "flex";
            helpGuide.innerText = "What do you need?";
            const response = await fetch("/api/categories/");
            const data = await response.json();

            if (!data.success) return;
            categoriesContainer.innerHTML = "";

            data.data.forEach(category => {
                const categoryElement = document.createElement("div");
                categoryElement.classList.add("border-2", "flex", "rounded-md", "overflow-hidden", "hover:-translate-y-2", "transition-all", "bg-white", "cursor-pointer", "min-w-[500px]");
                categoryElement.addEventListener("click", () => handleCategoryClick(category.id, category.name))
                categoryElement.innerHTML = `
                        <div class="flex-grow-0">
                            <img src="/images/5272436.jpg" class="w-[128px] h-[80px] object-cover"/>
                        </div>
                        <div class="flex-grow flex flex-col justify-center px-4">
                            <p class="font-bold text-xl">${category.name}</p>
                            <p class="text-gray-500">Category</p>
                        </div>
                    `;
                categoriesContainer.appendChild(categoryElement);
            });
        }

        const handleWindowLoad = () => {
            handleCategoriesLoad();
        }

        window.addEventListener("load", handleWindowLoad);
        backButton.addEventListener("click", handleCategoriesLoad);
    </script>
</body>
</html>