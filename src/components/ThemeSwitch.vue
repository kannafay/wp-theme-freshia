<script setup>
import ColorMode from '@/utils/ColorMode'

const switcher = new ColorMode()

function switchTheme(mode) {
    if (!mode) return
    if (mode === 'toggle') {
        switcher.toggle()
        return
    }
    const oldMode = switcher.getMode()
    if (oldMode == mode) return
    switcher.setMode(mode)
}
</script>

<template>
    <div class="flex gap-x-4 pt-4">
        <div class="flex">
            <input type="radio" name="color-mode-group"
                class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                id="color-mode-group-1" @click="switchTheme('light')" :checked="switcher.getMode() === 'light'">
            <label for="color-mode-group-1" class="text-sm ms-2 dark:text-neutral-400">浅色模式</label>
        </div>

        <div class="flex">
            <input type="radio" name="color-mode-group"
                class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                id="color-mode-group-2" @click="switchTheme('dark')" :checked="switcher.getMode() === 'dark'">
            <label for="color-mode-group-2" class="text-sm ms-2 dark:text-neutral-400">深色模式</label>
        </div>

        <div class="flex">
            <input type="radio" name="color-mode-group"
                class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                id="color-mode-group-3" @click="switchTheme('auto')" :checked="switcher.getMode() === 'auto'">
            <label for="color-mode-group-3" class="text-sm ms-2 dark:text-neutral-400">跟随系统</label>
        </div>
    </div>
</template>

<style scoped>
input:hover,
label:hover {
    cursor: pointer;
}
</style>