document.addEventListener('DOMContentLoaded', () => {
    fetchUsers();
    fetchTasks();
    fetchCategories();

    // Handle category form submission
    document.getElementById('categoryForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.append('action', 'addCategory');

        try {
            const response = await fetch('php/admin.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                alert(result.message);
                fetchCategories(); // Refresh category list
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});

async function fetchUsers() {
    try {
        const response = await fetch('php/admin.php?action=getUsers');
        const contentType = response.headers.get('Content-Type');

        if (contentType && contentType.includes('application/json')) {
            const users = await response.json();
            const userList = document.getElementById('userList');
            userList.innerHTML = '';

            users.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td>
                        <!-- Add any action buttons as needed -->
                    </td>
                `;
                userList.appendChild(row);
            });
        } else {
            const text = await response.text();
            console.error('Received HTML instead of JSON:', text);
        }
    } catch (error) {
        console.error('Error fetching users:', error);
    }
}

async function fetchTasks() {
    try {
        const response = await fetch('php/admin.php?action=getTasks');
        const contentType = response.headers.get('Content-Type');

        if (contentType && contentType.includes('application/json')) {
            const tasks = await response.json();
            const taskList = document.getElementById('taskList');
            taskList.innerHTML = '';

            tasks.forEach(task => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${task.title}</td>
                    <td>${task.description}</td>
                    <td>${task.status}</td>
                    <td>${task.dueDate}</td>
                    <td>
                        <!-- Add any action buttons as needed -->
                    </td>
                `;
                taskList.appendChild(row);
            });
        } else {
            const text = await response.text();
            console.error('Received HTML instead of JSON:', text);
        }
    } catch (error) {
        console.error('Error fetching tasks:', error);
    }
}

async function fetchCategories() {
    try {
        const response = await fetch('php/admin.php?action=getCategories');
        const contentType = response.headers.get('Content-Type');

        if (contentType && contentType.includes('application/json')) {
            const categories = await response.json();
            const categoryList = document.getElementById('categoryList');
            categoryList.innerHTML = '';

            categories.forEach(category => {
                const listItem = document.createElement('li');
                listItem.textContent = category.name;
                categoryList.appendChild(listItem);
            });
        } else {
            const text = await response.text();
            console.error('Received HTML instead of JSON:', text);
        }
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
}
