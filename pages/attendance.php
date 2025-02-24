<?php
session_start();
require_once('../classes/actions.class.php');
$actionClass = new Actions();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$classList = $actionClass->list_class();
$class_id = $_GET['class_id'] ?? "";
$class_date = $_GET['class_date'] ?? "";
$studentList = $actionClass->attendanceStudents($class_id, $class_date);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Attendance</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="page-title mb-3">Manage Attendance</div>
        <hr>
        <form action="" id="manage-attendance">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div id="msg"></div>
                    <div class="card shadow mb-3">
                        <div class="card-body rounded-0">
                            <div class="container-fluid">
                                <div class="row align-items-end">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                        <label for="class_id" class="form-label">Class</label>
                                        <select name="class_id" id="class_id" class="form-select" required>
                                            <option value="" disabled <?= empty($class_id) ? "selected" : "" ?>> -- Select Here -- </option>
                                            <?php foreach ($classList as $row): ?>
                                                <option value="<?= $row['id'] ?>" <?= (isset($class_id) && $class_id == $row['id']) ? "selected" : "" ?>><?= $row['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                        <label for="class_date" class="form-label">Date</label>
                                        <input type="date" name="class_date" id="class_date" class="form-control" value="<?= $class_date ?? '' ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($class_id) && !empty($class_date)): ?>
                        <div class="card shadow mb-3">
                            <div class="card-header rounded-0">
                                <div class="card-title">Attendance Sheet</div>
                            </div>
                            <div class="card-body">
                                <div class="container-fluid">
                                    <div class="table-responsive">
                                        <table id="attendance-tbl" class="table table-bordered">
                                            <colgroup>
                                                <col width="40%">
                                                <col width="15%">
                                                <col width="15%">
                                                <col width="15%">
                                                <col width="15%">
                                            </colgroup>
                                            <thead class="bg-primary">
                                                <tr>
                                                    <th class="text-center bg-transparent text-light">Students</th>
                                                    <th class="text-center bg-transparent text-light">Present</th>
                                                    <th class="text-center bg-transparent text-light">Late</th>
                                                    <th class="text-center bg-transparent text-light">Absent</th>
                                                    <th class="text-center bg-transparent text-light">Holiday</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th class="text-center px-2 py-1 text-dark-emphasis">Check/Uncheck All</th>
                                                    <th class="text-center px-2 py-1 text-dark-emphasis">
                                                        <div class="form-check d-flex w-100 justify-content-center">
                                                            <input class="form-check-input checkAll" type="checkbox" id="PCheckAll">
                                                            <label class="form-check-label" for="PCheckAll"></label>
                                                        </div>
                                                    </th>
                                                    <th class="text-center px-2 py-1 text-dark-emphasis">
                                                        <div class="form-check d-flex w-100 justify-content-center">
                                                            <input class="form-check-input checkAll" type="checkbox" id="LCheckAll">
                                                            <label class="form-check-label" for="LCheckAll"></label>
                                                        </div>
                                                    </th>
                                                    <th class="text-center px-2 py-1 text-dark-emphasis">
                                                        <div class="form-check d-flex w-100 justify-content-center">
                                                            <input class="form-check-input checkAll" type="checkbox" id="ACheckAll">
                                                            <label class="form-check-label" for="ACheckAll"></label>
                                                        </div>
                                                    </th>
                                                    <th class="text-center px-2 py-1 text-dark-emphasis">
                                                        <div class="form-check d-flex w-100 justify-content-center">
                                                            <input class="form-check-input checkAll" type="checkbox" id="HCheckAll">
                                                            <label class="form-check-label" for="HCheckAll"></label>
                                                        </div>
                                                    </th>
                                                </tr>
                                                <?php if (!empty($studentList) && is_array($studentList)): ?>
                                                    <?php foreach ($studentList as $row): ?>
                                                        <tr class="student-row">
                                                            <td class="px-2 py-1 text-dark-emphasis fw-bold">
                                                                <input type="hidden" name="student_id[]" value="<?= $row['id'] ?>">
                                                                <?= $row['name'] ?>
                                                            </td>
                                                            <td class="text-center px-2 py-1 text-dark-emphasis">
                                                                <div class="form-check d-flex w-100 justify-content-center">
                                                                    <input class="form-check-input status_check" data-id="<?= $row['id'] ?>" type="checkbox" name="status[]" value="1" id="status_p_<?= $row['id'] ?>" <?= (isset($row['status']) && $row['status'] == 1) ? "checked" : "" ?>>
                                                                    <label class="form-check-label" for="status_p_<?= $row['id'] ?>"></label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center px-2 py-1 text-dark-emphasis">
                                                                <div class="form-check d-flex w-100 justify-content-center">
                                                                    <input class="form-check-input status_check" data-id="<?= $row['id'] ?>" type="checkbox" name="status[]" value="2" id="status_l_<?= $row['id'] ?>" <?= (isset($row['status']) && $row['status'] == 2) ? "checked" : "" ?>>
                                                                    <label class="form-check-label" for="status_l_<?= $row['id'] ?>"></label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center px-2 py-1 text-dark-emphasis">
                                                                <div class="form-check d-flex w-100 justify-content-center">
                                                                    <input class="form-check-input status_check" data-id="<?= $row['id'] ?>" type="checkbox" name="status[]" value="3" id="status_a_<?= $row['id'] ?>" <?= (isset($row['status']) && $row['status'] == 3) ? "checked" : "" ?>>
                                                                    <label class="form-check-label" for="status_a_<?= $row['id'] ?>"></label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center px-2 py-1 text-dark-emphasis">
                                                                <div class="form-check d-flex w-100 justify-content-center">
                                                                    <input class="form-check-input status_check" data-id="<?= $row['id'] ?>" type="checkbox" name="status[]" value="4" id="status_h_<?= $row['id'] ?>" <?= (isset($row['status']) && $row['status'] == 4) ? "checked" : "" ?>>
                                                                    <label class="form-check-label" for="status_h_<?= $row['id'] ?>"></label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="5" class="px-2 py-1 text-center">No Student Listed Yet</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex w-100 justify-content-center align-items-center">
                            <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                                <button class="btn btn-primary rounded-pill w-100" type="submit">Save Attendance</button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function(){
            // Function to count checked checkboxes
            function checkAll_count() {
                var statuses = {'PCheckAll': 1, 'LCheckAll': 2, 'ACheckAll': 3, 'HCheckAll': 4};
                $('.checkAll').each(function() {
                    var id = $(this).attr('id');
                    var checkedCount = $(`.status_check[value="${statuses[id]}"]:checked`).length;
                    var totalCount = $(`.status_check[value="${statuses[id]}"]`).length;
                    $(this).prop('checked', totalCount === checkedCount);
                });
            }

            checkAll_count();

            $('#class_id, #class_date').change(function(e){
                var class_id = $('#class_id').val();
                var class_date = $('#class_date').val();
                location.replace(`./?page=attendance&class_id=${class_id}&class_date=${class_date}`);
            });

            $('.status_check').change(function(){
                checkAll_count();
            });

            $('.checkAll').change(function(){
                var _this = $(this);
                var isChecked = $(this).is(":checked");
                var id = $(this).attr('id');
                if (isChecked) {
                    $('.status_check').prop('checked', false);
                    $('.status_check[value="' + (id === 'PCheckAll' ? '1' : id === 'LCheckAll' ? '2' : id === 'ACheckAll' ? '3' : '4') + '"]').prop('checked', true);
                } else {
                    $('.status_check[value="' + (id === 'PCheckAll' ? '1' : id === 'LCheckAll' ? '2' : id === 'ACheckAll' ? '3' : '4') + '"]').prop('checked', false);
                }
                checkAll_count();
            });

            $('#manage-attendance').submit(function(e){
                e.preventDefault();
                var hasUncheckedStudents = false;

                $('#attendance-tbl .student-row').each(function(){
                    var hasChecks = $(this).find('.status_check:checked').length;
                    if (hasChecks < 1) {
                        hasUncheckedStudents = true;
                        var name = $(this).find('td').first().text().trim();
                        alert(`${name}'s attendance is not yet marked!`);
                        return false;
                    }
                });

                if (hasUncheckedStudents) return;

                $.ajax({
                    url: './ajax-api.php?action=save_attendance',
                    method: 'POST',
                    data: $(this).serialize(),
                    dataType: 'JSON',
                    error: function(err) {
                        console.error(err);
                        alert("An error occurred while saving the data. Please reload the page.");
                    },
                    success: function(resp) {
                        if (resp.status === "success") {
                            location.reload();
                        } else {
                            alert("An error occurred while saving the data. Please reload the page.");
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>