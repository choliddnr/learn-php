<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Todo Item</title>
    <link href="/../../assets/style_output.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <pre>
        <!-- <?= print_r($user, true) ?> -->
    </pre>
    <div class="bg-white p-8 rounded shadow-md max-w-md w-full">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">User Info</h1>

        <p class="mb-2 text-gray-600 grid grid-cols-2"><strong>Name:</strong> <span
                class="underline font-medium"><?= $user->name ?></span></p>
        <p class="mb-2 text-gray-600  grid grid-cols-2"><strong>Email:</strong> <span
                class="underline font-medium"><?= $user->email ?></span></p>
        <p class=" mb-2 text-gray-600 grid grid-cols-2"><strong>Registered
                at: </strong> <span class="underline font-medium"><?= $user->created_at ?></span></p>


        <!-- Action Buttons -->
        <div class="flex justify-between space-x-4">
            <a href="/todo"
                class="flex-1 bg-blue-200 hover:bg-blue-300 text-blue-800 font-medium py-2 px-4 rounded text-center">
                Todo
            </a>
            <a href="/user/update"
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded text-center">
                Update
            </a>
            <a href="/logout"
                class="flex-1 bg-red-200 hover:bg-red-300 text-red-800 font-medium py-2 px-4 rounded text-center">
                Logout
            </a>

            <a href="#" onclick="deleteAccount(<?= $user->id ?>)"
                class="flex-1 bg-red-400 hover:bg-red-500 text-red-800 font-medium py-2 px-4 rounded text-center">
                Delete
            </a>
        </div>
    </div>
    <script>
        function deleteAccount(id) {
            if (confirm("Are you sure you want to delete your account?")) {
                fetch('/user', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                })
                    .then(response => {
                        if (response.ok) {
                            console.log("Account deleted successfully.");
                            location.replace('/login'); // Refresh the page to see the updated list
                        } else {
                            console.error("Failed to delete account.");
                        }
                    })
                    .catch(error => {
                        alert("Error:", error);
                    });

            } else {
                console.log("Delete action canceled.");
            }
        }
    </script>
</body>

</html>