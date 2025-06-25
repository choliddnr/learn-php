<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- <script type="module" src="http://localhost:5173/resources/tailwind/index.ts"></script> -->
    <script type="module" src="/assets/index-B5S487x2.js"></script>
    <link rel="stylesheet" href="/assets/index-BvRfNH8U.css">


    <title>Add User Info</title>

</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-md">

        <!-- Tabs -->

        <?php if (isset($errors['general'])) { ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-3" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline"><?= $errors['general'] ?></span>
            </div>
        <?php } ?>

        <!-- Register Form -->
        <form action="/user/create" method="POST" class="space-y-4" enctype="multipart/form-data">
            <div>
                <label for="fullname" class="block text-sm font-medium text-gray-700">Fullname</label>
                <input id="fullname" name="fullname" type="text" placeholder="fullname" required
                    class="w-full shadow-sm border-gray-300 px-4 py-2 border rounded" value="<?= $form->fullname ?? "" ?>">
                <small class="text-gray-500">Enter your fullname. min 4 character.</small>
                <?php if (isset($errors['fullname'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['fullname'] ?></p>
                <?php } ?>
            </div>

            <div>
                <label for="whatsapp" class="block text-sm font-medium text-gray-700">Whatsapp</label>
                <input id="whatsapp" name="whatsapp" type="number" placeholder="+6285712345789" required
                    class="w-full shadow-sm border-gray-300 px-4 py-2 border rounded" value="<?= $form->whatsapp ?? "" ?>">
                <small class="text-gray-500">Enter your valid whatsapp.</small>
                <?php if (isset($errors['whatsapp'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['whatsapp'] ?></p>
                <?php } ?>

            </div>

            <div>
                <label for="avatar" class="block text-sm font-medium text-gray-700">Avatar</label>
                <input id="avatar" name="avatar" type="file" placeholder="avatar"
                    class="w-full shadow-sm border-gray-300 px-4 py-2 border rounded">
                <small class="text-gray-500">Make strong avatar.</small>
                <?php if (isset($errors['avatar'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['avatar'] ?></p>
                <?php } ?>
            </div>

            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                <select id="gender" name="gender" type="avatar" placeholder="Gender"
                    class="w-full shadow-sm border-gray-300 px-4 py-2 border rounded">
                    <option value="0" selected> Female</option>
                    <option value="1">Male</option>
                </select>
                <?php if (isset($errors['gender'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['gender'] ?></p>
                <?php } ?>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class=" flex-1 w-full border-gray-300 bg-green-600 text-white py-2 rounded hover:bg-green-700">
                    Submit Profile Info
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