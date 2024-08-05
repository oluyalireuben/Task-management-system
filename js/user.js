document.addEventListener('DOMContentLoaded', () => {
    fetchTasks();
    fetchCategories();

    document.getElementById('taskForm').addEventListener('submit', addTask);
});

async function fetchTasks() {
    try {
        const response = await fetch('php/task.php?action=getUserTasks');
        const tasks = await response.json();
        const taskList = document.getElementById('taskList');
        taskList.innerHTML = tasks.map(task => `
            <li>
                <h3>${task.title}</h3>
                <p>${task.description}</p>
                <p>Due: ${task.due_date}</p>
                <p>Category: ${task.category}</p>
                <button onclick="editTask(${task.id})">Edit</button>
                <button onclick="deleteTask(${task.id})">Delete</button>
            </li>
        `).join('');
    } catch (error) {
        console.error('Error fetching tasks:', error);
    }
}

async function fetchCategories() {
    try {
        const response = await fetch('php/task.php?action=getCategories');
        const categories = await response.json();
        const categorySelect = document.getElementById('taskCategory');
        categorySelect.innerHTML = categories.map(category => `
            <option value="${category.id}">${category.name}</option>
        `).join('');
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
}

async function addTask(e) {
    e.preventDefault();
    const taskForm = document.getElementById('taskForm');
    const formData = new FormData(taskForm);
    formData.append('action', 'addTask');

    try {
        const response = await fetch('php/task.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.status === 'success') {
            alert(result.message);
            taskForm.reset();
            fetchTasks();
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error('Error adding task:', error);
    }
}

async function editTask(taskId) {
    // Implement task editing functionality
}

async function deleteTask(taskId) {
    try {
        const response = await fetch(`php/task.php?action=deleteTask&id=${taskId}`, {
            method: 'GET'
        });
        const result = await response.json();
        if (result.status === 'success') {
            alert(result.message);
            fetchTasks();
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error('Error deleting task:', error);
    }
}
