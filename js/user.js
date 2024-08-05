document.addEventListener('DOMContentLoaded', () => {
    fetchTasks();

    document.getElementById('taskForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.append('action', 'addTask');

        try {
            const response = await fetch('php/task.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                alert(result.message);
                fetchTasks(); // Refresh the task list
                e.target.reset(); // Clear the form
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});

async function fetchTasks() {
    try {
        const response = await fetch('php/task.php?action=getTasks');
        const contentType = response.headers.get('Content-Type');

        if (contentType && contentType.includes('application/json')) {
            const tasks = await response.json();
            const taskList = document.getElementById('taskList');
            taskList.innerHTML = '';

            tasks.forEach(task => {
                const listItem = document.createElement('li');
                listItem.textContent = `${task.title}: ${task.description} - ${task.status} (Due: ${task.due_date})`;
                taskList.appendChild(listItem);
            });
        } else {
            const text = await response.text();
            console.error('Received HTML instead of JSON:', text);
        }
    } catch (error) {
        console.error('Error fetching tasks:', error);
    }
}
