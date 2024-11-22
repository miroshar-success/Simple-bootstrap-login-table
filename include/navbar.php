<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Network Devices DB</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="<?= basename($_SERVER['PHP_SELF']) == 'indexEQIP.php' ? 'active' : '' ?>">
        <a href="indexEQIP.php">HOME</a>
      </li>
      <li class="<?= basename($_SERVER['PHP_SELF']) == 'lapinv.php' ? 'active' : '' ?>">
        <a href="lapinv.php">LAPTOP INVENTORY</a>
      </li>
      <li class="<?= basename($_SERVER['PHP_SELF']) == 'addNewEntry.php' ? 'active' : '' ?>">
        <a href="addNewEntry.php">ADD NEW ENTRY</a>
      </li>
      <li class="<?= basename($_SERVER['PHP_SELF']) == 'applicationsDB.php' ? 'active' : '' ?>">
        <a href="applicationsDB.php">APPLICATIONS DB</a>
      </li>
      <li class="<?= basename($_SERVER['PHP_SELF']) == 'laptopDB.php' ? 'active' : '' ?>">
        <a href="laptopDB.php">LAPTOP DB</a>
      </li>
      <li class="<?= basename($_SERVER['PHP_SELF']) == 'serverDB.php' ? 'active' : '' ?>">
        <a href="serverDB.php">SERVER DB</a>
      </li>
      <li class="<?= basename($_SERVER['PHP_SELF']) == 'wirelessAPs.php' ? 'active' : '' ?>">
        <a href="wirelessAPs.php">WIRELESS-AP's</a>
      </li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="include/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
    </ul>
  </div>
</nav>
