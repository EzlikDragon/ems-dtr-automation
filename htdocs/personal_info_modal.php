<!-- 🔷 Personal Information Edit Modal -->
<div class="modal fade" id="editPersonalModal" tabindex="-1" aria-labelledby="editPersonalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="update_personal_info.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPersonalModalLabel">Edit Personal Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="emp_id" value="<?= $emp_id ?>">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($employee['first_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($employee['last_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control" value="<?= htmlspecialchars($employee['date_of_birth'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Place of Birth</label>
                            <input type="text" name="place_of_birth" class="form-control" value="<?= htmlspecialchars($employee['place_of_birth'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-control">
                                <option value="MALE" <?= $employee['gender'] == 'MALE' ? 'selected' : '' ?>>Male</option>
                                <option value="FEMALE" <?= $employee['gender'] == 'FEMALE' ? 'selected' : '' ?>>Female</option>
                                <option value="OTHER" <?= $employee['gender'] == 'OTHER' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="<?= htmlspecialchars(($employee['house_block_lot'] ?? '') . ', ' . ($employee['street'] ?? '') . ', ' . ($employee['barangay'] ?? '') . ', ' . ($employee['city_municipality'] ?? '') . ', ' . ($employee['province'] ?? '')) ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 🔷 Family Information Edit Modal -->
<div class="modal fade" id="editFamilyModal" tabindex="-1" aria-labelledby="editFamilyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="update_family_info.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFamilyModalLabel">Edit Family Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="emp_id" value="<?= $emp_id ?>">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Father's Name</label>
                            <input type="text" name="father_name" class="form-control" value="<?= htmlspecialchars(($family['father_first_name'] ?? '') . ' ' . ($family['father_surname'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mother's Name</label>
                            <input type="text" name="mother_name" class="form-control" value="<?= htmlspecialchars(($family['mother_first_name'] ?? '') . ' ' . ($family['mother_surname'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Spouse Name</label>
                            <input type="text" name="spouse_name" class="form-control" value="<?= htmlspecialchars(($family['spouse_first_name'] ?? '') . ' ' . ($family['spouse_surname'] ?? '')) ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
