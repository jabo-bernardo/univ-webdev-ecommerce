<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once "../../layout/default_head.php" ?>
</head>
<body>
  <!-- MAIN LAYOUT -->
  <article class="absolute top-0 left-0">
    <main class="w-full h-[100vh] grid grid-cols-2">
      <div class="bg-gray-500">
        <img class="w-full h-full object-cover" src="/images/42950.jpg"/>
      </div>
      <div class="flex items-center justify-center">
        <div class="w-6/12">
          <div class="text-center">
            <h1 class="text-3xl font-bold">PrintEase Creations</h1>
            <p class="text-sm text-gray-500">We provide a variety of premium printing services</p>
          </div>
          <div class="flex flex-col gap-4 mt-4">
              <form id="register-form">
                  <div>
                      <label for="email-address" class="text-gray-600">Email Address</label>
                      <input id="email-address" class="mt-1 bg-slate-100 w-full p-3 px-4 text-sm rounded-md" type="email" placeholder="johndoe@domain.com"/>
                  </div>
                  <div>
                      <label for="password" class="text-gray-600">Password</label>
                      <input id="password" class="mt-1 bg-slate-100 w-full p-3 px-4 text-sm rounded-md" type="password" placeholder="********"/>
                  </div>
                  <div class="flex justify-between items-center">
                      <a href="/auth/login" class="text-blue-700">Already have an account?</a>
                      <button class="p-2 px-6 bg-blue-700 hover:bg-blue-900 text-white font-semibold rounded-md shadow-xl">Create Account</button>
                  </div>
              </form>
          </div>
        </div>
      </div>
    </main>
  </article>

 <script>
        const form = document.querySelector("#register-form");

        const handleRegister = async (evt) => {
            evt.preventDefault();
            const email = document.querySelector("#email-address").value;
            const password = document.querySelector("#password").value;

            const formData = new FormData();
            formData.append("email_address", email);
            formData.append("password", password);

            const response = await fetch("/api/auth/register/", {
                method: "POST",
                body: formData
            });
            const data = await response.json();
            if (!data.success) {
                alert(data.reason);
                return;
            }
            window.location.href = "/auth/login";
        }

        form.addEventListener("submit", handleRegister);
 </script>
</body>
</html>