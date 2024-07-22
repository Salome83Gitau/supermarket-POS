document.addEventListener('DOMContentLoaded', function() {
    const addUserBtn = document.getElementById('addUserBtn');
    const addUserPopup = document.getElementById('addUserPopup');
    const editUserPopup = document.getElementById('editUserPopup');
    const deleteUserPopup = document.getElementById('deleteUserPopup');
    const passwordConfirmationPopup = document.getElementById('passwordConfirmationPopup');

    const closeBtns = document.querySelectorAll('.close-btn');
    const cancelBtns = document.querySelectorAll('.cancel-btn');

    addUserBtn.addEventListener('click', function() {
        addUserPopup.style.display = 'block';
    });

    closeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            btn.parentElement.parentElement.style.display = 'none';
        });
    });

    cancelBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            btn.parentElement.parentElement.parentElement.style.display = 'none';
        });
    });

    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.id;
            // Fetch user data via AJAX or pass via data attributes and populate form
            // Example with static data:
            document.getElementById('editUserId').value = userId;
            document.getElementById('editUsername').value = "username"; // Replace with actual data
            document.getElementById('editName').value = "name"; // Replace with actual data
            document.getElementById('editEmail').value = "email@example.com"; // Replace with actual data
            document.getElementById('editRole').value = "role"; // Replace with actual data

            editUserPopup.style.display = 'block';
        });
    });

    document.querySelectorAll('.deleteBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.id;
            document.getElementById('deleteUserId').value = userId;
            deleteUserPopup.style.display = 'block';
        });
    });

    // Show password confirmation popup before delete or edit form submission
    document.getElementById('editUserForm').addEventListener('submit', function(event) {
        event.preventDefault();
        passwordConfirmationPopup.style.display = 'block';
        // Attach additional data to form if necessary
    });

    document.getElementById('deleteUserForm').addEventListener('submit', function(event) {
        event.preventDefault();
        passwordConfirmationPopup.style.display = 'block';
        // Attach additional data to form if necessary
    });

    document.getElementById('passwordConfirmationForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const adminPassword = document.querySelector('input[name="adminPassword"]').value;
        // Verify admin password via AJAX or form submission
        // If successful, submit the original form (edit or delete)
    });
});
