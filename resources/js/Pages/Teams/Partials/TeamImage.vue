<script setup></script>

<template>
    <div>
        <img
            class="max-w-full rounded-lg hover:grayscale transition-all duration-300"
            :src="$page.props.team.image"
            :alt="$page.props.team.name"
        />
    </div>
</template>
