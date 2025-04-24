<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="/../../assets/style_output.css" rel="stylesheet">
    <title>Add New Todo</title>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6">Add new todo</h2>

        <?php if (isset($errors['general'])) { ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-3" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline"><?= $errors['general'] ?></span>
            </div>
        <?php } ?>


        <form class="space-y-4" action="/todo" method="POST">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="<?= $form->title ?? '' ?>" required
                    class="mt-1  px-4 py-2 block w-full border-gray-300 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter task title">
                <small class="text-gray-500">Todo title.</small>
                <?php if (isset($errors['title'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['title'] ?></p>
                <?php } ?>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="4"
                    class="mt-1  px-4 py-2 block w-full border-gray-300 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter task description"><?= $form->description ?? '' ?></textarea>
                <small class="text-gray-500">Is not required, leave it empty if you don't want to add a
                    description</small>
                <?php if (isset($errors['description'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['description'] ?></p>
                <?php } ?>
            </div>

            <!-- Deadline -->
            <div>
                <label for="deadline" class="block text-sm font-medium text-gray-700">Deadline</label>
                <input type="datetime-local" id="deadline" name="deadline" value="<?= $form->deadline ?? '' ?>"
                    class="mt-1  px-4 py-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <small class="text-gray-500">The time when the task should be done</small>

                <?php if (isset($errors['deadline'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['deadline'] ?></p>
                <?php } ?>
            </div>

            <div class="flex justify-between space-x-4">
                <button type="submit"
                    class="flex-1 bg-blue-200 hover:bg-blue-300 text-blue-800 font-medium py-2 px-4 rounded text-center">Submit</button>
                <a href="/todo"
                    class="flex-1 bg-red-200 hover:bg-red-300 text-red-800 font-medium py-2 px-4 rounded text-center">
                    Cancel
                </a>

            </div>
        </form>
    </div>
</body>

</html>