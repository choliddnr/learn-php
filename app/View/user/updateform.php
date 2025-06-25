<?php
// echo "<pre>";
// var_dump($form);
// echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- <script type="module" src="http://localhost:5173/resources/tailwind/index.ts"></script> -->
    <script type="module" src="/assets/index-B5S487x2.js"></script>
    <link rel="stylesheet" href="/assets/index-BvRfNH8U.css">

    <title>Update Profile</title>

</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-1 text-center">Update your profile </h2>

        <h2 class="text-2xl font-semibold mb-6 text-center underline"><?= $form->username ?></h2>

        <?php if (isset($errors['general'])) { ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-3" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline"><?= $errors['general'] ?></span>
            </div>
        <?php } ?>


        <form action="/user/update" method="POST" class="space-y-4" enctype="multipart/form-data">


            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Current Password</label>
                <input id="old_password" name="current_password" type="password" placeholder="Your Current Password"
                    required class="w-full border-gray-300 px-4 py-2 border rounded shadow-sm">
                <small class="text-gray-500">Your current password is required to confirm that you own this
                    account</small>
                <?php if (isset($errors['current_password'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['current_password'] ?></p>
                <?php } ?>
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input id="username" name="username" type="text" placeholder="Username" required
                    class="w-full shadow-sm border-gray-300 px-4 py-2 border rounded" value="<?= $form->username ?>">
                <small class="text-gray-500">Enter your full legal username. min 4 character.</small>
                <?php if (isset($errors['username'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['username'] ?></p>
                <?php } ?>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" placeholder="Email" disabled
                    class="w-full shadow-sm border-gray-300 px-4 py-2 border rounded" value="<?= $form->email ?>">
                <small class="text-gray-500">You can't change your email</small>

            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input id="password" name="new_password" type="password" placeholder="Password"
                    class="w-full shadow-sm border-gray-300 px-4 py-2 border rounded">
                <small class="text-gray-500">Leave it empty if you don't want to change your password</small>
                <?php if (isset($errors['new_password'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['new_password'] ?></p>
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
                <img src="/uploads/<?= htmlspecialchars($form->avatar) ?>" alt="Avatar"
                    class="w-24 h-24 object-cover rounded mt-2" />
                <?php if (isset($errors['avatar'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['avatar'] ?></p>
                <?php } ?>
            </div>

            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                <select id="gender" name="gender" type="avatar" placeholder="Gender"
                    class="w-full shadow-sm border-gray-300 px-4 py-2 border rounded">
                    <option value="0" <?= $form->gender === '0' ? 'selected' : '' ?>> Female</option>
                    <option value="1" <?= $form->gender === '1' ? 'selected' : '' ?>>Male</option>
                </select>
                <?php if (isset($errors['gender'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['gender'] ?></p>
                <?php } ?>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class=" flex-1 w-full border-gray-300 bg-green-600 text-white py-2 rounded hover:bg-green-700">
                    Submit Update
                </button>
                <a href="/"
                    class=" flex-1 w-full border-gray-300 bg-red-600 text-white py-2 rounded hover:bg-red-700 text-center">
                    Cancel
                </a>
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