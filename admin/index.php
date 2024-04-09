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
		<main class="grid grid-cols-[256px_1fr] my-4">
			<?php
                include_once "../components/admin_navigation.php";
            ?>
			<div>
				<div class="p-4 border-b-2 border-gray-50">
					<h1 class="font-bold text-2xl">Dashboard</h1>
				</div>
			</div>
		</main>
		<!-- FOOTER -->
        <?php
            include_once "../components/footer.php";
        ?>
	</article>
</body>
</html>