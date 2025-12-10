<template>
    <div>
        <form @submit.prevent="createOrUpdateTask" id="create-task">
            <div v-if="expanded">
                <textarea rows="6" v-model="title" type="text" placeholder="заголовок" required />
                <textarea rows="12" v-model="description" type="text" placeholder="описание" required />
                <select v-model="status_id" name="status" required>
                    <option v-for="status in statuses" :value="status.id">{{ status.name }}</option>
                </select>
                <select v-model="assigned_id" name="assigned" required>
                    <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                </select>
                <input type="date" v-model="completion_date">
                <input hidden type="text" v-model="task_id">
                <input type="file" @change="handleFileUpload" ref="fileInput">
            </div>
            <button type="submit" class="btn primary">{{ mode === 'create' ? 'Cоздать' : 'Cохранить' }}</button>
        </form>
    </div>
    <div>
        <div>
            <p class="btn primary" @click="toggleFilters()">Фильтры</p>
            <div v-if="show_filters">
                <div>
                    <select class="filter-select" v-model="by_user" name="assigned" required>
                        <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                    </select>
                    <select class="filter-select" v-model="by_status" name="status" required>
                        <option v-for="status in statuses" :value="status.id">{{ status.name }}</option>
                    </select>
                </div>
                <div>
                    <input class="filter-select" type="date" v-model="date_from"></input>
                    <input class="filter-select" type="date" v-model="date_to"></input>
                </div>

                <p class="btn primary" @click="goFilter">Показать</p>
            </div>
        </div>
        <div class="task-cart" v-for="task in tasks">
            <h3 style="margin-bottom: 8px;"> {{ task.title }}</h3>
            <p style="margin-bottom: 8px;"> {{ task.description }}</p>
            <p> Закреплена за {{ task.assignee.name }}</p>
            <p> Статус: {{ task.status.name }}</p>
            <p v-if="task.completion_date"> Дата завершения {{ task.completion_date }} </p>
            <a v-if="task.attachment_url" target="_blank" :href="task.attachment_url">Вложение</a>
            <div class="cart-actions">
                <button class="btn primary" @click="editTask(task)">Редактировать</button>
                <button class="btn danger" @click="goDeleteTask(task.id)">Удалить</button>
            </div>
        </div>
    </div>
</template>
<script setup>
const { getTasks, storeTask, getFilters, deleteTask, updateTask } = useTasks()

let mode = ref('create')
let expanded = ref(false)
let title = ref('')
let description = ref('')
let completion_date = ref('')
let task_id = ref(null)
let status_id = ref(0)
let assigned_id = ref()
let attachment = ref(null)
let by_user = ref(null)
let by_status = ref(null)
let date_from = ref('')
let date_to = ref('')
let show_filters = ref(false)

let users = ref([])
let statuses = ref([])

let tasks = ref([])

const toggleFilters = async () => {
    if (show_filters.value) {
        await dropFilters()
    }
    show_filters.value = !show_filters.value
}

const dropFilters = async () => {
    by_user.value = null
    by_status.value = null
    date_from.value = null
    date_to.value = null

    let result = await getTasks()
    if (result.success) {
        tasks.value = result.data
    }
}

const goFilter = async () => {
    let filters = {
        user_id: by_user.value,
        status_id: by_status.value,
        date_from: date_from.value,
        date_to: date_to.value
    }
    let result = await getTasks(filters)
    if (result.success) {
        tasks.value = result.data
    }
}

const handleFileUpload = (event) => {
    attachment.value = event.target.files[0]
}

let goDeleteTask = async (task_id) => {
    let result = await deleteTask(task_id)

    if (result.success) {
        tasks.value = tasks.value.filter(elem => elem.id != task_id)
    }
}

let editTask = (task) => {
    mode.value = 'edit'
    task_id.value = task.id
    expanded.value = true

    title.value = task.title
    description.value = task.description
    completion_date.value = task.completion_date ? task.completion_date : ''
    status_id.value = task.status_id
    assigned_id.value = task.assigned_id
}

let createOrUpdateTask = async () => {
    expanded.value = true

    const formData = new FormData()

    formData.append('title', title.value)
    formData.append('description', description.value)
    formData.append('status_id', status_id.value)
    formData.append('assigned_id', assigned_id.value)
    formData.append('completion_date', completion_date.value)

    if (attachment.value) {
        formData.append('attachment', attachment.value)
    }

    let result = undefined

    if (mode.value === 'create') {
        result = await storeTask(formData)
    }
    else {
        result = await updateTask(formData, task_id.value)
    }

    if (result.success) {
        console.log(result.task.id)
        if (mode.value === 'edit') {
            tasks.value = tasks.value.filter(elem => elem.id != result.task.id)
        }
        tasks.value.push(result.task)
        title.value = ''
        description.value = ''
        completion_date.value = ''
        status_id.value = 0
        assigned_id.value = 0
        attachment.value = null
        task_id.value = null

        mode.value = 'create'
        expanded.value = false
    }
}

onMounted(async () => {
    let filters = await getFilters()
    if (filters.success) {
        users.value = filters.users
        statuses.value = filters.statuses

        status_id.value = statuses.value[0].id
    }

    let result = await getTasks()
    if (result.success) {
        tasks.value = result.data
    }
})
</script>