<nav class="navbar">
  <div class="logo">
    <h1>DTR System</h1>
  </div>
  <ul class="nav-links">
    <li>
      <a href="../admin_dashboard.php">
        <img src="../images/dashboard.png" alt="Home" />
        <span>Home</span>
      </a>
    </li>
    <li>
      <a href="../logout.php">
        <img src="../images/logout.png" alt="Logout" />
        <span>Logout</span>
      </a>
    </li>
  </ul>
</nav>

<style>
.navbar {
    background-color: #343a40;
    padding: 15px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.navbar .logo h1 {
    color: #fff;
    margin: 0;
    font-size: 20px;
}
.nav-links {
    list-style: none;
    display: flex;
    margin: 0;
    padding: 0;
}
.nav-links li {
    margin-left: 20px;
}
.nav-links a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    display: flex;
    align-items: center;
}
.nav-links a img {
    width: 18px;
    height: 18px;
    margin-right: 8px;
}
</style>
