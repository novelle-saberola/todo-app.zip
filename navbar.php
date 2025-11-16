<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php"><strong>TODO App</strong></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bstarget="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <button id="darkModeToggle" class="btn btn-sm btn-secondary ms-2">🌙 Dark Mode</button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="todos.php">TODOs</a></li>
            </ul>
            <a href="logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const body = document.body;
    const toggle = document.getElementById("darkModeToggle");

    // Restore saved mode
    if (localStorage.getItem("darkMode") === "enabled") {
        body.classList.add("dark");
        toggle.textContent = "☀️";
    }

    toggle.addEventListener("click", function () {
        body.classList.toggle("dark");

        if (body.classList.contains("dark")) {
            localStorage.setItem("darkMode", "enabled");
            toggle.textContent = "☀️"; // light mode icon
        } else {
            localStorage.removeItem("darkMode");
            toggle.textContent = "🌙"; // dark mode icon
        }
    });
});
</script>