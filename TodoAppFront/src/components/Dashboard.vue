<script setup>
import {ref, onMounted} from 'vue';
import {useAuth} from '../composables/useAuth';
import {useTodo} from '../composables/useTodo';

const {user, logout} = useAuth();
const {todos, loading, error, createTodo, deleteTodo, updateTodo, updateTodoStatus} = useTodo();

const newTodo = ref({
    name: ''
});

const submitNewTodo = async () => {
    await createTodo(newTodo.value);
    newTodo.value.name = ''; // Clear the form after adding the todo
};

// Modal state and todo for editing
const showModal = ref(false);
const editTodo = ref({
    id: null,
    name: ''
});

// Open the modal for editing
const openEditModal = (todo) => {
    editTodo.value = {...todo}; // Clone the todo to avoid direct mutation
    showModal.value = true;       // Show the modal
};

// Close the modal
const closeModal = () => {
    showModal.value = false; // Hide the modal
};

// Save changes to the todo
const updateTodoAndCloseModal = async () => {
    await updateTodo(editTodo.value.id, {name: editTodo.value.name});
    closeModal(); // Close the modal after saving
};

// Update the status of the todo
const updateStatus = async (id, currentStatus) => {
    const newStatus = currentStatus ? 1 : 0; // Set completed (1) or not completed (0)
    await updateTodoStatus(id, {status: newStatus});
    // Update the local state immediately for instant feedback
    const index = todos.value.findIndex(todo => todo.id === id);
    if (index !== -1) {
        todos.value[index].status = newStatus;
    }
};
</script>

<template>
    <div class="container p-4 mt-5 rounded-4">
        <div class="auth d-flex justify-content-between align-items-center">
            <h1 class="greeting fs-2">Hi, <span class="user-name">{{ user?.name }}</span>!</h1>
            <button class="btn bg-purple my-custom-btn" @click="logout">Logout</button>
        </div>
        <div class="content m-auto p-3 rounded-4 d-flex justify-content-center">

            <form @submit.prevent="submitNewTodo" class="add-todo-form">
                <div class="add-todo-section d-flex justify-content-between align-items-center">
                    <input required v-model="newTodo.name" class="form-control me-2 ps-3" type="text"
                           placeholder="What’s on your to-do list?">
                    <button type="submit" class="btn add-todo my-custom-btn">Add</button>
                </div>
                <div class="todo-list">
                    <div class="todo-img-div d-flex justify-content-center align-items-center p-3">
                        <img class="todo-img" src="/assets/todo.gif" alt="">
                    </div>
                    <div class="text-success text-center mt-2" v-if="loading">Loading...</div>
                    <div class="text-danger text-center mt-2" v-if="error">{{ error }}</div>
                    <ul class="list-unstyled pe-4 ps-4 todo-list-ul">
                        <li v-for="todo in todos" :key="todo.id"
                            class="d-flex justify-content-between align-items-center">
                            <div class="custom-checkbox-container d-flex align-items-center">
                                <input type="checkbox"
                                       :checked="todo.status === 1"
                                       @change="updateStatus(todo.id, !todo.status)"
                                       id="checkbox-todo-{{ todo.id }}"
                                       class="custom-checkbox me-4">
                                <label :for="'checkbox-todo-' + todo.id
                                       "
                                       :class="{ 'text-decoration-line-through': todo.status === 1 }"
                                       class="text-secondary fs-6">{{ todo.name }}</label>
                            </div>
                            <div>
                                <button @click="openEditModal(todo)" class="btn btn-link text-warning">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="deleteTodo(todo.id)" class="btn btn-link text-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </li>
                    </ul>

                    <!-- Edit Modal -->
                    <div v-if="showModal" class="modal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Todo</h5>
                                    <button type="button" class="btn-close" @click="closeModal"></button>
                                </div>
                                <div class="modal-body">
                                    <input v-model="editTodo.name" class="form-control" placeholder="Edit Todo"/>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" @click="updateTodoAndCloseModal">Save
                                        changes
                                    </button>
                                    <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.container {
    width: 80%;
    height: 650px;
    background-image: url("../../public/assets/todo-bg-image.jpg");
    background-size: cover;
    background-repeat: no-repeat;
}

.greeting {
    color: #5b00ff;
}

.content {
    width: 80%;
    height: 80%;
}

.add-todo {
    background-color: #5b00ff;
    color: white;
}

.bg-purple {
    background-color: #5b00ff;
    color: white;
}

.add-todo-form {
    width: 100%;
    display: flex;
    flex-direction: column;
}

.add-todo-section, .todo-list {
    width: 100%;
}

.todo-list {
    background-color: white;
    border-radius: 10px;
    height: 100%;
    margin-top: 30px;
}

.custom-checkbox {
    appearance: none;
    width: 17px;
    height: 17px;
    border: 2px solid mediumpurple;
    border-radius: 5px;
    background-color: transparent;
    position: relative;
    cursor: pointer;
    outline: none;
}

.custom-checkbox:checked {
    background-color: #5b00ff;
    border-color: #5b00ff;
}

.custom-checkbox:checked::after {
    content: '\f00c'; /* Font Awesome check mark */
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    color: white;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 14px;
}

.todo-list-ul {
    max-height: 280px;
    overflow: scroll;
}

.todo-img {
    width: 150px;
}

.modal {
    display: block;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-dialog {
    max-width: 500px;
}

.text-decoration-line-through {
    text-decoration: line-through;
}

.my-custom-btn:focus, .my-custom-btn:active {
    background-color: #5b00ff !important; /* Keeps the background color */
    color: #ffff !important; /* Keeps the text color */
    box-shadow: none; /* Removes the shadow */
    border-color: #5b00ff;
}
</style>
