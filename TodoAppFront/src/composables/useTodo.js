import axios from '../axios'; // Your Axios instance
import { ref, onMounted } from 'vue';

export function useTodo() {
    const todos = ref([]);
    const loading = ref(false);
    const error = ref(null);

    // Fetch all todos
    const fetchTodos = async () => {
        loading.value = true;
        try {
            const response = await axios.get('/api/todos');
            todos.value = response.data;
        } catch (err) {
            error.value = err.response?.data?.message || 'Error fetching todos';
        } finally {
            loading.value = false;
        }
    };

    const createTodo = async (newTodo) => {
        loading.value = true;
        try {
            const response = await axios.post('/api/todos', newTodo);
            todos.value.push(response.data);
        } catch (err) {
            error.value = err.response?.data?.message || 'Error creating todo';
        } finally {
            loading.value = false;
        }
    };

    const updateTodo = async (id, updatedFields) => {
        loading.value = true;
        try {
            await axios.put(`/api/todos/${id}`, updatedFields);
            const index = todos.value.findIndex(todo => todo.id === id);
            if (index !== -1) {
                todos.value[index].name = updatedFields.name; // Update the name
            }
        } catch (err) {
            error.value = err.response?.data?.message || 'Error updating todo';
        } finally {
            loading.value = false;
        }
    };

    const updateTodoStatus = async (id, updatedFields) => {
        loading.value = true;
        try {
            await axios.put(`/api/todos/${id}`, updatedFields);
        } catch (err) {
            error.value = err.response?.data?.message || 'Error updating status';
        } finally {
            loading.value = false;
        }
    };

    const deleteTodo = async (id) => {
        const confirmed = confirm("Are you sure you want to delete this todo?");
        if (!confirmed) return; // Exit if the user cancels

        loading.value = true;
        try {
            await axios.delete(`/api/todos/${id}`);
            todos.value = todos.value.filter(todo => todo.id !== id); // Remove the deleted todo
        } catch (err) {
            error.value = err.response?.data?.message || 'Error deleting todo';
        } finally {
            loading.value = false;
        }
    };

    // Fetch all todos when the component is mounted
    onMounted(fetchTodos);

    return {
        todos,
        loading,
        error,
        createTodo,
        updateTodo,
        updateTodoStatus,
        deleteTodo
    };
}
