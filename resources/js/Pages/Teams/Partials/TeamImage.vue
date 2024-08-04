<script setup>

</script>

<template>
    <section class="bg-white rounded-lg shadow-sm p-4 sm:p-6 dark:bg-gray-800 text-gray-900 dark:text-white">
        <header>
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100 mb-4">Image de l'équipe</h2>
        </header>

        <div class="flex justify-center items-center">
            <img class="max-w-full rounded-lg hover:grayscale transition-all duration-300" :src="$page.props.team.image" :alt="$page.props.team.name">
        </div>
    </section>
</template>