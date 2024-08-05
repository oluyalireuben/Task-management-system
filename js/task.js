document.getElementById('taskForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    formData.append('action', 'create');

    const response = await fetch('php/task.php', {
        method: 'POST',
        body: formData
    });
    const result = await response.json();

    if (result.status === 'success') {
        alert(result.message);
        loadTasks();
    } else {
        alert(result.message);
    }
});

async function loadTasks() {
    const response = await fetch('php/task.php');
    const result = await response.json();

    if (result.status === 'success') {
        const taskList = document.getElementById('taskList');
        taskList.innerHTML = '';

        result.tasks.forEach(task => {
            const taskItem = document.createElement('li');
            taskItem.textContent = `${task.title} - ${task.description} - ${task.due_date} - ${task.status} - ${task.category}`;
            taskList.appendChild(taskItem);
        });
    } else {
        alert(result.message);
    }
}

document.addEventListener('DOMContentLoaded', loadTasks);
