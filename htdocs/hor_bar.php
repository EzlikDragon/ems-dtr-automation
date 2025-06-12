
<table class="employee-details-table">
    <tr>
        <th>Last Name</th>
        <th>First Name</th>
        <th>Middle Name</th>
        <th>Extension Name</th>
        <th>BIO ID</th>
        <th>Status</th>
    </tr>
    <tr>
        <td><?= htmlspecialchars($employee['last_name']) ?></td>
        <td><?= htmlspecialchars($employee['first_name']) ?></td>
        <td><?= htmlspecialchars($employee['middle_name'] ?? 'N/A') ?></td>
        <td><?= htmlspecialchars($employee['ext_name'] ?? 'N/A') ?></td>
        <td><?= htmlspecialchars($employee['bio_id']) ?></td>
        <td><?= htmlspecialchars($employee['status']) ?></td>
    </tr>
</table>

<nav class="horbar">
  <div class="logo">
  </div>
  <ul class="nav-links">
  <li><a href="view_employee.php?id=<?= $emp_id ?>" class="active">Personal</a></li>
            <li><a href="family.php?id=<?= $emp_id ?>">Family</a></li>
            <li><a href="education.php?id=<?= $emp_id ?>">Education/CSC</a></li>
            <li><a href="work.php?id=<?= $emp_id ?>">Work/Voluntary</a></li>
            <li><a href="training.php?id=<?= $emp_id ?>">Training/Others</a></li>
            <li><a href="ixa.php?id=<?= $emp_id ?>">IX-A</a></li>
            <li><a href="ixb.php?id=<?= $emp_id ?>">IX-B</a></li>
            <li><a href="payroll.php?id=<?= $emp_id ?>">Payroll Data</a></li>
            <li><a href="leave.php?id=<?= $emp_id ?>">Leave Ledger</a></li>
            <li><a href="compensatory.php?id=<?= $emp_id ?>">Compensatory Ledger</a></li>
            <li><a href="service_record.php?id=<?= $emp_id ?>">Service Record</a></li>
  </ul>
</nav>
