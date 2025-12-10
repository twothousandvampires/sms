<template>
    <div class="container">
        <header class="header">
            <h1>Задачи</h1>
            <button v-if="user" class="btn primary" @click="goLogout">Выйти</button>
        </header>

        <div class="card" v-if="!loading">
            <div v-if="user">
                <h1 style="text-align: center;">{{ user.name }}</h1>

                <Tasks></Tasks>
            </div>

            <div v-else>
                <div v-if="mode === 'login'">
                    <h2>Вход</h2>
                    <input v-model="email" placeholder="Email">
                    <input v-model="password" type="password" placeholder="Password">
                    <button class="btn primary" @click="goLogin">Войти</button>
                    <button class="btn" @click="mode = 'register'">Нужен аккаунт?</button>
                </div>

                <div v-else>
                    <h2>Регистрация</h2>
                    <input v-model="name" placeholder="Name">
                    <input v-model="email" placeholder="Email">
                    <input v-model="password" type="password" placeholder="Password">
                    <button class="btn primary" @click="goRegister()">Создать</button>
                    <button class="btn" @click="mode = 'login'">Есть аккаунт?</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
const { login, register, logout, me } = useLoginUser()
const user = ref(null);
const mode = ref('login');
const name = ref('');
const email = ref('');
const password = ref('');
const loading = ref(true)

let goRegister = async () => {
    let response = await register(name.value, email.value, password.value);

    if (response.success) {
        user.value = response.user
        localStorage.setItem('token', response.token)
    }
}

let checkAuth = async () => {
    loading.value = true
    let result = await me()
    if (result.success) {
        user.value = result.user
    }
    loading.value = false
}

let goLogin = async () => {
    loading.value = true
    let result = await login(email.value, password.value)
    if (result.success) {
        user.value = result.user
        localStorage.setItem('token', result.token)
    }
    loading.value = false
}

let goLogout = async () => {
    loading.value = true
    let result = await logout()
    if (result.success) {
        user.value = null
        localStorage.removeItem('token')
    }
    loading.value = false
}

onMounted(checkAuth);
</script>