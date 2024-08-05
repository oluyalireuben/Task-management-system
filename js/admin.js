document.addEventListener('DOMContentLoaded', () => {
    fetchUsers();
    fetchTasks();
    fetchCategories();

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
                fetchCategories();
                e.target.reset();
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    document.getElementById('notificationForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.append('action', 'sendNotification');

        try {
            const response = await fetch('php/admin.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                alert(result.message);
                e.target.reset();
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
                    <button onclick="editUser(${user.id})">Edit</button>
                    <button onclick="deleteUser(${user.id})">Delete</button>
                </td>
            `;
            userList.appendChild(row);
        });
    } catch (error) {
        console.error('Error fetching users:', error);
    }
}

async function fetchTasks() {
    try {
        const response = await fetch('php/admin.php?action=getTasks');
        const tasks = await response.json();
        const taskList = document.getElementById('taskList');
        taskList.innerHTML = '';

        tasks.forEach(task => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${task.title}</td>
                <td>${task.description}</td>
                <td>${task.status}</td>
                <td>${task.due_date}</td>
                <td>
                    <button onclick="editTask(${task.id})">Edit</button>
                    <button onclick="deleteTask(${task.id})">Delete</button>
                </td>
            `;
            taskList.appendChild(row);
        });
    } catch (error) {
        console.error('Error fetching tasks:', error);
    }
}

async function fetchCategories() {
    try {
        const response = await fetch('php/admin.php?action=getCategories');
        const categories = await response.json();
        const categoryList = document.getElementById('categoryList');
        categoryList.innerHTML = '';

        categories.forEach(category => {
            const listItem = document.createElement('li');
            listItem.innerHTML = `
                ${category.name}
                <button onclick="editCategory(${category.id})">Edit</button>
                <button onclick="deleteCategory(${category.id})">Delete</button>
            `;
            categoryList.appendChild(listItem);
        });
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
}

async function search() {
    const query = document.getElementById('searchInput').value;

    try {
        const response = await fetch(`php/admin.php?action=search&query=${query}`);
        const results = await response.json();
        // Handle search results (populate relevant sections)
    } catch (error) {
        console.error('Error searching:', error);
    }
}

async function generateReports() {
    try {
        const response = await fetch('php/admin.php?action=generateReports');
        const reportContent = await response.text();
        document.getElementById('reportContent').innerHTML = reportContent;
    } catch (error) {
        console.error('Error generating reports:', error);
    }
}

async function editUser(userId) {
    const newEmail = prompt("Enter new email for user:");
    const newRole = prompt("Enter new role for user (admin/user):");

    if (newEmail && newRole) {
        try {
            const response = await fetch('php/admin.php', {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'editUser',
                    userId: userId,
                    email: newEmail,
                    role: newRole
                })
            });
            const result = await response.json();

            if (result.status === 'success') {
                alert(result.message);
                fetchUsers();
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error editing user:', error);
        }
    }
}

async function deleteUser(userId) {
    if (confirm("Are you sure you want to delete this user?")) {
        try {
            const response = await fetch('php/admin.php', {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'deleteUser',
                    userId: userId
                })
            });
            const result = await response.json();

            if (result.status === 'success') {
                alert(result.message);
                fetchUsers();
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error deleting user:', error);
        }
    }
}

async function editTask(taskId) {
    const newTitle = prompt("Enter new title for task:");
    const newDescription = prompt("Enter new description for task:");
    const newStatus = prompt("Enter new status for task:");
    const newDueDate = prompt("Enter new due date for task:");

    if (newTitle && newDescription && newStatus && newDueDate) {
        try {
            const response = await fetch('php/admin.php', {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'editTask',
                    taskId: taskId,
                    title: newTitle,
                    description: newDescription,
                    status: newStatus,
                    dueDate: newDueDate
                })
            });
            const result = await response.json();

            if (result.status === 'success') {
                alert(result.message);
                fetchTasks();
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error editing task:', error);
        }
    }
}

async function deleteTask(taskId) {
    if (confirm("Are you sure you want to delete this task?")) {
        try {
            const response = await fetch('php/admin.php', {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'deleteTask',
                    taskId: taskId
                })
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
}

async function editCategory(categoryId) {
    const newCategoryName = prompt("Enter new name for category:");

    if (newCategoryName) {
        try {
            const response = await fetch('php/admin.php', {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'editCategory',
                    categoryId: categoryId,
                    name: newCategoryName
                })
            });
            const result = await response.json();

            if (result.status === 'success') {
                alert(result.message);
                fetchCategories();
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error editing category:', error);
        }
    }
}

async function deleteCategory(categoryId) {
    if (confirm("Are you sure you want to delete this category?")) {
        try {
            const response = await fetch('php/admin.php', {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'deleteCategory',
                    categoryId: categoryId
                })
            });
            const result = await response.json();

            if (result.status === 'success') {
                alert(result.message);
                fetchCategories();
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error deleting category:', error);
        }
    }
}
