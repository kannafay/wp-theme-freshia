<script setup>
import { ref, onBeforeUnmount } from 'vue'
import ThemeSwitch from './ThemeSwitch.vue'

const onmount = ref(true)

let timer = null
function startTimer() {
    if (timer) {
        return
    }
    timer = setInterval(() => {
        console.log(new Date().getTime())
    }, 500)
}
onBeforeUnmount(() => {
    if (timer) {
        clearInterval(timer)
        timer = null
    }
})
</script>

<template>
    <button @click="onmount = !onmount">{{ onmount === true ? '卸载' : '挂载' }}Vue实例</button> |
    <button @click="startTimer()">开启定时器</button>
    <ThemeSwitch v-if="onmount" />
</template>

<style scoped>
button:hover {
    cursor: pointer;
    text-decoration: underline;
}

button:active {
    color: #999;
}
</style>