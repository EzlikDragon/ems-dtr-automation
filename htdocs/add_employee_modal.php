
<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_employee.php" method="POST">
                    <!-- 🔷 Employment Information -->
          <div class="form-group">
            <label for="bio_id">Bio ID:</label>
            <input type="text" class="form-control" id="bio_id" name="bio_id" required>
          </div>
          <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
          </div>
          <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
          </div>
          <div class="form-group">
            <label for="middle_name">Middle Name:</label>
            <input type="text" class="form-control" id="middle_name" name="middle_name">
          </div>
          <div class="form-group">
            <label for="ext_name">Extension Name:</label>
            <input type="text" class="form-control" id="ext_name" name="ext_name">
          </div>
          <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" class="form-control" required>
                    <option value="PERMANENT">Permanent</option>
                    <option value="CASUAL">Casual</option>
                    <option value="JOB ORDER">Job Order</option>
                    <option value="ELECTED">Elected</option>
                </select>
          </div>
          <div class="form-group">
            <label for="unit">Unit:</label>
            <input type="text" class="form-control" id="unit" name="unit" required>
          </div>
          <div class="form-group">
            <label for="dtr_group">DTR Group:</label>
            <input type="text" class="form-control" id="dtr_group" name="dtr_group">
          </div>
          <div class="form-group">
            <label for="charged_office">Charged Office:</label>
            <input type="text" class="form-control" id="charged_office" name="charged_office">
          </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add Employee</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
        </form>
        </div>
        </div>
    </div>
    </div>
</div>
