<?php
// echo "<pre>";
// print_r($form);

// print_r($tags);

// print_r($errors);
// echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script>
        window.server = {
            tags: <?= json_encode(array_map(fn($tag) => ['id' => (int)$tag->id, 'title' => $tag->title], $tags)); ?>,
            current_tags: <?= json_encode(array_map(fn($tag) => ['id' => (int)$tag->id, 'title' => $tag->title], $form->tags)); ?>
        }
    </script>
    <!-- <script type="module" src="http://localhost:5173/resources/tailwind/todo_update.ts"></script> -->
    <script type="module" src="/assets/todo_update-CUgqp2MY.js "></script>
    <link rel="stylesheet" href="/assets/index-BvRfNH8U.css">
    <title>PHP UPdate FOrm sss</title>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6">Todo Form</h2>
        <?php if (isset($errors['general'])) { ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-3" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline"><?= $errors['general'] ?></span>
            </div>
            <?php if (isset($errors['title'])) { ?>
                <p class="text-red-600 text-sm"><?= $errors['title'] ?></p>
            <?php } ?>
        <?php } ?>

        <form class="space-y-4" action="/todo/<?= $id ?>" method="POST">


            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($form->title) ?>"
                    class="mt-1 px-4 py-2 block w-full border-gray-300 rounded-md border shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter todo title">

                <small class="text-gray-500">Todo title.</small>
                <?php if (isset($errors['title'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['title'] ?></p>
                <?php } ?>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="4" value=""
                    class="mt-1 px-4 py-2 block w-full border-gray-300 rounded-md border shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter todo description"><?= htmlspecialchars($form->description) ?></textarea>
                <small class="text-gray-500">Is not required, leave it empty if you don't want to add a
                    description</small>
                <?php if (isset($errors['description'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['description'] ?></p>
                <?php } ?>
            </div>

            <!-- Deadline -->
            <div>
                <label for="deadline" class="block text-sm font-medium text-gray-700">Deadline</label>
                <input type="datetime-local" id="deadline" name="deadline"
                    value="<?= date('Y-m-d\TH:i', $form->deadline) ?>"
                    class="mt-1 px-4 py-2 block w-full border-gray-300 rounded-md border shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <small class="text-gray-500">The time when the task should be done.</small>
                <?php if (isset($errors['deadline'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['deadline'] ?></p>
                <?php } ?>
            </div>

            <!-- Options -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" value="ongoing"
                    class="mt-1 px-4 py-2 block w-full border-gray-300 rounded-md border shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option <?= htmlspecialchars($form->status) === "pending" ? "selected" : "" ?> value="pending">
                        Pending
                    </option>
                    <option <?= htmlspecialchars($form->status) === "processed" ? "selected" : "" ?> value="processed">
                        processed
                    </option>
                    <option <?= htmlspecialchars($form->status) === "done" ? "selected" : "" ?> value="done">Done
                    </option>
                </select>
                <small class="text-gray-500">Your current todo status.</small>
                <?php if (isset($errors['status'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['status'] ?></p>
                <?php } ?>
            </div>


            <!-- Tags -->
            <div x-data="tagDropdown()">
                <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                <!-- 🔥 Hidden input to submit selected array -->
                <template x-for="(item, index) in selected" :key="index">
                    <input type="hidden" name="tags[]" :value="item.id">
                </template>
                <div class="relative w-full">
                    <button @click="open = !open" type="button"
                        class="w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <template x-if="selected.length === 0">
                            <span class="text-gray-400 px-4  ">Select Tags...</span>
                        </template>
                        <template x-for="item in selected" :key="item.id">
                            <span class="inline-block mr-2 rounded-full text-xs">
                                <span x-text="item.title" class="bg-blue-200 text-black px-2 py-1 rounded-md"></span>
                            </span>
                        </template>
                    </button>

                    <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                        <template x-for="tag in tags" :key="tag.id">
                            <div @click="toggle(tag)" class="cursor-pointer select-none relative py-2 px-4 pl-10 pr-4 hover:bg-indigo-50 text-sm text-gray-700">
                                <span x-text="tag.title"></span>
                                <template x-if="selected.some(t => t.id === tag.id)">
                                    <span class="absolute left-2 top-2 text-indigo-600">✔️</span>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <?php if (isset($errors['tags'])) { ?>
                    <p class="text-red-600 text-sm"><?= $errors['tags'] ?></p>
                <?php } ?>
            </div>


            <!-- Action Buttons -->
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