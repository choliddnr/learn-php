<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- <script type="module" src="http://localhost:5173/resources/tailwind/index.ts"></script> -->
    <script type="module" src="/assets/index-B5S487x2.js"></script>
    <link rel="stylesheet" href="/assets/index-BvRfNH8U.css">

    <title>Register</title>

</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Welcome</h2>

        <!-- Tabs -->
        <div class="mb-6 flex justify-center space-x-4">
            Have an account? please <a href="/login" class="text-blue-600 hover:underline"> Login</a>
        </div>
        <?php if (isset($errors['general'])) { ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-3" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline"><?= $errors['general'] ?></span>
            </div>
        <?php } ?>

        <!-- Register Form -->
        <form action="/register" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input id="username" name="username" type="text" placeholder="Username" required
                    class="w-full shadow-sm border-gray-300 px-4 py-2 border rounded" value="<?= $form->username ?? "" ?>">
                <small class="text-gray-500">Enter your username. min 4 character.</small>
                <?php if (isset($errors['username'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['username'] ?></p>
                <?php } ?>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" placeholder="Email"
                    class="w-full shadow-sm border-gray-300 px-4 py-2 border rounded" value="<?= $form->email ?? "" ?>">
                <small class="text-gray-500">Enter your valid email.</small>
                <?php if (isset($errors['email'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['email'] ?></p>
                <?php } ?>

            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700"> Password</label>
                <input id="password" name="password" type="password" placeholder="Password"
                    class="w-full shadow-sm border-gray-300 px-4 py-2 border rounded">
                <small class="text-gray-500">Make strong password.</small>
                <?php if (isset($errors['password'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['password'] ?></p>
                <?php } ?>
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input id="confirm_password" name="confirm_password" type="password" placeholder="Confirm Password"
                    class="w-full shadow-sm border-gray-300 px-4 py-2 border rounded">
                <small class="text-gray-500">Re-enter your password to confirm.</small>
                <?php if (isset($errors['confirm_password'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['confirm_password'] ?></p>
                <?php } ?>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class=" flex-1 w-full border-gray-300 bg-green-600 text-white py-2 rounded hover:bg-green-700">
                    Register
                </button>
            </div>
        </form>
    </div>

    <script>
        function showForm(type) {
            document.getElementById('loginForm').classList.toggle('hidden', type !== 'login');
            document.getElementById('registerForm').classList.toggle('hidden', type !== 'register');
        }
    </script>

</body>

</html>